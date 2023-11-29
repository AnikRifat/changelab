@extends($activeTemplate.'layouts.master')
@section('content')
<div class="container">
    <div class="row mb-2 justify-content-end gy-4">
        <div class="col-lg-4">
            <form>
                <div class="input-group">
                    <input name="search" value="{{ request()->search ?? '' }}" type="text" class="form--control form-control" placeholder="@lang('Search by Transaction ID')">
                    <button type="submit" class="btn btn--base input-group-text"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-body p-0">
                    <table class="table custom--table table-responsive--md">
                        <thead>
                            <tr>
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
                                    <td>{{ __($withdraw->trx) }}</td>
                                    <td>{{ __($withdraw->method->name) }}</td>
                                    <td>{{ showAmount($withdraw->amount)}} {{ __($general->cur_text) }} </td>
                                    <td>{{ showAmount($withdraw->rate) }} {{ __($withdraw->method->cur_sym) }}</td>
                                    <td>{{ showAmount($withdraw->charge) }} {{ __($withdraw->method->cur_sym) }}</td>
                                    <td>{{ showAmount($withdraw->final_amount) }} {{ __($withdraw->method->cur_sym) }}</td>
                                    <td>@php echo $withdraw->statusBadge @endphp </td>
                                    <td>
                                        <a href="{{ route('user.withdraw.details',$withdraw->trx) }}" class="btn btn--base-outline btn-sm">
                                            <i class="las la-desktop"></i>@lang('Details')
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
            </div>

            @if($withdraws->hasPages())
                <div>
                    {{ paginateLinks($withdraws) }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
