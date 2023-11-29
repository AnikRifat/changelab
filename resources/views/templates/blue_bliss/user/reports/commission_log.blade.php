@extends($activeTemplate.'layouts.master')
@section('content')
<div class="container">
   <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-body p-0">
                    <table class="table custom--table table-responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('Commission From')</th>
                                <th>@lang('Commission Level')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Transaction')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>{{ __(@$log->userFrom->username) }}</td>
                                <td>{{ __($log->level) }}</td>
                                <td>{{ showAmount($log->amount) }} {{ __($general->cur_text) }}</td>
                                <td>{{ __($log->title) }}</td>
                                <td>{{ $log->trx }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($logs->hasPages())
            {{ paginateLinks($logs) }}
            @endif
        </div>
    </div>
</div>
@endsection
