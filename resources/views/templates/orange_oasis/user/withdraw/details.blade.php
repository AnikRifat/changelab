@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-end mb-3">
                    <a class="btn btn--base btn-sm" href="{{ route('user.withdraw.history') }}"> <i class="fa fa-list"></i>
                        @lang('Withdraw List')
                    </a>
                </div>
                <div class="card custom--card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">@lang('Withdraw Details')</h5>
                        <span >@php echo $withdraw->statusBadge @endphp</span>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <h6 class="text--base">{{ __(@$withdraw->trx) }}</h6>
                                <small class="text-muted">@lang('Transaction ID')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <h6> {{ __(@$withdraw->method->name) }}</h6>
                                <small class="text-muted">@lang('Withdraw Method')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <h6> {{ __(@$withdraw->method->cur_sym) }}</h6>
                                <small class="text-muted">@lang('Withdraw Currency')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <h6>{{ showAmount($withdraw->amount) }} {{ __($withdraw->currency) }}</h6>
                                <small class="text-muted">@lang('Withdraw Amount')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <h6>1 {{ __($withdraw->method->cur_sym) . ' = ' }} {{ showAmount($withdraw->rate) }} {{ __($withdraw->currency) }}</h6>
                                <small class="text-muted">@lang('Rate')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <h6> {{ showAmount($withdraw->final_amount) }} {{ __(@$withdraw->method->cur_sym) }}</h6>
                                <small class="text-muted">@lang('Received Amount')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <div>
                                    <span class="text--danger">{{ showAmount($withdraw->charge) }} {{ __(@$withdraw->method->cur_sym) }}</span>
                                <div>
                                <small class="text-muted">@lang('Charge')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <h6> {{ showAmount($withdraw->after_charge) }} {{ __($withdraw->method->cur_sym) }}</h6>
                                <small class="text-muted">@lang('Received Amount After Charge')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <div>
                                    <h6 class="d-block">{{ __(showDateTime($withdraw->created_at)) }}</h6>
                                <div>
                                <small class="text-muted">@lang('Time')</small>
                            </li>
                            @if ($withdraw->admin_feedback)
                                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                    <h6>{{ __($withdraw->admin_feedback) }}</h6>
                                    <small class="text-muted">@lang('Admin Feedback')</small>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
