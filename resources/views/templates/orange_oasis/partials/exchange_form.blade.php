<div class="custom-widget mb-4">
    <form action="{{ route('user.exchange.start') }}" method="POST" id="exchange-form">
        @csrf
        <div class="row g-4">
            <div class="col-md-6">
                <div>
                    <h6 class="banner__widget-title mb-3 mt-0">@lang('You Send')</h6>
                    <div class="form-group mb-3">
                        <div class="select-item">
                            <select required class="select2 form-control form--control currency-picker" data-type="select"
                                name="sending_currency" id="send">
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($sellCurrencies as $sellCurrency)
                                    <option
                                        data-image="{{ getImage(getFilePath('currency') . '/' . @$sellCurrency->image, getFileSize('currency')) }}"
                                        data-min="{{ getAmount($sellCurrency->minimum_limit_for_buy) }}"
                                        data-max="{{ getAmount($sellCurrency->maximum_limit_for_buy) }}"
                                        data-buy="{{ getAmount($sellCurrency->buy_at) }}"
                                        data-currency="{{ @$sellCurrency->cur_sym }}"
                                        value="{{ $sellCurrency->id }}"
                                        data-select-for="send"
                                        @selected(old('sending_currency') == $sellCurrency->id || @$exchangeFormData['sending_currency'] == $sellCurrency->id)>
                                        {{ __($sellCurrency->name) }} - {{ __($sellCurrency->cur_sym) }} - {{ __($sellCurrency->id) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label fw-medium">@lang('Send Amount')</label>
                        <div class="input-group">
                            <input type="number" step="any" class="form-control form--control rounded"
                                name="sending_amount" id="sending_amount" value="{{old('sending_amount') }}"  placeholder="0.00" required>
                            <span class="input-group-text d-none bg--base text-white border-0"></span>
                        </div>
                    </div>
                    <div class="rate--txt d-none">
                        <div>
                            <span>@lang('Limit:')</span>
                            <span class="limit-exchange">
                                <span class="text--base"></span>
                                <span class="currency_name"></span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div>
                    <h6 class="mb-3 mt-0">@lang('You Get')</h6>
                    <div class="form-group mb-3" id="receiving-currency-wrapper">
                        <div class="select-item ">
                            <select class="select2 form-control form--control currency-picker" name="receiving_currency"
                                id="receive" required value.bind="selectedThing2">
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($buyCurrencies as $buyCurrency)
                                    <option
                                        data-image="{{ getImage(getFilePath('currency') . '/' . @$buyCurrency->image, getFileSize('currency')) }}"
                                        data-sell="{{ getAmount($buyCurrency->sell_at) }}"
                                        data-currency="{{ @$buyCurrency->cur_sym }}"
                                        data-min="{{ getAmount($buyCurrency->minimum_limit_for_sell) }}"
                                        data-max="{{ getAmount($buyCurrency->maximum_limit_for_sell) }}"
                                        data-reserve="{{ getAmount($buyCurrency->reserve) }}"
                                        value="{{ $buyCurrency->id }}"
                                        data-select-for="received"
                                        @selected(old('receiving_currency') == $buyCurrency->id || @$exchangeFormData['receiving_currency'] == $buyCurrency->id)
                                       >
                                        {{ __($buyCurrency->name) }} - {{ __($buyCurrency->cur_sym) }} - {{ __($buyCurrency->id) }}
                                    </option>

                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label fw-medium">@lang('Get Amount')</label>
                        <div class="input-group">
                            <input type="number" step="any" class="form-control form--control rounded"  id="receiving_amount" name="receiving_amount"
                                value="{{ old('receiving_amount') }}"  placeholder="0.00" required>
                            <span class="input-group-text d-none bg--base text-white border-0"></span>
                        </div>
                    </div>
                    <div class="rate--txt-received d-none">
                        <div>
                            <span>@lang('Limit:')</span>
                            <span class="limit-received-exchange">
                                <span class="text--base"></span>
                                <span class="currency_name"></span>
                            </span>
                            <span>@lang('| Reserve:')</span>
                            <span class="reserve-amount">
                                <span class="text--base"></span>
                                <span class="currency_name"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button class="btn btn--base " type="submit">
                    <span class="me-2"> <i class="las la-exchange-alt"></i></span>@lang('Exchange Now')
                </button>
            </div>
        </div>
    </form>
</div>

@push('style-lib')
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            let sendId, sendMinAmount, sendMaxAmount, sendAmount, sendCurrency, sendCurrencyBuyRate;
            let receivedId, receivedAmount, receivedCurrency, receiveCurrencySellRate;

            //=============change select2 structure
            $('.currency-picker').select2({
                templateResult: formatState
            });

            function formatState(state) {
                if (!state.id) return state.text;
                let selectType=$(state.element).data('select-for').toUpperCase();

                if(sendId && selectType=='RECEIVED' && sendId==state.element.value ){
                    return false;
                }

                if(receivedId && selectType=='SEND' && receivedId==state.element.value ){
                    return false;
                }
                return $('<img class="ms-1"   src="' + $(state.element).data('image') + '"/> <span class="ms-3">' +
                    state.text + '</span>');
            }

            @if (old('sending_currency') || @$exchangeFormData)
                sendAmount = "{{ old('sending_amount') }}";
                @if(@$exchangeFormData['sending_amount'])
                    sendAmount = "{{ @$exchangeFormData['sending_amount'] }}";
                @endif
                sendAmount = parseFloat(sendAmount);
                $("#sending_amount").val(sendAmount.toFixed("{{$general->show_number_after_decimal}}"));
                setTimeout(() => {
                    $('#send').trigger('change');
                });
            @endif

            @if (old('receiving_currency') || @$exchangeFormData)
                receivedAmount = "{{ old('receiving_amount')}}";
                @if(@$exchangeFormData['receiving_amount'])
                    receivedAmount = "{{ @$exchangeFormData['receiving_amount'] }}";
                @endif
                receivedAmount=parseFloat(receivedAmount);
                $("#receiving_amount").val(receivedAmount.toFixed("{{$general->show_number_after_decimal}}"));
                setTimeout(() => {
                    $('#receive').trigger('change');
                });
            @endif

            $('#exchange-form').on('change', '#send', function(e) {

                sendId = parseInt($(this).val());
                sendMinAmount = parseFloat($(this).find(':selected').data('min'));
                sendMaxAmount = parseFloat($(this).find(':selected').data('max'));
                sendCurrency = $(this).find(':selected').data('currency');
                sendCurrencyBuyRate = parseFloat($(this).find(':selected').data('buy'));

                $('.limit-exchange').find('.text--base').text(
                    `${sendMinAmount.toFixed(2)}- ${sendMaxAmount.toFixed(2)}`);
                $('.limit-exchange').find('.currency_name').text(sendCurrency);
                $('.rate--txt').removeClass('d-none');

                $("#sending_amount").siblings('.input-group-text').removeClass('d-none');
                $("#sending_amount").removeClass('rounded');
                $("#sending_amount").siblings('.input-group-text').text(sendCurrency);

                $(this).closest('.form-group').find('.select2-selection__rendered').html(
                    `
               <img src="${$(this).find(':selected').data('image')}" class="currency-image"/> ${$(this).find(':selected').text()}`
                )

                calculationReceivedAmount();
            });

            $('#exchange-form').on('change', '#receive', function(e) {

                receivedId = parseInt($(this).val());
                receiveCurrencySellRate = parseFloat($(this).find(':selected').data('sell'));
                receivedCurrency = $(this).find(':selected').data('currency');

                let minAmount = parseFloat($(this).find(':selected').data('min'));
                let maxAmount = parseFloat($(this).find(':selected').data('max'));
                let reserveAmount = parseFloat($(this).find(':selected').data('reserve'))

                $('.limit-received-exchange').find('.text--base').text(
                    `${minAmount.toFixed(2)}- ${maxAmount.toFixed(2)}`);
                $('.reserve-amount').find('.text--base').text(`${reserveAmount.toFixed(2)}`);
                $('.limit-received-exchange').find('.currency_name').text(receivedCurrency);
                $('.reserve-amount').find('.currency_name').text(receivedCurrency);
                $('.rate--txt-received').removeClass('d-none');



                $("#receiving_amount").siblings('.input-group-text').removeClass('d-none');
                $("#receiving_amount").removeClass('rounded');
                $("#receiving_amount").siblings('.input-group-text').text(receivedCurrency);

                $(this).closest('.form-group').find('.select2-selection__rendered').html(
                    `<img src="${$(this).find(':selected').data('image')}" class="currency-image"/> ${$(this).find(':selected').text()}`
                    )
                calculationReceivedAmount();
            });

            $('#exchange-form').on('input', '#sending_amount', function(e) {
                sendAmount = parseFloat(this.value);
                calculationReceivedAmount();
            });

            $('#exchange-form').on('input', '#receiving_amount', function(e) {
                receivedAmount = parseFloat(this.value);
                calculationSendAmount();
            });

            const calculationReceivedAmount = () => {

                if (!sendId && !receivedId && !sendCurrencyBuyRate && !receiveCurrencySellRate) {
                    return false;
                }
                let amountReceived = (sendCurrencyBuyRate / receiveCurrencySellRate) * sendAmount;
                $("#receiving_amount").val(amountReceived.toFixed(2))
            }

            const calculationSendAmount = () => {
                if (!sendId && !receivedId && !sendCurrencyBuyRate && !receiveCurrencySellRate) {
                    return false;
                }
                let amountReceived = (receiveCurrencySellRate / sendCurrencyBuyRate) * receivedAmount;
                $("#sending_amount").val(amountReceived.toFixed(2))
            }

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .select2-container .select2-selection--single {
            height: 46px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
        }

        .select2-container--default img {
            width: 28px;
            height: 28px;
            object-fit: contain;
        }

        .select2-results__option--selectable {
            display: flex;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            top: 80%;
        }

        img.currency-image {
            width: 25px;
            height: 25px;
            margin-right: 8px;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid hsl(var(--border));
        }
        .select2-results__option:empty {
            display: none !important;
        }
    </style>
@endpush
