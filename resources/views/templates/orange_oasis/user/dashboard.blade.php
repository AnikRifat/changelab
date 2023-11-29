@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $kyc = getContent('kyc_content.content', true);
    @endphp
    <div class="container">
        <div class="row gy-4 mb-4">
            @if ($user->kv == 0)
                <div class="col-12">
                    <div class="alert alert--danger" role="alert">
                        <h4 class="alert-heading mb-2">@lang('KYC Verification required')</h4>
                        <p>
                            {{ __(@$kyc->data_values->unverified_content) }}
                            <a href="{{ route('user.kyc.form') }}" class="text--base fw-bold">@lang('Click Here to Verify')</a>
                        </p>
                    </div>
                </div>
            @endif
            @if ($user->kv == 2)
                <div class="col-12">
                    <div class="alert alert--warning" role="alert">
                        <h4 class="alert-heading mb-2">@lang('KYC Verification pending')</h4>
                        <p>
                            {{ __(@$kyc->data_values->pending_content) }}
                            <a href="{{ route('user.kyc.data') }}" class="text--base fw-bold">@lang('See KYC Data')</a>
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <div class="d-flex flex-wrap gap-3">

            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-sync"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Approved Exchange')</p>
                    <h3 class="dashboard-card__content-title">{{ $exchange['approved'] }}</h3>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-undo-alt"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Pending Exchange')</p>
                    <h3 class="dashboard-card__content-title">{{ $exchange['pending'] }}</h3>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-window-close"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Cancel Exchange')</p>
                    <h3 class="dashboard-card__content-title">{{ $exchange['cancel'] }}</h3>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-sync-alt"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Refund Exchange')</p>
                    <h3 class="dashboard-card__content-title">{{ $exchange['refunded'] }}</h3>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-exchange-alt"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Total Exchange')</p>
                    <h3 class="dashboard-card__content-title">{{ $exchange['total'] }}</h3>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-voicemail"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Answer Ticket')</p>
                    <h3 class="dashboard-card__content-title">{{ $tickets['answer'] }}</h3>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-reply"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Reply Ticket')</p>
                    <h3 class="dashboard-card__content-title">{{ $tickets['reapply'] }}</h3>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-money-check-alt"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Current Balance')</p>
                    <h3 class="dashboard-card__content-title">{{ showAmount($user->balance) }} {{ __($general->cur_text) }}</h3>
                </div>
            </div>

        </div>

        <div class="mt-4">
            <h5 class="title mb-3 mt-2">@lang('Your Latest Exchanges')</h5>
            <div class="card custom--card">
                <div class="card-body p-0">
                    <table class="table table--responsive--lg">
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
                                    <td>
                                        <div class="table-content text-center">
                                            <div class="thumb ">
                                                <img src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}" class="thumb">
                                            </div>
                                            <span class="mt-2">{{ __(@$exchange->sendCurrency->name) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-content text-center">
                                            <div class="thumb">
                                                <img src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}">
                                            </div>
                                            <span class="mt-2">{{ __(@$exchange->receivedCurrency->name) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ @$exchange->exchange_id }}
                                    </td>
                                    <td>
                                        <div>
                                            {{ showAmount($exchange->sending_amount) }} {{ __(@$exchange->sendCurrency->cur_sym) }}
                                            <i class="las la-arrow-right text--base"></i>
                                            {{ showAmount($exchange->receiving_amount) }} {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">{{ showDateTime(@$exchange->created_at) }}</span>
                                        <span class="text--base">{{ diffForHumans(@$exchange->created_at) }}</span>
                                    </td>
                                    <td>
                                        @php echo $exchange->badgeData(); @endphp
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
        </div>
    </div>
@endsection
