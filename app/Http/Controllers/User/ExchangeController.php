<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Models\Deposit;
use App\Models\Currency;
use App\Models\Exchange;
use App\Lib\FormProcessor;
use Illuminate\Http\Request;
use App\Models\GatewayCurrency;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;

class ExchangeController extends Controller
{

    public function exchange(Request $request)
    {
        // dd($request->all());
        $this->validation($request);

        $sendCurrency = Currency::enabled()->availableForSell()->find($request->sending_currency);
        $receiveCurrency = Currency::enabled()->availableForBuy()->find($request->receiving_currency);

        if (!$sendCurrency) {
            $notify[] = ['error', 'Sending currency not found'];
            return back()->withNotify($notify);
        }

        if (!$receiveCurrency) {
            $notify[] = ['error', 'Receiving currency not found'];
            return back()->withNotify($notify);
        }

        $sendAmount = $request->sending_amount;

        try {
            $sendingPercentCharge = $sendAmount / 100 * $sendCurrency->percent_charge_for_buy;
            $sendingFixedCharge = $sendCurrency->fixed_charge_for_buy;
            $totalSendingCharge = $sendingFixedCharge + $sendingPercentCharge;
            $receiveAmount = $sendCurrency->buy_at / $receiveCurrency->sell_at * $sendAmount;
            $receivingPercentCharge = $receiveAmount / 100 * $receiveCurrency->percent_charge_for_sell;
            $receivingFixedCharge = $receiveCurrency->fixed_charge_for_sell;
            $totalReceivingCharge = $receivingFixedCharge + $receivingPercentCharge;
            $totalReceivedAmount = $receiveAmount - $totalReceivingCharge;
        } catch (Exception $ex) {
            $notify[] = ['error', "Something went wrong with the exchange processing."];
            return back()->withNotify($notify)->withInput();
        }

        if ($sendAmount < $sendCurrency->minimum_limit_for_buy) {
            $notify[] = ['error', "Minimum sending amount " . showAmount($sendCurrency->minimum_limit_for_buy) . ' ' . $sendCurrency->cur_sym];
            return back()->withNotify($notify)->withInput();
        }

        if ($sendAmount > $sendCurrency->maximum_limit_for_buy) {
            $notify[] = ['error', "Maximum sending amount " . showAmount($sendCurrency->maximum_limit_for_buy) . ' ' . $sendCurrency->cur_sym];
            return back()->withNotify($notify)->withInput();
        }

        if ($receiveAmount < $receiveCurrency->minimum_limit_for_sell) {
            $notify[] = ['error', "Minimum received amount " . showAmount($receiveCurrency->minimum_limit_for_sell) . ' ' . $receiveCurrency->cur_sym];
            return back()->withNotify($notify)->withInput();
        }

        if ($receiveAmount > $receiveCurrency->maximum_limit_for_sell) {
            $notify[] = ['error', "Maximum received amount " . showAmount($receiveCurrency->maximum_limit_for_sell) . ' ' . $receiveCurrency->cur_sym];
            return back()->withNotify($notify)->withInput();
        }

        if ($totalReceivedAmount > $receiveCurrency->reserve) {
            $notify[] = ['error', "Sorry, our reserve limit exceeded"];
            return back()->withNotify($notify)->withInput();
        }

        $charge = [
            'sending_charge' => [
                'fixed_charge' => $sendingFixedCharge,
                'percent_charge' => $sendCurrency->percent_charge_for_buy,
                'percent_amount' => $sendingPercentCharge,
                'total_charge' => $totalSendingCharge
            ],
            'receiving_charge' => [
                'fixed_charge' => $receivingFixedCharge,
                'percent_charge' => $receiveCurrency->percent_charge_for_sell,
                'percent_amount' => $receivingPercentCharge,
                'total_charge' => $totalReceivingCharge
            ],
        ];

        $exchange = new Exchange();
        $exchange->user_id = auth()->id();
        $exchange->send_currency_id = $sendCurrency->id;
        $exchange->receive_currency_id = $receiveCurrency->id;
        $exchange->sending_amount = $sendAmount;
        $exchange->sending_charge = $totalSendingCharge;
        $exchange->receiving_amount = $receiveAmount;
        $exchange->receiving_charge = $totalReceivingCharge;
        $exchange->sell_rate = $receiveCurrency->sell_at;
        $exchange->buy_rate = $sendCurrency->buy_at;
        $exchange->exchange_id = getTrx();
        $exchange->charge = $charge;
        $exchange->save();

        session()->put('EXCHANGE_TRACK', $exchange->exchange_id);
        return to_route('user.exchange.preview');
    }

    public function preview()
    {
        // dd(session()->all());
        if (!session()->has('EXCHANGE_TRACK')) {
            $notify[] = ['error', "Invalid session"];
            return to_route('home')->withNotify($notify);
        }
        $pageTitle = 'Exchange Preview';
        $exchange = Exchange::where('exchange_id', session('EXCHANGE_TRACK'))->with('receivedCurrency.userDetailsData')->firstOrFail();
        return view($this->activeTemplate . 'user.exchange.preview', compact('pageTitle', 'exchange'));
    }

