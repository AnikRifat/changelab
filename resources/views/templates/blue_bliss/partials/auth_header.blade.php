@php
    $header = getContent('header.content', true);
@endphp
<header>
    <div class="header-top">
        <div class="container">
            <div class="header-top-area">
                <div class="header-wrapper">
                    <div class="header-top-item">
                        <div class="header-top-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="header-top-content">
                            <span>{{ @$header->data_values->address }}</span>
                        </div>
                    </div>
                    <div class="header-top-item">
                        <div class="header-top-icon">
                            <i class="far fa-envelope"></i>
                        </div>
                        <div class="header-top-content">
                            <a href="Mailto:{{ @$header->data_values->email }}">
                                {{ @$header->data_values->email }}
                            </a>
                        </div>
                    </div>
                    <div class="header-top-right-item header-top-item">
                        <a href="tel:{{ @$header->data_values->mobile }}">
                            <i class="fas fa-phone"></i>
                        </a>
                        <span>{{ @$header->data_values->mobile }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <div class="header-bottom-area">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ siteLogo('dark') }}">
                    </a>
                </div>
                <div class="menu-area">
                    <ul class="menu">
                        <li>
                            <a href="{{ route('home') }}">@lang('Home')</a>
                        </li>
                        <li>
                            <a href="#">@lang('Exchanges')</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ route('user.exchange.list', 'list') }}">@lang('All Exchange')</a>
                                </li>
                                <li>
                                    <a href="{{ route('user.exchange.list', 'approved') }}">@lang('Approved Exchange')</a>
                                </li>
                                <li>
                                    <a href="{{ route('user.exchange.list', 'pending') }}">@lang('Pending Exchange')</a>
                                </li>
                                <li>
                                    <a href="{{ route('user.exchange.list', 'refunded') }}">@lang('Refunded Exchange')</a>
                                </li>
                                <li>
                                    <a href="{{ route('user.exchange.list', 'canceled') }}">@lang('Cancled Exchange')</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">@lang('Ticket')</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ route('ticket.open') }}">@lang('Create New')</a>
                                </li>
                                <li>
                                    <a href="{{ route('ticket.index') }}">@lang('My Ticket')</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#0">@lang('Withdraw')</a>
                            <ul class="sub-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.withdraw') }}">@lang('Withdraw Money')</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.withdraw.history') }}">@lang('Withdraw Log')</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#0">@lang('Account')</a>
                            <ul class="sub-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.affiliate.index') }}">
                                        @lang('Affiliation')
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.report.commission.log') }}">
                                        @lang('Commission Logs')
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.profile.setting') }}">
                                        @lang('Profile Setting')
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('user.twofactor') }}">
                                        @lang('2FA Security')
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.change.password') }}">
                                        @lang('Change Password')
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.logout') }}">
                                        @lang('Logout')
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="d-lg-none d-block">
                            <a href="{{ route('user.home') }}" class="btn btn--base btn-sm">@lang('Dashboard')</a>
                        </li>
                    </ul>
                    <div class="d-lg-block d-none">
                        <a href="{{ route('user.home') }}" class="btn btn--base btn-sm">@lang('Dashboard')</a>
                    </div>

                    @if ($general->multi_language)
                        <select class="select language langSel">
                            @foreach ($language as $lang)
                                <option value="{{ $lang->code }}" {{ session('lang') == $lang->code ? 'selected' : '' }}>
                                    {{ __($lang->name) }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    <div class="header-bar-area d-lg-none">
                        <div class="header-bar">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
