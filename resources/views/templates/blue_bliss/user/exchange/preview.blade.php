@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row gy-4">
                    <div class="col-12">
                        <div class="card custom--card">
                            <div class="card-body">
                                <h6 class="text-center"> @lang('Exchange ID: ') <span class="text-muted">#{{ $exchange->exchange_id }}</span></h6>
                                <p class="mt-1 fw-bold text-center text--warning">
                                    @lang('Send') {{ showAmount($exchange->sending_amount + $exchange->sending_charge) }} {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }} @lang('via') {{ __(@$exchange->sendCurrency->name) }} @lang('to get') {{ showAmount($exchange->receiving_amount - $exchange->receiving_charge) }} {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }} @lang('via') {{ __(@$exchange->receivedCurrency->name) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card custom--card">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Sending Details')</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush p-3">
                                    <li class="list-group-item d-flex justify-content-between flex-column flex-wrap border-dotted">
                                        <span class="fw-bold">{{ __(@$exchange->sendCurrency->name) }}</span>
                                        <small class="text-muted">@lang('Sending Method')</small>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between flex-column flex-wrap border-dotted">
                                        <span class="fw-bold">{{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}</span>
                                        <small class="text-muted">@lang('Currency')</small>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between flex-column flex-wrap border-dotted">
                                        <span class="fw-bold">
                                            {{ showAmount(@$exchange->sending_amount) }} {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                                        </span>
                                        <small class="text-muted">@lang('Sending Amount')</small>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between flex-column flex-wrap border-dotted">
                                        <span class="fw-bold text--danger">
                                            {{ showAmount(@$exchange->sending_charge) }} {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                                        </span>
                                        <small class="text-muted">@lang('Charge')</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-column flex-wrap border-dotted">
                                        <span class="fw-bold">
                                            {{ showAmount($exchange->sending_amount + $exchange->sending_charge) }} {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                                        </span>
                                        <small class="text-muted">@lang('Total Sending Amount Including Charge')</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card custom--card">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Receiving Details')</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush p-3">
                                    <li class="list-group-item d-flex justify-content-between flex-column flex-wrap border-dotted">
                                        <span class="fw-bold">{{ __(@$exchange->receivedCurrency->name) }}</span>
                                        <small class="text-muted">@lang('Receiving Method')</small>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between flex-column flex-wrap border-dotted">
                                        <span class="fw-bold">
                                            {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                                        </span>
                                        <small class="text-muted">@lang('Receiving Currency')</small>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between flex-column flex-wrap border-dotted">
                                        <span class="fw-bold">
                                            {{ showAmount(@$exchange->receiving_amount) }} {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                                        </span>
                                        <small class="text-muted">@lang('Receiving Amount')</small>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between flex-column flex-wrap border-dotted">
                                        <span class="fw-bold text--danger">
                                            {{ showAmount(@$exchange->receiving_charge) }} {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                                        </span>
                                        <small class="text-muted">@lang('Charge')</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-column flex-wrap border-dotted">
                                        <span class="fw-bold">
                                            {{ showAmount($exchange->receiving_amount - $exchange->receiving_charge) }} {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                                        </span>
                                        <small class="text-muted">@lang('Receivable Amount After Charge')</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card custom--card">
                            <div class="card-body">
                                <form method="post" action="{{ route('user.exchange.confirm') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label required" for="paytm_wallet">{{ __(@$exchange->receivedCurrency->name) }}
                                            @lang('Wallet Number/ID')</label>
                                        <input type="text" class="form-control form--control" name="wallet_id" required>
                                    </div>
                                    <x-viser-form identifier="id" identifierValue="{{ @$exchange->receivedCurrency->userDetailsData->id }}" />
                                    <button class="btn btn--base w-100 confirmationBtn" type="submit">
                                        @lang('Confirm Exchange')
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
