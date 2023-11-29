@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card custom--card">
                    <div class="text-center card-header">
                        <h5>@lang('Withdraw Balance')</h5>
                        <span>@lang('Your Current Balance Is: ') {{ showAmount(auth()->user()->balance) }} {{ __($general->cur_text) }}</span>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" id="form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">@lang('Select Method for Withdraw')</label>
                                <select name="currency" id="currency" class="form--control form-control" required>
                                    <option value="" selected disabled>@lang('Select Withdraw Method')</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" data-sell-at="{{ $currency->sell_at }}" data-fixed-charge="{{ $currency->fixed_charge_for_sell }}" data-percent-charge="{{ $currency->percent_charge_for_sell }}">
                                            {{ __($currency->name) }}-{{ __($currency->cur_sym) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12 col-lg-6 form-group">
                                    <label class="form-label">@lang('Send Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="send_amount" class="form--control form-control" id="send_amount" required>
                                        <span class="input-group-text bg--base text-white border-0">
                                            {{ __($general->cur_text) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 form-group">
                                    <label class="form-label">@lang('Get Amount')</label>
                                    <div class="input-group">
                                        <input type="text" name="get_amount" class="form--control form-control" id="get_amount" required readonly>
                                        <span class="input-group-text bg--base text-white border-0" id="basic-addon2"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="user_input form-group"> </div>
                            <div class="d-none min_max"></div>
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            let minAmount = 0;
            let maxAmount = 0;
            let getAmount = 0;
            let charge = 0;
            let hasAmount = false;

            $('#currency').on('change', function() {
                let url = `{{ route('user.withdraw.currency.user.data', ':id') }}`;
                url = url.replace(':id', $(this).val());
                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(response) {
                        if (response.success) {
                            minAmount = response.min_amount;
                            maxAmount = response.max_amount;
                            calculateAmount();
                            $('.min_max').removeClass('d-none')
                            $('#basic-addon2').text(response.cur_sym);
                            $('.user_input').html(response.html);
                        } else {
                            notify('error', response.message || "@lang('Something went the wrong')")
                        }
                    }
                })
            });

            $('#send_amount').on('input', function() {
                calculateAmount();
            });

			function calculateAmount() {
                let amount = parseFloat($("#send_amount").val() || 0);
                let currencyId = $("#currency").val();
                if (!currencyId) {
                    notify('error', "Please select currency first");
                    return false;
                }
                let currencySelected = $("#currency").find('option:selected');
                let sellAt = parseFloat($(currencySelected).data('sell-at')) ;
                let fixedCharge = parseFloat($(currencySelected).data('fixed-charge')) || 0;
                let percentCharge = parseFloat($(currencySelected).data('percent-charge')) || 0;

                if (!sellAt || !amount) {
                    return false;
                }
                getAmount = amount / sellAt;
                charge = fixedCharge + ((getAmount * percentCharge) / 100);
                $('#get_amount').val(getAmount.toFixed(2));
                hasAmount = true;
                previewHtml();
            }

            function previewHtml() {
                let html = `
                    <li class="list-group-item d-flex justify-content-between  flex-wrap border-dotted">
                        <span>Minimum Limit</span>
                        <span>${parseFloat(minAmount).toFixed(2)} {{ $general->cur_text }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between  flex-wrap border-dotted">
                        <span>Maximum Limit</span>
                        <span>${parseFloat(maxAmount).toFixed(2)} {{ $general->cur_text }}</span>
                    </li>
                `;

                if (hasAmount) {
                    html += `
                    <li class="list-group-item d-flex justify-content-between  flex-wrap border-dotted">
                        <span>@lang('Received Amount')</span>
                        <span>${parseFloat(getAmount).toFixed(2)} {{ $general->cur_text }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between  flex-wrap border-dotted">
                        <span>@lang('Charge')</span>
                        <span class="text--danger">${parseFloat(charge).toFixed(2)} {{ $general->cur_text }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between  flex-wrap border-dotted">
                        <span>@lang('Amount Received After Charge')</span>
                        <span>${parseFloat(getAmount-charge).toFixed(2)} {{ $general->cur_text }}</span>
                    </li>
                    `
                }
                $('.min_max').html(`<ul class="list-group list-group-flush">${html}</ul>`);
                $('.min_max').removeClass(`d-none`).addClass('mb-3');

            }
        })(jQuery);
    </script>
@endpush
