@php
    $reserveCurrencies = App\Models\Currency::enabled()
        ->availableForSell()
        ->availableForBuy()
        ->where('show_rate', Status::YES)
        ->where('reserve', '>', 0)
        ->asc('name')
        ->get();
@endphp

<div class="custom-widget widget-scroll">
    <h6 class="custom-widget-title mb-3">@lang('Our Reserves')</h6>
    <div class="custom-widget__inner">
        <table class="table fs--13px">
            <thead>
                <tr>
                    <th>@lang('Currency')</th>
                    <th>@lang('Reserved')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reserveCurrencies as $currency)
                    <tr>
                        <td>
                            <div>
                                <span class="thumb ms-0">
                                    <img src="{{ getImage(getFilePath('currency') . '/' . @$currency->image, getFileSize('currency')) }}">
                                </span>
                                <span> {{ __($currency->name) }} - {{ __($currency->cur_sym) }} </span>
                            </div>
                        </td>
                        <td>{{ showAmount($currency->reserve) }} {{ __($currency->cur_sym) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
