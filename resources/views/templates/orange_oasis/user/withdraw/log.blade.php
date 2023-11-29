@extends($activeTemplate.'layouts.master')
@section('content')
<div class="container">
    <div class="row mb-2 justify-content-end">
        <div class="col-lg-5">
            <form>
                <div class="input-group">
                    <input name="search" value="{{ request()->search ?? '' }}" type="text" class="form--control form-control" placeholder="@lang('Search by Transaction ID')">
                    <button type="submit" class="btn btn--base input-group-text bg--base text-white"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
           <div class="card custom--card">
            <div class="card-body p-0">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('Transaction ID')</th>
                            <th>@lang('Receiving Method')</th>
                            <th>@lang('Send Amount')</th>
                            <th>@lang('Rate')</th>
                            <th>@lang('Charge')</th>
                            <th>@lang('Receivable')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdraws as $withdraw)
                            <tr>
                                <td>{{$loop->index+$withdraws->firstItem()}}</td>
                                <td>{{ __($withdraw->trx) }}</td>
                                <td>{{ __($withdraw->method->name) }}</td>
                                <td>{{ showAmount($withdraw->amount) }} {{ __($general->cur_text) }} </td>
                                <td>{{ showAmount($withdraw->rate) }} {{ __($withdraw->method->cur_sym) }}</td>
                                <td>{{ showAmount($withdraw->charge) }} {{ __($withdraw->method->cur_sym) }}</td>
                                <td>{{ showAmount($withdraw->final_amount) }} {{ __($withdraw->method->cur_sym) }}</td>
                                <td>@php echo $withdraw->statusBadge @endphp </td>
                                <td>
                                    <a href="{{ route('user.withdraw.details', $withdraw->trx) }}" class="btn btn--outline-base btn-sm">
                                        <i class="las la-desktop"></i> @lang('Details')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">{{ $emptyMessage }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($withdraws->hasPages())
                <div class="card-footer">
                    {{ paginateLinks($withdraws) }}
                </div>
            @endif
           </div>
        </div>
    </div>
</div>
@endsection
