@extends($activeTemplate.'layouts.master')
@section('content')
<div class="container">
    <div class="row gy-4">
        <div class="col-lg-12 text-end">
            <a href="{{ route('ticket.open') }}" class="btn btn--base btn--sm">
                <i class="las la-plus"></i>
                @lang('Create Ticket')
            </a>
        </div>
        <div class="col-12">
            <div class="card custom--card">
                <div class="card-body p-0">
                    <table class="table table--responsive--md">
                        <thead>
                            <th>@lang('S.N.')</th>
                            <th>@lang('Subject')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Priority')</th>
                            <th>@lang('Last Reply')</th>
                            <th>@lang('Action')</th>
                        </thead>
                        <tbody>
                            @forelse($supports as $support)
                            <tr>
                                <td>{{$loop->index+$supports->firstItem()}}</td>
                                <td> <a href="{{ route('ticket.view', $support->ticket) }}" class="fw-bold">
                                    [@lang('Ticket')#{{$support->ticket }}] {{ __($support->subject) }} </a>
                                </td>
                                <td>
                                    @php echo $support->statusBadge; @endphp
                                </td>
                                <td>
                                    @if($support->priority == Status::PRIORITY_LOW)
                                        <span class="badge badge--dark">@lang('Low')</span>
                                    @elseif($support->priority == Status::PRIORITY_MEDIUM)
                                        <span class="badge badge--success">@lang('Medium')</span>
                                    @elseif($support->priority == Status::PRIORITY_HIGH)
                                        <span class="badge badge--primary">@lang('High')</span>
                                    @endif
                                </td>
                                <td>{{ showDateTime($support->last_reply) }} </td>
                                <td>
                                    <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn--outline-base btn-sm">
                                        <i class="las la-desktop"></i> @lang('Details')
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($supports->hasPages())
                <div class="card-footer">
                    {{ paginateLinks($supports) }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
