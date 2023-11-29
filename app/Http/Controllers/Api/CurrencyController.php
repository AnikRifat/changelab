<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function list()
    {
        $notify[] = 'Currency List';

        $currencies = $this->currencyList();
        $imagePath  = route('home').'/'.getFilePath('currency');

        return response()->json([
            'remark'  => 'currency',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'currencies' => $currencies,
                'image_path' => $imagePath,
            ]
        ]);
    }
    public function sell()
    {
        $notify[] = 'Selling Currency List';

        $currencies = $this->currencyList("availableForSell");
        $imagePath  = getFilePath('currency');

        return response()->json([
            'remark'  => 'selling_currency',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'currencies' => $currencies,
                'image_path' => $imagePath,
            ]
        ]);
    }
    public function buy()
    {
        $notify[] = 'Buying Currency List';

        $currencies = $this->currencyList("availableForBuy");
        $imagePath  = getFilePath('currency');

        return response()->json([
            'remark'  => 'buying_currency',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'currencies' => $currencies,
                'image_path' => $imagePath
            ]
        ]);
    }

    private function currencyList($scope = null)
    {
        $currencies = Currency::active()
            ->orderBy('name');
        if ($scope) return $currencies->$scope()->get();
        return $currencies->get();
    }
}