    public function confirm(Request $request)
    {

        if (!session()->has('EXCHANGE_TRACK')) {
            $notify[] = ['error', "Invalid session"];
            return to_route('home')->withNotify($notify);
        }

        $validation = [
            'wallet_id' => 'required'
        ];

        $exchange = Exchange::where('exchange_id', session()->get('EXCHANGE_TRACK'))->firstOrFail();
        $userRequiredData = @$exchange->receivedCurrency->userDetailsData->form_data ?? [];

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($userRequiredData);
        $validationRule = array_merge($validationRule, $validation);
        $request->validate($validationRule);

        $userData = $formProcessor->processFormData($request, $userRequiredData);

        $exchange->user_data = $userData ?? null;
        $exchange->wallet_id = $request->wallet_id;
        $exchange->save();

        //=====automatic payment

        if ($exchange->sendCurrency->gateway_id != 0) {
            $curSymbol = $exchange->sendCurrency->cur_sym;
            $code = $exchange->sendCurrency->gatewayCurrency->code;
            $gateway = GatewayCurrency::where('method_code', $code)->where('currency', $curSymbol)->first();

            if (!$gateway) {
                $notify[] = ['error', "Something went the wrong with exchange processing"];
                return back()->withNotify($notify);
            }

            $amount = $exchange->sending_amount + $exchange->sending_charge;

            $deposit = new Deposit();
            $deposit->user_id = auth()->id();
            $deposit->method_code = $code;
            $deposit->method_currency = strtoupper($curSymbol);
            $deposit->amount = $amount;
            $deposit->charge = 0;
            $deposit->rate = $exchange->buy_rate;
            $deposit->final_amo = getAmount($amount);
            $deposit->btc_amo = 0;
            $deposit->btc_wallet = "";
            $deposit->trx = $exchange->exchange_id;
            $deposit->try = 0;
            $deposit->status = 0;
            $deposit->exchange_id = $exchange->id;
            $deposit->save();

            session()->put('Track', $deposit->trx);
            return to_route('user.deposit.confirm');
        }

        return to_route('user.exchange.manual');
    }

    public function manual()
    {
        if (!session()->has('EXCHANGE_TRACK')) {
            $notify[] = ['error', "Something went the wrong with exchange processing"];
            return to_route('home')->withNotify($notify);
        }
        $exchange = Exchange::where('exchange_id', session()->get('EXCHANGE_TRACK'))->firstOrFail();
        $pageTitle = "Transaction Proof";
        return view($this->activeTemplate . 'user.exchange.manual', compact('pageTitle', 'exchange'));
    }

    public function manualConfirm(Request $request)
    {

        if (!session()->has('EXCHANGE_TRACK')) {
            $notify[] = ['error', "Something went the wrong with exchange processing"];
            return to_route('home')->withNotify($notify);
        }

        $exchange = Exchange::where('exchange_id', session()->get('EXCHANGE_TRACK'))->firstOrFail();
        $transactionProvedData = @$exchange->sendCurrency->transactionProvedData->form_data ?? [];

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($transactionProvedData);
        $request->validate($validationRule);
        $provedData = $formProcessor->processFormData($request, $transactionProvedData);

        $exchange->transaction_proof_data = $provedData ?? null;
        $exchange->status = Status::EXCHANGE_PENDING;
        $exchange->save();

        $comment = 'send ' . getAmount($exchange->get_amount) . ' by ' . @$exchange->sendCurrency->name;
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $exchange->user_id;
        $adminNotification->title = $comment;
        $adminNotification->click_url = urlPath('admin.exchange.details', $exchange->id);
        $adminNotification->save();

        session()->forget('EXCHANGE_TRACK');

        $notify[] = ['success', 'Admin will review your request'];
        return to_route('user.exchange.details', $exchange->exchange_id)->withNotify($notify);
    }

    public function list($scope = 'list')
    {
        try {
            $exchanges = Exchange::$scope()->where('user_id', auth()->id())->with('sendCurrency', 'receivedCurrency')->desc()->paginate(getPaginate());
            $pageTitle = formateScope($scope) . " Exchange";
        } catch (Exception $ex) {
            $notify[] = ['error', 'Invalid URL.'];
            return back()->withNotify($notify);
        }
        return view($this->activeTemplate . 'user.exchange.list', compact('pageTitle', 'exchanges'));
    }

    public function details($trx)
    {
        $exchange = Exchange::where('status', '!=', Status::EXCHANGE_INITIAL)->where('user_id', auth()->id())->orderBy('id', 'DESC')->where('exchange_id', $trx)->firstOrFail();
        $pageTitle = 'Exchange Details';
        return view($this->activeTemplate . 'user.exchange.details', compact('pageTitle', 'exchange'));
    }

    protected function validation($request)
    {
        $request->validate([
            'sending_amount' => 'required|numeric|gt:0',
            'receiving_amount' => 'required|numeric|gt:0',
            'sending_currency' => 'required|integer',
            'receiving_currency' => 'required|integer|different:sending_currency',
        ]);
    }

    public function invoice($exchangeId, $type)
    {

        $types = ['print', 'download'];

        if (!in_array($type, $types)) {

            $notify[] = ['error', "Invalid URL."];
            return to_route('user.exchange.list', 'list')->withNotify($notify);
        }

        if ($type == 'print') {
            $pageTitle = "Print Exchange";
            $action = 'stream';
        } else {
            $pageTitle = "Download Exchange";
            $action = 'download';
        }

        $exchange = Exchange::where('status', '!=', Status::EXCHANGE_INITIAL)->where('exchange_id', $exchangeId)->where('user_id', auth()->id())->firstOrFail();
        $user = auth()->user();
        $pdf = PDF::loadView('partials.pdf', compact('pageTitle', 'user', 'exchange'));
        $fileName = $exchange->exchange_id . '_' . time();
        return $pdf->$action($fileName . '.pdf');
    }
}
