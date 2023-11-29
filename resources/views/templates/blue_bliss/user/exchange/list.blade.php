@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-body p-0">
                    <table class="table custom--table table-responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Exchange ID')</th>
                                <th>@lang('Send')</th>
                                <th>@lang('Received')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($exchanges as $exchange)
                                <tr>
                                    <td>{{$loop->index + $exchanges->firstItem() }}</td>
                                    <td>{{$exchange->exchange_id }}</td>
                                    <td>
                                        <div class="thumb">
                                            <img class="table-currency-img" src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}">
                                        </div>
                                        <span>{{ __(@$exchange->sendCurrency->name) }}</span>
                                    </td>
                                    <td>
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}" class="table-currency-img">
                                        </div>
                                        <span>{{ __(@$exchange->receivedCurrency->name) }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            {{ showAmount($exchange->sending_amount) }} {{ __(@$exchange->sendCurrency->cur_sym) }}
                                            <i class="las la-arrow-right text--base"></i>
                                            {{ showAmount($exchange->receiving_amount) }} {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-end">
                                            <span>{{ showDateTime(@$exchange->created_at) }}</span>
                                            <span class="text--base">{{diffForHumans(@$exchange->created_at) }}</span>
                                        </div>

                                    </td>
                                    <td> @php echo $exchange->badgeData(); @endphp </td>
                                    <td>
                                        <a href="{{ route('user.exchange.details', $exchange->exchange_id) }}" class="btn btn--base-outline btn-sm" data-reason="{{ $exchange->cancle_reason }}">
                                            <i class="las la-desktop"></i>@lang('Details')
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
            </div>
            @if ($exchanges->hasPages())
                {{ paginateLinks($exchanges) }}
            @endif
        </div>
    </div>
</div>
@endsection

