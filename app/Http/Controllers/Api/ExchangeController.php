<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Form;
use App\Models\Deposit;
use App\Models\Currency;
use App\Models\Exchange;
use App\Constants\Status;
use App\Lib\FormProcessor;
use Illuminate\Http\Request;
use App\Models\GatewayCurrency;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ExchangeController extends Controller
{


    protected $sendCurrencyRelation     = 'sendCurrency';
    protected $receivedCurrencyRelation = 'receivedCurrency';
    protected $userRelation             = 'user';

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sending_amount'     => 'required|numeric|gt:0',
            'sending_currency'   => 'required|integer',
            'receiving_currency' => 'required|integer|different:sending_currency',
        ]);

        if ($validator->fails()) {
            return jsonResponse('validation_error', $validator->errors()->all());
        }

        $sendCurrency    = Currency::enabled()->availableForSell()->find($request->sending_currency);
        $receiveCurrency = Currency::enabled()->availableForBuy()->find($request->receiving_currency);

        if (!$sendCurrency) {
            return jsonResponse('not_found', ['Sending currency not found']);
        }
        if (!$receiveCurrency) {
            return jsonResponse('not_found', ['Receiving currency not found']);
        }

        $sendAmount = $request->sending_amount;

        try {
            $sendingPercentCharge   = $sendAmount / 100 * $sendCurrency->percent_charge_for_buy;
            $sendingFixedCharge     = $sendCurrency->fixed_charge_for_buy;
            $totalSendingCharge     = $sendingFixedCharge + $sendingPercentCharge;

            $receiveAmount          = $sendCurrency->buy_at / $receiveCurrency->sell_at * $sendAmount;
            $receivingPercentCharge = $receiveAmount / 100 * $receiveCurrency->percent_charge_for_sell;
            $receivingFixedCharge   = $receiveCurrency->fixed_charge_for_sell;

            $totalReceivingCharge   = $receivingFixedCharge + $receivingPercentCharge;
            $totalReceivedAmount    = $receiveAmount - $totalReceivingCharge;
        } catch (Exception $ex) {
            return jsonResponse('exception_found', ['Something went wrong with the exchange processing.']);
        }

        if ($sendAmount < $sendCurrency->minimum_limit_for_buy) {
            $notify = ["Minimum sending amount " . showAmount($sendCurrency->minimum_limit_for_buy) . ' ' . $sendCurrency->cur_sym];
            return jsonResponse('validation_error', $notify);
        }

        if ($sendAmount > $sendCurrency->maximum_limit_for_buy) {
            $notify = ["Maximum sending amount " . showAmount($sendCurrency->maximum_limit_for_buy) . ' ' . $sendCurrency->cur_sym];
            return jsonResponse('validation_error', $notify);
        }

        if ($receiveAmount < $receiveCurrency->minimum_limit_for_sell) {
            $notify = ["Minimum received amount " . showAmount($receiveCurrency->minimum_limit_for_sell) . ' ' . $receiveCurrency->cur_sym];
            return jsonResponse('validation_error', $notify);
        }

        if ($receiveAmount > $receiveCurrency->maximum_limit_for_sell) {
            $notify = ["Maximum received amount " . showAmount($receiveCurrency->maximum_limit_for_sell) . ' ' . $receiveCurrency->cur_sym];
            return jsonResponse('validation_error', $notify);
        }

        if ($totalReceivedAmount > $receiveCurrency->reserve) {
            $notify = ["Sorry, our reserve limit exceeded"];
            return jsonResponse('validation_error', $notify);
        }

        $charge = [
            'sending_charge' => [
                'fixed_charge'   => $sendingFixedCharge,
                'percent_charge' => $sendCurrency->percent_charge_for_buy,
                'percent_amount' => $sendingPercentCharge,
                'total_charge'   => $totalSendingCharge
            ],
            'receiving_charge' => [
                'fixed_charge'   => $receivingFixedCharge,
                'percent_charge' => $receiveCurrency->percent_charge_for_sell,
                'percent_amount' => $receivingPercentCharge,
                'total_charge'   => $totalReceivingCharge
            ],
        ];

        $exchange                      = new Exchange();
        $exchange->user_id             = auth()->id();
        $exchange->send_currency_id    = $sendCurrency->id;
        $exchange->receive_currency_id = $receiveCurrency->id;
        $exchange->sending_amount      = $sendAmount;
        $exchange->sending_charge      = $totalSendingCharge;
        $exchange->receiving_amount    = $receiveAmount;
        $exchange->receiving_charge    = $totalReceivingCharge;
        $exchange->sell_rate           = $receiveCurrency->sell_at;
        $exchange->buy_rate            = $sendCurrency->buy_at;
        $exchange->exchange_id         = getTrx();
        $exchange->charge              = $charge;
        $exchange->save();

        $notify[] = 'Please provide required data for the confirm exchange';
        $data = [
            'exchange' => $exchange
        ];
        return jsonResponse('exchange_created', $notify, 'success', $data);
    }

    public function preview($id)
    {
        $exchange = Exchange::where('status', Status::EXCHANGE_INITIAL)->where('id', $id)->where('user_id', auth()->id())
            ->select('id', 'user_id', 'send_currency_id', 'receive_currency_id', 'sending_amount', 'receiving_amount', 'sending_charge', 'receiving_charge', 'charge', 'exchange_id', 'status')
            ->with('sendCurrency:id,name,image,cur_sym', 'receivedCurrency:id,name,image,cur_sym,user_detail_form_id')
            ->first();
        $imagePath = route('home') . '/' . getFilePath('currency');

        if (!$exchange) {
            $notify = ["Exchange not found"];
            return jsonResponse('not_found', $notify);
        }

        $notify[] = 'Exchange preview';
        $data = [
            'exchange'      => $exchange,
            'required_data' => @$exchange->receivedCurrency->userDetailsData,
            'image_path'    => $imagePath
        ];
        return jsonResponse('exchange_preview', $notify, 'success', $data);
    }
    public function confirm(Request $request, $id)
    {
        $exchange  = Exchange::where('status', Status::EXCHANGE_INITIAL)->where('id', $id)->where('user_id', auth()->id())->first();

        if (!$exchange) {
            $notify = ["Exchange not found"];
            return jsonResponse('not_found', $notify);
        }
        $validation = [
            'wallet_id' => 'required'
        ];

        $userRequiredData = @$exchange->receivedCurrency->userDetailsData->form_data ?? [];
        $formProcessor    = new FormProcessor();
        $validationRule   = $formProcessor->valueValidation($userRequiredData);
        $validationRule   = array_merge($validationRule, $validation);
        $validator        = Validator::make($request->all(), $validationRule);

        if ($validator->fails()) {
            return jsonResponse('validation_error', $validator->errors()->all());
        }

        $userData            = $formProcessor->processFormData($request, $userRequiredData);
        $exchange->user_data = $userData ?? null;
        $exchange->wallet_id = $request->wallet_id;
        $exchange->save();

        $notify[] = 'Please make payment for complete exchange';

        //=====automatic payment
        if ($exchange->sendCurrency->gateway_id != 0) {
            $curSymbol = $exchange->sendCurrency->cur_sym;
            $code      = $exchange->sendCurrency->gatewayCurrency->code;
            $gateway   = GatewayCurrency::where('method_code', $code)->where('currency', $curSymbol)->first();

            if (!$gateway) {
                $notify = ["Something went the wrong with exchange processing"];
                return jsonResponse('validation_error', $notify);
            }

            $amount = $exchange->sending_amount + $exchange->sending_charge;

            $deposit                  = new Deposit();
            $deposit->user_id         = auth()->id();
            $deposit->method_code     = $code;
            $deposit->method_currency = strtoupper($curSymbol);
            $deposit->amount          = $amount;
            $deposit->charge          = 0;
            $deposit->rate            = $exchange->buy_rate;
            $deposit->final_amo       = getAmount($amount);
            $deposit->btc_amo         = 0;
            $deposit->btc_wallet      = "";
            $deposit->trx             = $exchange->exchange_id;
            $deposit->try             = 0;
            $deposit->status          = 0;
            $deposit->exchange_id     = $exchange->id;
            $deposit->save();

            $data = [
                'is_autometic' => true,
                'exchange'     => $exchange,
                'redirect_url' => route('deposit.app.confirm', ['hash' => encrypt($deposit->id)])
            ];
            return jsonResponse('confirm_automatic_exchange', $notify, 'success', $data);
        }
        $data = [
            'is_autometic' => false,
            'exchange'     => $exchange,
        ];
        return jsonResponse('confirm_manual_exchange', $notify, 'success', $data);
    }

    public function manual($id)
    {
        $exchange = Exchange::where('status', Status::EXCHANGE_INITIAL)->where('id', $id)->where('user_id', auth()->id())->with($this->sendCurrencyRelation)->first();
        if (!$exchange) {
            $notify = ["Exchange not found"];
            return jsonResponse('not_found', $notify);
        }
        $formData = Form::where('id', @$exchange->sendCurrency->trx_proof_form_id)->first();
        $notify[] = 'Confirm Manual Exchange';
        $data = [
            'exchange'  => $exchange,
            'required_data' => $formData
        ];
        return jsonResponse('exchange_preview', $notify, 'success', $data);
    }
    public function manualConfirm(Request $request, $id)
    {
        $exchange = Exchange::where('status', Status::EXCHANGE_INITIAL)->where('id', $id)->where('user_id', auth()->id())->with('sendCurrency')->first();

        if (!$exchange) {
            $notify = ["Exchange not found"];
            return jsonResponse('not_found', $notify);
        }

        $transactionProvedData = @$exchange->sendCurrency->transactionProvedData->form_data ?? [];
        $formProcessor         = new FormProcessor();
        $validationRule        = $formProcessor->valueValidation($transactionProvedData);
        $validator             = Validator::make($request->all(), $validationRule);

        if ($validator->fails()) {
            return jsonResponse('validation_error', $validator->errors()->all());
        }
        $provedData = $formProcessor->processFormData($request, $transactionProvedData);

        $exchange->transaction_proof_data = $provedData ?? null;
        $exchange->status                 = Status::EXCHANGE_PENDING;
        $exchange->save();

        $comment                      = 'send ' . getAmount($exchange->get_amount) . ' by ' . @$exchange->sendCurrency->name;
        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $exchange->user_id;
        $adminNotification->title     = $comment;
        $adminNotification->click_url = urlPath('admin.exchange.details', $exchange->id);
        $adminNotification->save();

        $notify = ["Thank you for your exchange. Admin will review your request"];
        $data   = [
            'exchange' => $exchange
        ];

        return jsonResponse('exchange_success', $notify, 'success', $data);
    }

    public function list($scope = 'list')
    {
        try {
            $imagePath = route('home') . '/' . getFilePath('currency');
            $exchanges = Exchange::$scope()->where('user_id', auth()->id())
                ->with($this->sendCurrencyRelation, $this->receivedCurrencyRelation)
                ->desc()
                ->paginate(getPaginate(request()->item ?? 20));
            $pageTitle = [formateScope($scope) . " Exchange"];
            $data      = [
                'exchanges'  => $exchanges,
                'image_path' => $imagePath
            ];
            return jsonResponse('exchanges', $pageTitle, 'success', $data);
        } catch (Exception $ex) {
            $notify[] = ['error', 'Invalid URL.'];
            return back()->withNotify($notify);
        }
        return view($this->activeTemplate . 'user.exchange.list', compact('pageTitle', 'exchanges'));
    }

    public function details($id)
    {
        $imagePath = route('home') . '/' . getFilePath('currency');
        $exchange = Exchange::where('user_id', auth()->id())
            ->orderBy('id', 'DESC')
            ->where('id', $id)
            ->with($this->sendCurrencyRelation, $this->receivedCurrencyRelation)
            ->first();

        if (!$exchange) {
            $notify = ["Exchange not found"];
            return jsonResponse('not_found', $notify);
        }
        $pdfPath=route('download.exchange.pdf',['hash' => encrypt($exchange->user_id),'id' => $exchange->id]);

        $notify = ["Exchange Details"];
        $data   = [
            'exchange'   => $exchange,
            'image_path' => $imagePath,
            'pdfPath'    => $pdfPath
        ];

        return jsonResponse('exchange_details', $notify, 'success', $data);
    }

    public function all()
    {
        $imagePath = route('home') . '/' . getFilePath('currency');
        $notify    = ["Latest Exchange List"];

        $exchanges = Exchange::desc()->with($this->sendCurrencyRelation, $this->receivedCurrencyRelation, $this->userRelation)
            ->approved()->paginate(getPaginate(request()->item ?? 20));

        $data   = [
            'exchanges'  => $exchanges,
            'image_path' => $imagePath
        ];

        return jsonResponse('exchange_details', $notify, 'success', $data);
    }

    public function track(Request $request){

        $validator = Validator::make($request->all(), [
            'exchange_id'     => 'required|exists:exchanges,exchange_id'
        ]);

        if ($validator->fails()) {
            return jsonResponse('validation_error', $validator->errors()->all());
        }

         $exchange = Exchange::where('exchange_id', $request->exchange_id)
            ->with($this->sendCurrencyRelation, $this->receivedCurrencyRelation)
            ->first();


        $notify = ["Track Exchange"];
        $data   = [
            'exchange'   => $exchange
        ];

        return jsonResponse('track_exchange', $notify, 'success', $data);
    }
}
