@php
    $currencies = App\Models\Currency::enabled()
        ->availableForSell()
        ->availableForBuy()
        ->desc()
        ->get();
@endphp
<div class="custom-widget widget-scroll mb-4">
    <h6 class="custom-widget-title mb-3">@lang('Exchange Rates Now')</h6>
    <div class="custom-widget__inner">
        <table class="table fs--13px">
            <thead>
                <tr>
                    <th>@lang('Currency')</th>
                    <th>@lang('Buy At')</th>
                    <th>@lang('Sell At')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($currencies as $currency)
                    <tr>
                        <td>
                            <div>
                                <span class="thumb ms-0">
                                    <img src="{{ getImage(getFilePath('currency') . '/' . $currency->image, getFileSize('currency')) }}">
                                </span>
                                <span> {{ __($currency->name) }} - {{ __($currency->cur_sym) }} </span>
                            </div>
                        </td>
                        <td>{{ showAmount($currency->sell_at) }} <span>{{ __($general->cur_text) }}</span></td>
                        <td>{{ showAmount($currency->buy_at) }} <span>{{ __($general->cur_text) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
