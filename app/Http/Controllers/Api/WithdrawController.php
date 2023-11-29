<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    public function withdrawMethod()
    {
        $currencies = Currency::enabled()->where('available_for_buy', Status::YES)
            ->with('userDetailsData')
            ->get();

        $notify[] = 'Withdrawals Currency';
        $data = [
            'currencies' => $currencies
        ];
        return jsonResponse('withdraw_currency', $notify, 'success', $data);
    }

    public function withdrawStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency'    => 'required',
            'send_amount' => 'required|numeric|gte:0',
        ]);

        if ($validator->fails()) {
            return jsonResponse('validation_error', $validator->errors()->all());
        }

        $user = auth()->user();

        if ($request->send_amount > $user->balance) {
            $notify = ['You have not enough balance'];
            return jsonResponse('not_enough_balance', $notify);
        }

        $currency = Currency::enabled()->where('available_for_buy', Status::YES)->where('id', $request->currency)->first();

        if (!$currency) {
            $notify = ['Withdraw currency not found'];
            return jsonResponse('not_found', $notify);
        }

        $formData       = @$currency->userDetailsData->form_data ?? null;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $formValidator  = Validator::make($request->all(), $validationRule);

        if ($formValidator->fails()) {
            return jsonResponse('validation_error', $formValidator->errors()->all());
        }

        $formValue = $formProcessor->processFormData($request, $formData);

        if ($request->send_amount < ($currency->minimum_limit_for_sell * $currency->sell_at)) {
            $notify = ['Please follow the minimum limit'];
            return jsonResponse('not_found', $notify);
        }
        if ($request->send_amount > ($currency->maximum_limit_for_sell * $currency->sell_at)) {
            $notify[] = ['Please follow the maximum limit'];
            return jsonResponse('not_found', $notify);
        }


        $getAmount = $request->send_amount / $currency->sell_at;
        $charge    = $currency->fixed_charge_for_sell + ($getAmount * $currency->percent_charge_for_sell / 100);
        $general   = gs();

        $withdraw                       = new Withdrawal();
        $withdraw->method_id            = $currency->id;
        $withdraw->user_id              = $user->id;
        $withdraw->amount               = $request->send_amount;
        $withdraw->currency             = $general->cur_text;
        $withdraw->rate                 = $currency->sell_at;
        $withdraw->charge               = $charge;
        $withdraw->final_amount         = $getAmount;
        $withdraw->after_charge         = $getAmount - $charge;
        $withdraw->trx                  = getTrx();
        $withdraw->status               = 2;
        $withdraw->withdraw_information = $formValue;
        $withdraw->save();

        $user->balance -= $withdraw->amount;
        $user->save();


        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdraw request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.details', $withdraw->id);
        $adminNotification->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount),
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance),
        ]);

        $notify = ['Withdraw created successfully'];
        $data   = [
            'withdraw' => $withdraw
        ];
        return jsonResponse('withdraw_request_created', $notify, 'success', $data);
    }



    public function withdrawLog(Request $request)
    {
        $withdraws = Withdrawal::where('user_id', auth()->id());
        if ($request->search) {
            $withdraws = $withdraws->where('trx', $request->search);
        }
        $withdraws = $withdraws->where('status', '!=', Status::PAYMENT_INITIATE)->with('method')->orderBy('id', 'desc')->paginate(getPaginate());
        $notify[] = 'Withdrawals';
        return response()->json([
            'remark' => 'withdrawals',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'withdrawals' => $withdraws
            ]
        ]);
    }
}
