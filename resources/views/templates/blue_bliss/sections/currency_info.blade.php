@php
    $content = getContent('currency_info.content', true);
    $currencies = App\Models\Currency::enabled()
        ->availableForSell()
        ->availableForBuy()
        ->desc()
        ->get();

    $reserveCurrencies = App\Models\Currency::enabled()
        ->availableForSell()
        ->availableForBuy()
        ->where('show_rate', Status::YES)
        ->where('reserve', '>', 0)
        ->asc('name')
        ->get();
@endphp
<section class="reserve-section padding-top padding-bottom">
    <div class="container">
        <div class="section-header">
            <h3 class="title">{{ __(@$content->data_values->heading) }}</h3>
            <p>{{ __(@$content->data_values->subheading) }}</p>
        </div>
        <div class="row gy-5">
            <div class="col-xl-6 col-lg-7">
                <div class="card custom--card p-3">
                    <h5 class="card-title mb-2">@lang('Exchange Rates Now')</h5>
                    <div class="custom--card__inner">
                        <div class="card-body p-0">
                            <table class="table custom--table shadow-none">
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
                                                    <span class="currency-name"> {{ __($currency->name) }} - {{ __($currency->cur_sym) }} </span>
                                                </div>
                                            </td>
                                            <td>{{ showAmount($currency->sell_at) }} {{ __($general->cur_text) }}</td>
                                            <td>{{ showAmount($currency->buy_at) }} {{ __($general->cur_text) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-5">
                <div class="card custom--card p-3">
                    <h5 class="card-title mb-2">@lang('Our Reserves')</h5>
                    <div class="custom--card__inner">
                        <div class="card-body p-0">
                            <table class="table custom--table shadow-none">
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
                                                        <img src="{{ getImage(getFilePath('currency') . '/' . $currency->image, getFileSize('currency')) }}">
                                                    </span>
                                                    <span class="currency-name"> {{ __($currency->name) }} - {{ __($currency->cur_sym) }} </span>
                                                </div>
                                            </td>
                                            <td>{{ showAmount($currency->reserve) }} {{ __($currency->cur_sym) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
