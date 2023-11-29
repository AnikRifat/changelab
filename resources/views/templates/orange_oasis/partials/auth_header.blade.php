<div class="header">
    <div class="container">
        <div class="header-bottom">
            <div class="header-bottom-area align-items-center">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ siteLogo() }}" alt="logo">
                    </a>
                </div>
                <ul class="menu">
                    <li>
                        <a href="{{ route('home') }}" class="{{ menuActive('home') }}">@lang('Home')</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="{{ menuActive('user.exchange*') }}">@lang('Exchange History')</a>
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
                        <a href="javascript:void(0)" class="{{ menuActive('ticket*') }}">@lang('Ticket')</a>
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
                        <a href="javascript:void(0)" class="{{ menuActive('user.withdraw*') }}">@lang('Withdraw')</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('user.withdraw') }}">@lang('Withdraw Money')</a>
                            </li>
                            <li>
                                <a href="{{ route('user.withdraw.history') }}">
                                    @lang('Withdraw Log')
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="{{ menuActive(['user.affiliate.index', 'user.change.password', 'user.profile.setting', 'user.twofactor']) }}">@lang('Account')</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('user.affiliate.index') }}">
                                    @lang('Affiliation')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.report.commission.log') }}">
                                    @lang('Commission Logs')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.profile.setting') }}">
                                    @lang('Profile Setting')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.twofactor') }}">
                                    @lang('2FA Security')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.change.password') }}">
                                    @lang('Change Password')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.logout') }}">
                                    @lang('Logout')
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('user.home') }}" class="btn btn--base btn-sm mt-lg-0 mt-3 d-inline-block">@lang('Dashboard')</a>
                    </li>
                </ul>
                <div class="header-trigger-wrapper d-flex d-lg-none align-items-center">
                    <div class="header-trigger">
                        <i class="las la-bars"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
