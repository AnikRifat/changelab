@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row gy-4">
                <div class="col-md-12 text-end">
                    <a href="{{ route('user.exchange.invoice', ['id' => $exchange->exchange_id, 'type' => 'download']) }}" class="btn btn--info btn-sm"> <i class="fa fa-download"></i> @lang('Download')</a>
                    <a href="{{ route('user.exchange.invoice', ['id' => $exchange->exchange_id, 'type' => 'print']) }}" class="btn btn--success  btn-sm"> <i class="fa fa-print"></i> @lang('Print')</a>
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
                                        {{ showAmount($exchange->sending_amount+$exchange->sending_charge) }} {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
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
                                        {{ showAmount($exchange->receiving_amount-$exchange->receiving_charge) }} {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                                    </span>
                                    <small class="text-muted">@lang('You\'ll Receive Amount After Charged')</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="card-title">@lang('Exchange Information')</h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush p-3">
                                <li class="list-group-item d-flex justify-content-between flex-wrap">
                                    <span>@lang('Exchange ID:')</span>
                                    <span class="fw-bold">{{ __($exchange->exchange_id) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between flex-wrap  align-items-center">
                                    <span>@lang('Your ') <span class="text--base">{{ __(@$exchange->receivedCurrency->name) }}</span> @lang('Wallet ID/Number')</span>
                                    <span class="fw-bold">{{$exchange->wallet_id}}</span>
                                </li>
                                @if ($exchange->status == Status::EXCHANGE_APPROVED)
                                    <li class="list-group-item d-flex justify-content-between flex-wrap align-items-center">
                                        <span>@lang('Admin Transaction/Wallet Number')</span>
                                        <span class="fw-bold">{{ $exchange->admin_trx_no }}</span>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between flex-wrap align-items-center">
                                    <span>@lang('Status')</span>
                                    <span class="text-end">@php echo $exchange->badgeData() @endphp</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between flex-wrap align-items-center">
                                    <span>@lang('Exchange Date')</span>
                                    <div class="text-end">
                                        <span class="d-block">{{ showDateTime($exchange->created_at) }}</span>
                                        <span>{{ diffForHumans($exchange->created_at) }}</span>
                                    </div>
                                </li>
                                @if ($exchange->admin_feedback != null)
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        @if ($exchange->status == Status::EXCHANGE_CANCEL)
                                            <span class="text--danger">@lang('Failed Reason')</span>
                                        @else
                                            <span>@lang('Admin Feedback')</span>
                                        @endif
                                        <span class="text-end">{{ __($exchange->admin_feedback) }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
