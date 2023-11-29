<form class="exchange-form" method="POST" action="{{ route('user.exchange.start') }}" id="exchange-form">
    @csrf
    <div class="form-group w-100 sendData">
        <label>@lang('You Send')</label>
        <input type="number" step="any" name="sending_amount" id="sending_amount" class="form--control" placeholder="0.00" value="{{ old('sending_amount') }}"  required>
        <select class="select-bar form--control" name="sending_currency" id="send">
            <option value="" selected disabled>@lang('Select Currency')</option>
            @foreach ($sellCurrencies as $sellCurrency)
                <option data-image="{{ getImage(getFilePath('currency') . '/' . @$sellCurrency->image, getFileSize('currency')) }}" data-min="{{ getAmount($sellCurrency->minimum_limit_for_buy) }}" data-max="{{ getAmount($sellCurrency->maximum_limit_for_buy) }}" data-buy="{{ getAmount($sellCurrency->buy_at) }}" data-currency="{{ @$sellCurrency->cur_sym }}" value="{{ $sellCurrency->id }}" @if (old('sending_currency') == $sellCurrency->id)
                    selected
                @elseif(@$exchangeFormData['sending_currency'] == $sellCurrency->id)
                    selected
                @endif>
                    {{ __($sellCurrency->name) }} - {{ __($sellCurrency->cur_sym) }}
                </option>
            @endforeach
        </select>
        <span class="limit-alert d-none" id="currency-limit"></span>
    </div>

    <div class="form-group receiveData w-100">
        <label for="receive">@lang('You Get')</label>
        <input type="number" step="any" name="receiving_amount" class="form--control" id="receiving_amount" value="{{ old('receiving_amount') }}" placeholder="@lang('0.00')">
        <select class="select-bar form--control" name="receiving_currency" id="receive">
            <option value="" selected disabled>@lang('Select Currency')</option>
            @foreach ($buyCurrencies as $buyCurrency)
                <option data-image="{{ getImage(getFilePath('currency') . '/' . @$buyCurrency->image, getFileSize('currency')) }}" data-sell="{{ getAmount($buyCurrency->sell_at) }}" data-currency="{{ @$buyCurrency->cur_sym }}" value="{{ $buyCurrency->id }}" data-min="{{ getAmount($buyCurrency->minimum_limit_for_sell) }}" data-max="{{ getAmount($buyCurrency->maximum_limit_for_sell) }}" data-reserve="{{ getAmount($buyCurrency->reserve) }}"
                @if (old('receiving_currency') == $buyCurrency->id)
                    selected
                @elseif(@$exchangeFormData['receiving_currency'] == $buyCurrency->id)
                    selected
                @endif>
                    {{ __($buyCurrency->name) }} - {{ __($buyCurrency->cur_sym) }}
                </option>
            @endforeach
        </select>
        <span class="limit-alert d-none" id="currency-limit-received"></span>
    </div>
    <div class="form-group w-100">
        <button type="submit" class="btn--base btn">@lang('Exchange')</button>
    </div>
</form>


@push('script')
    <script>
        "use strict";
        (function($) {

            let sendId, sendMinAmount, sendMaxAmount, sendAmount, sendCurrency, sendCurrencyBuyRate;
            let receivedId, receivedAmount, receivedCurrency, receiveCurrencySellRate;

            const EXCHANGE_FORM = $('#exchange-form');

            @if (old('sending_currency') || @$exchangeFormData)
                sendAmount = "{{ old('sending_amount')}}";
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

            EXCHANGE_FORM.on('change', '#send', function(e) {
                sendId = parseInt($(this).val());
                sendMinAmount = parseFloat($(this).find(':selected').data('min'));
                sendMaxAmount = parseFloat($(this).find(':selected').data('max'));
                sendCurrency = $(this).find(':selected').data('currency');
                sendCurrencyBuyRate = parseFloat($(this).find(':selected').data('buy'));

                $("#currency-limit").removeClass('d-none').text(`Limit: ${sendMinAmount.toFixed(2)} - ${sendMaxAmount.toFixed(2)} ${sendCurrency}`);

                sameCurrencyCheck()
                calculationReceivedAmount();
            }).change();

            EXCHANGE_FORM.on('change', '#receive', function(e) {
                receivedId = parseInt($(this).val());
                receiveCurrencySellRate = parseFloat($(this).find(':selected').data('sell'));
                receivedCurrency = $(this).find(':selected').data('currency');

                let minAmount = parseFloat($(this).find(':selected').data('min'));
                let maxAmount = parseFloat($(this).find(':selected').data('max'));
                let reserveAmount = parseFloat($(this).find(':selected').data('reserve'))

                $("#currency-limit-received").removeClass('d-none').text(`Limit: ${minAmount.toFixed(2)} - ${maxAmount.toFixed(2)} ${receivedCurrency} | Reserve ${reserveAmount.toFixed(2)} ${receivedCurrency}`);

                sameCurrencyCheck();
                calculationReceivedAmount();
            });

            EXCHANGE_FORM.on('input', '#sending_amount', function(e) {
                sendAmount = parseFloat(this.value);
                sameCurrencyCheck();
                calculationReceivedAmount();
            });

            EXCHANGE_FORM.on('input', '#receiving_amount', function(e) {
                if (!sendId) {
                    this.value = this.value.replace('');
                    return false;
                }
                receivedAmount = parseFloat(this.value);
                sameCurrencyCheck();
                calculationSendAmount();
            });


            const sameCurrencyCheck = () => {
                if(sendId){
                    $('.receiveData').find(`.list li`).removeClass('d-none');
                    $('.receiveData').find(`.list li[data-value="${sendId}"]`).addClass('d-none');
                }
                if(receivedId){
                    $('.sendData').find(`.list li`).removeClass('d-none');
                    $('.sendData').find(`.list li[data-value="${receivedId}"]`).addClass('d-none');
                }
            }

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
        .limit-alert {
            background: #ff5200;
            padding: 5px 10px;
            border-radius: 0px 0px;
            display: block;
            width: 100%;
            color: #fff !important;
        }
    </style>
@endpush
