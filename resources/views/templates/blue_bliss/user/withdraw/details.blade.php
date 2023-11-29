@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center gy-4">
            <div class="col-lg-6">
                <div class="text-end mb-3">
                    <a class="btn btn--base btn-sm" href="{{ route('user.withdraw.history') }}"> <i class="fa fa-list"></i> @lang('Withdraw Logs')</a>
                </div>
                <div class="card custom--card justify-content-between">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">@lang('Withdraw Details')</h4>
                        <div>@php echo $withdraw->statusBadge @endphp</div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <span class="text--base">{{__(@$withdraw->trx)}}</span>
                                <small class="text-muted">@lang('Transaction ID')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <span> {{__(@$withdraw->method->name)}}</span>
                                <small class="text-muted">@lang('Withdraw Method')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <span> {{__(@$withdraw->method->cur_sym)}}</span>
                                <small class="text-muted">@lang('Withdraw Currency')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <span>{{showAmount($withdraw->amount)}} {{__($withdraw->currency)}}</span>
                                <small class="text-muted">@lang('Withdraw Amount')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <span>1 {{__($withdraw->method->cur_sym) .' = '}} {{showAmount($withdraw->rate)}} {{__($withdraw->currency)}}</span>
                                <small class="text-muted">@lang('Rate')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <span> {{showAmount($withdraw->final_amount)}} {{__(@$withdraw->method->cur_sym)}}</span>
                                <small class="text-muted">@lang('Received Amount')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <span class="text--danger"> {{showAmount($withdraw->charge)}} {{__(@$withdraw->method->cur_sym)}}</span>
                                <small class="text-muted">@lang('Charge')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <span> {{showAmount($withdraw->after_charge)}} {{__($withdraw->method->cur_sym)}}</span>
                                <small class="text-muted">@lang('Received Amount After Charge')</small>
                            </li>
                            <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap flex-column border-dotted">
                                <div>
                                    <span>{{showDateTime($withdraw->created_at)}}</span>
                                </div>
                                <small class="text-muted">@lang('Time')</small>
                            </li>
                            @if ($withdraw->admin_feedback)
                            <li class="list-group-item d-flex justify-content-between flex-wrap">
                                <span>{{__($withdraw->admin_feedback)}}</span>
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


