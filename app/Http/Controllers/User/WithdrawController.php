<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Models\Currency;
use App\Lib\FormProcessor;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;


class WithdrawController extends Controller
{

    public function withdrawMoney()
    {
        $pageTitle  = 'Withdraw Money';
        $currencies = Currency::enabled()->where('available_for_buy', Status::YES)->get();
        return view($this->activeTemplate . 'user.withdraw.methods', compact('pageTitle', 'currencies'));
    }

    public function withdrawStore(Request $request)
    {
        $request->validate([
            'currency'    => 'required',
            'send_amount' => 'required|numeric|gte:0',
        ]);

        $user = auth()->user();

        if ($request->send_amount > $user->balance) {
            $notify[] = ['error', 'You have not enough balance'];
            return back()->withNotify($notify);
        }

        $currency = Currency::enabled()->availableForSell()->where('id', $request->currency)->firstOrFail();
        $formData = @$currency->userDetailsData->form_data ?? null;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $formValue = $formProcessor->processFormData($request, $formData);


        if ($request->send_amount < ($currency->minimum_limit_for_sell * $currency->sell_at)) {
            $notify[] = ['error', 'Please follow the minimum limit'];
            return back()->withNotify($notify);
        }

        if ($request->send_amount > ($currency->maximum_limit_for_sell * $currency->sell_at)) {
            $notify[] = ['error', 'Please follow the maximum limit'];
            return back()->withNotify($notify);
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

        $notify[] = ['success', 'Please wait for admin approval'];
        return to_route('user.withdraw.details', $withdraw->trx)->withNotify($notify);
    }

    public function withdrawDetails($trx)
    {
        $withdraw  = Withdrawal::with('method', 'user')->where('trx', $trx)->where('user_id', auth()->id())->orderBy('id', 'desc')->firstOrFail();
        $pageTitle = 'Withdraw Details';
        return view($this->activeTemplate . 'user.withdraw.details', compact('pageTitle', 'withdraw'));
    }

    public function withdrawLog(Request $request)
    {
        $pageTitle = "Withdraw Log";
        $withdraws = Withdrawal::where('user_id', auth()->id())->where('status', '!=', Status::PAYMENT_INITIATE);

        if ($request->search) {
            $withdraws = $withdraws->where('trx', $request->search);
        }

        $withdraws = $withdraws->with('method')->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.withdraw.log', compact('pageTitle', 'withdraws'));
    }

    public function currencyUserData($id)
    {
        $currency = Currency::enabled()->where('available_for_buy', Status::YES)->where('id', $id)->first();

        if (!$currency) {
            return response()->json([
                'success' => false,
                'message' => "Currency not found"
            ]);
        }

        $formData  = @$currency->userDetailsData->form_data ?? null;
        $html      = $formData ? view('components.viser-form', compact('formData'))->render() : '';
        $minAmount = $currency->minimum_limit_for_sell * $currency->sell_at;
        $maxAmount = $currency->maximum_limit_for_sell * $currency->sell_at;

        return response()->json([
            'success'    => true,
            'min_amount' => showAmount($minAmount),
            'max_amount' => showAmount($maxAmount),
            'html'       => $html,
            'cur_sym'    => $currency->cur_sym,
        ]);
    }
}
