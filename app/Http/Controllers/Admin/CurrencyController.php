<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gateway;
use App\Models\Currency;
use App\Constants\Status;
use App\Lib\FormProcessor;
use Illuminate\Http\Request;
use App\Models\GatewayCurrency;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::searchAble(['name', 'cur_sym'])->latest()->paginate(getPaginate());
        $pageTitle  = "Manage Currency";
        return view('admin.currency.index', compact('pageTitle', 'currencies'));
    }

    public function create()
    {
        $gateways  = Gateway::automatic()->active()->latest()->get();
        $pageTitle = "Create Currency";
        return view('admin.currency.create', compact('pageTitle', 'gateways'));
    }
    public function edit($id)
    {
        $gateways  = Gateway::automatic()->active()->latest()->get();
        $pageTitle = "Edit Currency";
        $currency  = Currency::where('id', $id)->with('userDetailsData', 'transactionProvedData')->firstOrFail();
        return view('admin.currency.create', compact('pageTitle', 'gateways', 'currency'));
    }

    public function save(Request $request, $id = 0)
    {
        $this->validation($request, $id);

        $userDetails           = new FormProcessor();
        $userDetailsValidation = @$userDetails->generatorValidation();

        $transactionProved            = new FormProcessor();
        $transactionProved->inputName = 'transaction_proof';
        $transactionProvedValidation  = @$transactionProved->generatorValidation();

        $rules        = array_merge(@$userDetailsValidation['rules'] ?? [], @$transactionProvedValidation['rules'] ?? []);
        $rulesMessage = array_merge(@$userDetailsValidation['message'] ?? [], @$transactionProvedValidation['message'] ?? []);

        $request->validate($rules, $rulesMessage);

        $currencySymbol = strtoupper($request->currency);

        if ($request->payment_gateway > 0) {
            $gateway         = Gateway::automatic()->active()->where('id', $request->payment_gateway)->first();
            $gatewayCurrency = GatewayCurrency::where('method_code', $gateway->code)->where('currency', $currencySymbol)->first();

            if (!$gatewayCurrency) {
                $notify[] = ['info', "Please add $currencySymbol support currency under the  $gateway->name  payment method"];
                $notify[] = ['error', "Gateway currency & Currency symbol must be same."];
                return back()->withNotify($notify)->withInput();
            }
        }

        if ($id) {
            $currency                      = Currency::findOrFail($id);
            $userDetailsGenerateData       = $request->form_generator ? $userDetails->generate('currency', true, 'id', $currency->user_detail_form_id) : null;
            $transactionProvedGenerateData = $request->transaction_proof ? $transactionProved->generate('currency', true, 'id', $currency->trx_proof_form_id) : null;
            $message                       = "Currency updated successfully";
        } else {
            $currency                      = new Currency();
            $userDetailsGenerateData       = $request->form_generator ? $userDetails->generate('currency') : null;
            $transactionProvedGenerateData = $request->transaction_proof ? $transactionProved->generate('currency') : null;
            $message                       = "Currency added successfully";
        }

        $currency->name                    = $request->name;
        $currency->cur_sym                 = $currencySymbol;
        $currency->sell_at                 = $request->sell_at;
        $currency->buy_at                  = $request->buy_at;
        $currency->minimum_limit_for_sell  = $request->minimum_limit_for_sell;
        $currency->maximum_limit_for_sell  = $request->maximum_limit_for_sell;
        $currency->minimum_limit_for_buy   = $request->minimum_limit_for_buy;
        $currency->maximum_limit_for_buy   = $request->maximum_limit_for_buy;
        $currency->fixed_charge_for_sell   = $request->fixed_charge_for_sell;
        $currency->percent_charge_for_sell = $request->percent_charge_for_sell;
        $currency->fixed_charge_for_buy    = $request->fixed_charge_for_buy;
        $currency->percent_charge_for_buy  = $request->percent_charge_for_buy;
        $currency->reserve                 = $request->reserve;
        $currency->instruction             = $request->instruction;
        $currency->available_for_sell      = $request->available_for_sell ? Status::YES : Status::NO;
        $currency->available_for_buy       = $request->available_for_buy ? Status::YES : Status::NO;
        $currency->show_rate               = $request->rate_show ? Status::YES : Status::NO;
        $currency->gateway_id              = $request->payment_gateway;
        $currency->user_detail_form_id     = @$userDetailsGenerateData->id ?? 0;
        $currency->trx_proof_form_id       = @$transactionProvedGenerateData->id ?? 0;

        if ($request->hasFile('image')) {
            try {
                $path            = getFilePath('currency');
                $size            = getFileSize('currency');
                $currency->image = fileUploader($request->image, $path, $size, $currency->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $currency->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Currency::changeStatus($id);
    }

    protected function validation($request, $id)
    {
        $imageValidation = $id ? 'sometimes' : 'required';

        $rules           = [
            'name'                    => 'required|max:255',
            'currency'                => 'required|max:255',
            'payment_gateway'         => 'required|integer|min:0',
            'buy_at'                  => 'required|numeric|gte:0',
            'sell_at'                 => 'required|numeric|gte:0',
            'reserve'                 => 'required|numeric|gte:0',
            'instruction'             => 'required',
            'minimum_limit_for_sell'  => 'required',
            'minimum_limit_for_sell'  => "required|numeric|gt:0",
            'maximum_limit_for_sell'  => "required|numeric|gt:minimum_limit_for_sell",
            'fixed_charge_for_sell'   => "required|numeric|gte:0",
            'percent_charge_for_sell' => "required|numeric|gte:0",
            'minimum_limit_for_buy'   => 'required|numeric|gt:0',
            'maximum_limit_for_buy'   => 'required|numeric|gt:minimum_limit_for_buy',
            'fixed_charge_for_buy'    => "required|numeric|gte:0",
            'percent_charge_for_buy'  => "required|numeric|gte:0",
            'image'                   => [$imageValidation, new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ];
        $request->validate($rules);
    }
}
