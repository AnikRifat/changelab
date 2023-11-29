@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.currency.save', @$currency->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-3">
                                <div class="thumb mb-2">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url('{{ getImage(getFilePath('currency') . '/' . @$currency->image, getFileSize('currency')) }}')">
                                        </div>
                                    </div>
                                    <div class="avatar-edit">
                                        <input type="file" name="image" class="profilePicUpload" id="image" accept=".png, .jpg, .jpeg" />
                                        <label for="image" class="bg--primary"><i class="la la-pencil"></i></label>
                                    </div>
                                </div>
                                <small class="mt-3 text-muted text--small">@lang('Supported files'): <b>@lang('png'), @lang('jpeg'),@lang('jpg').</b>
                                    @lang('Image will be resized into '){{ __(getFileSize('currency')) }} @lang('px')
                                </small>
                            </div>

                            <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8 col-xxl-9">
                                <div class="row">
                                    <div class="col-xxl-4 col-sm-12">
                                        <div class="form-group">
                                            <label>@lang('Currency Name')</label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', @$currency->name) }}" required autocomplete="off" />
                                        </div>
                                    </div>

                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Currency')</label>
                                            <input type="text" name="currency" class="form-control currency" required value="{{ old('currency', @$currency->cur_sym) }}" />
                                        </div>
                                    </div>

                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Payment Gateway')</label> <i class="la la-info-circle" title="@lang('User will send the money by this payment gateway.')"></i>
                                            <select name="payment_gateway" class="form-control" required>
                                                <option value="0">@lang('Manual')</option>
                                                @foreach ($gateways as $gateway)
                                                    <option value="{{ $gateway->id }}" @selected(@$currency && $currency->gateway_id == $gateway->id)>
                                                        {{ $gateway->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Buy At')</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control" name="buy_at" value="{{ old('buy_at', @$currency->buy_at) }}" required />
                                                <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Sell At')</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control" name="sell_at" value="{{ old('sell_at', @$currency->sell_at) }}" required />
                                                <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Reserve')</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control" name="reserve" value="{{ old('reserve', @$currency->reserve) }}" required />
                                                <span class="currency-symbol input-group-text"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Available For sell')</label>
                                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="available_for_sell" {{ @$currency ? ($currency->available_for_sell ? 'checked' : '') : 'checked' }}>
                                        </div>
                                    </div>

                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Available For buy')</label>
                                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="available_for_buy" {{ @$currency ? ($currency->available_for_buy ? 'checked' : '') : 'checked' }}>
                                        </div>
                                    </div>

                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Rate Show')</label>
                                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="rate_show" {{ @$currency ? ($currency->show_rate ? 'checked' : '') : 'checked' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-12 col-md-6">
                <div class="card">
                    <h5 class="card-header bg--info">@lang('Limit & Charge for Sale')</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang('Minimum Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="minimum_limit_for_sell" required value="{{ old('minimum_limit_for_sell', @$currency->minimum_limit_for_sell) }}" />
                                    <span class="input-group-text currency-symbol d-none"></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Maximum Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="maximum_limit_for_sell" required value="{{ old('maximum_limit_for_sell', @$currency->maximum_limit_for_sell) }}" />
                                    <span class="input-group-text currency-symbol d-none"></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Fixed Charge')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="fixed_charge_for_sell" required value="{{ old('fixed_charge_for_sell', @$currency->fixed_charge_for_sell) }}" />
                                    <div class="input-group-text currency-symbol d-none"></div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Percent Charge')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="percent_charge_for_sell" required value="{{ old('percent_charge_for_sell', @$currency->percent_charge_for_sell) }}" />
                                    <div class="input-group-text">@lang('%')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-12 col-md-6">
                <div class="card">
                    <h5 class="card-header bg--warning">@lang('Limit & Charge to Buy')</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang('Minimum Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="minimum_limit_for_buy" required value="{{ old('minimum_limit_for_buy', @$currency->minimum_limit_for_buy) }}" />
                                    <span class="input-group-text currency-symbol d-none "></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Maximum Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="maximum_limit_for_buy" required value="{{ old('maximum_limit_for_buy', @$currency->maximum_limit_for_buy) }}" />
                                    <span class="input-group-text currency-symbol d-none"></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Fixed Charge')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="fixed_charge_for_buy" required value="{{ old('fixed_charge_for_buy', @$currency->fixed_charge_for_buy) }}" />
                                    <div class="input-group-text currency-symbol d-none"></div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Percent Charge')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="percent_charge_for_buy" required value="{{ old('percent_charge_for_buy', @$currency->percent_charge_for_buy) }}" />
                                    <div class="input-group-text">@lang('%')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="forManualGateway my-4">
            <div class="card">
                <h5 class="card-header">@lang('Instruction') <i class="fa fa-info-circle text--primary" title="@lang('Write the payment instruction here. Users will see the instruction while exchanging money.')"></i></h5>
                <div class="card-body">
                    <div class="form-group">
                        <textarea rows="8" class="form-control nicEdit" name="instruction">{{ old('instruction', @$currency->instruction) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between">
                    <h5>@lang('Transaction Proof Form') <i class="fa fa-info-circle text--primary" title="@lang('Users have to fill the form while sending money with this currency.')"></i></h5>
                    <button type="button" class="btn btn-sm btn-outline--primary float-end form-generate-btn" append-to="#transaction-proof" input-name="transaction_proof">
                        <i class="la la-fw la-plus"></i>@lang('Add New')
                    </button>
                </div>
                <div class="card-body">
                    <div class="row addedField" id="transaction-proof">
                        @if (@$currency && $currency->transactionProvedData)
                            @foreach ($currency->transactionProvedData->form_data as $formData)
                                <div class="col-md-4">
                                    <div class="card border mb-3" id="tr{{ $loop->index }}">
                                        <input type="hidden" name="transaction_proof[is_required][]" value="{{ $formData->is_required }}">
                                        <input type="hidden" name="transaction_proof[extensions][]" value="{{ $formData->extensions }}">
                                        <input type="hidden" name="transaction_proof[options][]" value="{{ implode(',', $formData->options) }}">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>@lang('Label')</label>
                                                <input type="text" name="transaction_proof[form_label][]" class="form-control" value="{{ $formData->name }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('Type')</label>
                                                <input type="text" name="transaction_proof[form_type][]" class="form-control" value="{{ $formData->type }}" readonly>
                                            </div>
                                            @php
                                                $jsonData = json_encode([
                                                    'type' => $formData->type,
                                                    'is_required' => $formData->is_required,
                                                    'label' => $formData->name,
                                                    'extensions' => explode(',', $formData->extensions) ?? 'null',
                                                    'options' => $formData->options,
                                                    'old_id' => '',
                                                ]);
                                            @endphp
                                            <div class="btn-group w-100">
                                                <button type="button" class="btn btn--primary editFormData" data-form_item="{{ $jsonData }}" data-update_id="tr{{ $loop->index }}">
                                                    <i class="las la-pen"></i>
                                                </button>
                                                <button type="button" class="btn btn--danger removeFormData">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card my-4">
            <div class="card-header d-flex justify-content-between">
                <h5>@lang('Sending Form')</h5>

                <button type="button" class="btn btn-sm btn-outline--primary float-end form-generate-btn" append-to="#sending-details">
                    <i class="la la-fw la-plus"></i>@lang('Add New')
                </button>
            </div>
            <div class="card-body">
                <div class="row addedField" id="sending-details">
                    @if (@$currency && $currency->userDetailsData)
                        @foreach ($currency->userDetailsData->form_data as $formData)
                            <div class="col-md-4">
                                <div class="card border mb-3" id="{{ $loop->index }}">
                                    <input type="hidden" name="form_generator[is_required][]" value="{{ $formData->is_required }}">
                                    <input type="hidden" name="form_generator[extensions][]" value="{{ $formData->extensions }}">
                                    <input type="hidden" name="form_generator[options][]" value="{{ implode(',', $formData->options) }}">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>@lang('Label')</label>
                                            <input type="text" name="form_generator[form_label][]" class="form-control" value="{{ $formData->name }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('Type')</label>
                                            <input type="text" name="form_generator[form_type][]" class="form-control" value="{{ $formData->type }}" readonly>
                                        </div>
                                        @php
                                            $jsonData = json_encode([
                                                'type' => $formData->type,
                                                'is_required' => $formData->is_required,
                                                'label' => $formData->name,
                                                'extensions' => explode(',', $formData->extensions) ?? 'null',
                                                'options' => $formData->options,
                                                'old_id' => '',
                                            ]);
                                        @endphp
                                        <div class="btn-group w-100">
                                            <button type="button" class="btn btn--primary editFormData" data-form_item="{{ $jsonData }}" data-update_id="{{ $loop->index }}"><i class="las la-pen"></i>
                                            </button>
                                            <button type="button" class="btn btn--danger removeFormData">
                                                <i class="las la-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
        </div>
        </div>
    </form>
    <x-form-generator />
@endsection

@push('breadcrumb-plugins')
    <x-back :route="route('admin.currency.index')" />
@endpush

@push('script')
    <script>
        "use strict"
        var formGenerator = new FormGenerator();
    </script>

    <script src="{{ asset('assets/global/js/form_actions.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        $(function() {

            const currencySymbol = () => {
                let currency = $('.currency').val();
                if (currency && currency.length > 0) {
                    currency = currency.toUpperCase();
                    $('.currency').val(currency);
                    $('.currency-symbol').removeClass('d-none');
                    $('.currency-symbol').parent().find('input').removeClass('rounded');
                    $('.currency-symbol').text(currency);
                } else {
                    $('.currency-symbol').addClass('d-none');
                    $('.currency-symbol').parent().find('input').addClass('rounded');
                }
            }
            @if (@$currency)
                currencySymbol();
            @endif

            $('.currency').on('input', currencySymbol);

            $('[name=payment_gateway]').on('change', function() {
                if (this.value != 0) {
                    $('.forManualGateway').addClass('d-none');
                } else {
                    $('.forManualGateway').removeClass('d-none');
                }
            }).change();
        });
    </script>
@endpush

@push('style')
    <style>
        .thumb .profilePicPreview {
            width: 100%;
            height: 250px;
            display: block;
            border: 3px solid #f1f1f1;
            box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.25);
            border-radius: 10px;
            background-size: cover;
            background-position: center
        }

        .thumb .profilePicUpload {
            font-size: 0;
            opacity: 0;
            width: 0;
        }

        .thumb .avatar-edit label {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            text-align: center;
            line-height: 45px;
            border: 2px solid #fff;
            font-size: 18px;
            cursor: pointer;
        }

        .thumb {
            position: relative;
            margin-bottom: 30px;
        }

        .thumb .avatar-edit {
            position: absolute;
            bottom: -15px;
            right: 0;
        }
    </style>
@endpush
