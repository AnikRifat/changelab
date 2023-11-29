@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $kyc = getContent('kyc_content.content', true);
    @endphp
    <div class="container">
        <div class="row justify-content-center gy-4">
            @if ($user->kv == 0)
                <div class="col-12">
                    <div class="alert alert-danger mb-0" role="alert">
                        <h6 class="alert-heading">@lang('KYC Verification')</h6>
                        <p class="py-2">
                            {{ __(@$kyc->data_values->pending_content) }}
                            <a href="{{ route('user.kyc.form') }}" class="fw-bold">@lang('Click here to verify')</a>
                        </p>
                    </div>
                </div>
            @endif
            @if ($user->kv == 2)
                <div class="col-12">
                    <div class="alert alert--warning mb-0" role="alert">
                        <h6 class="alert-heading">@lang('KYC Verification Pending')</h6>
                        <p class="py-2">
                            {{ __(@$kyc->data_values->pending_content) }}
                            <a href="{{ route('user.kyc.data') }}" class="text--base fw-bold">@lang('See KYC Data')</a>
                        </p>
                    </div>
                </div>
            @endif
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-sync"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Approved Exchange')</h5>
                        <h4 class="widget-item__amount ">{{ getAmount($exchange['approved']) }}</h4>
                        <a href="{{ route('user.exchange.list', 'approved') }}" class="btn--simple">
                            @lang('View All') <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-undo-alt"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Pending Exchange')</h5>
                        <h4 class="widget-item__amount ">{{ getAmount($exchange['pending']) }}</h4>
                        <a href="{{ route('user.exchange.list', 'pending') }}" class="btn--simple">@lang('View All')
                            <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-window-close"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Cancled Exchange')</h5>
                        <h4 class="widget-item__amount ">{{ getAmount($exchange['cancel']) }}</h4>
                        <a href="{{ route('user.exchange.list', 'canceled') }}" class="btn--simple">@lang('View All')
                            <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-sync-alt"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Refunded Exchange')</h5>
                        <h4 class="widget-item__amount ">{{ getAmount($exchange['refunded']) }}</h4>
                        <a href="{{ route('user.exchange.list', 'refunded') }}" class="btn--simple">@lang('View All')
                            <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-exchange-alt"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Total Exchange')</h5>
                        <h4 class="widget-item__amount ">{{ getAmount($exchange['total']) }}</h4>
                        <a href="{{ route('user.exchange.list', 'list') }}" class="btn--simple">@lang('View All')
                            <span class="icon text--base"> <i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="widget-item h-100">
                    <div class="widget-item__icon"><i class="fas fa-voicemail"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Answer Ticket')</h5>
                        <h4 class="widget-item__amount ">{{ getAmount($tickets['answer']) }}</h4>
                        <a href="{{ route('ticket.index') }}" class="btn--simple">
                            @lang('View All')<span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-reply"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Reply Ticket')</h5>
                        <h4 class="widget-item__amount ">{{ getAmount($tickets['reapply']) }}</h4>
                        <a href="{{ route('ticket.index') }}" class="btn--simple">
                            @lang('View All') <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Your Balance')</h5>
                        <h4 class="widget-item__amount ">{{ showAmount($user->balance) }}</h4>
                        <a href="{{ route('user.report.commission.log') }}" class="btn--simple">
                            @lang('View All') <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5">
                <h5 class="title mb-2">@lang('Your Latest Exchanges')</h5>
                <div class="card custom--card">
                    <div class="card-body p-0">
                        <table class="table custom--table table-responsive--md">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Send')</th>
                                    <th>@lang('Received')</th>
                                    <th>@lang('Exchange ID')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestExchange as $exchange)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-start">
                                            <span class="thumb">
                                                <img class="table-currency-img" src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}">
                                            </span>
                                            {{ $exchange->sendCurrency->name }}
                                        </td>

                                        <td class="text-start">
                                            <span class="thumb">
                                                <img src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}" class="table-currency-img">
                                            </span>
                                            <span>
                                                {{ __($exchange->receivedCurrency->name) }}
                                            </span>
                                        </td>
                                        <td>{{ @$exchange->exchange_id }}</td>
                                        <td>
                                            <div>
                                                {{ showAmount($exchange->sending_amount) }} {{ __(@$exchange->sendCurrency->cur_sym) }}
                                                <i class="la la-arrow-right text--base"></i>
                                                {{ showAmount($exchange->receiving_amount) }} {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ showDateTime(@$exchange->created_at) }}</span>
                                            <span class="text--base">{{ diffForHumans(@$exchange->created_at) }}</span>
                                        </td>
                                        <td> @php echo $exchange->badgeData(); @endphp </td>
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
            </div>
        </div>
    </div>
@endsection
