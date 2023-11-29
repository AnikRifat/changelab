@php
    $footerContent = getContent('footer.content', true);
    $socialIcons = getContent('social_icons.element', false);
    $topCurrencies = App\Models\Exchange::where('status', Status::EXCHANGE_APPROVED)
        ->orWhere('automatic_payment_status', Status::YES)
        ->groupBy('send_currency_id')
        ->selectRaw('count(send_currency_id) as gateway , send_currency_id')
        ->take(3)
        ->with('sendCurrency:id,name,cur_sym')
        ->get();
    $policyPages = getContent('policy_pages.element', false, null, true);
    $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
@endphp

<!--=======Footer-Section Starts Here=======-->
<footer class="overflow-hidden">
    <div class="footer-top padding-top padding-bottom">
        <div class="container">
            <div class="footer-area">
                <div class="footer-widget widget-about">
                    <div class="footer-logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ siteLogo('dark') }}" alt="{{ __($general->site_name) }}" title="{{ __($general->site_name) }}">
                        </a>
                    </div>
                    <p>{{ __(@$footerContent->data_values->details) }}</p>
                </div>
                <div class="footer-widget widget-link">
                    <h5 class="title">@lang('Support')</h5>
                    <ul>
                        <li>
                            <a href="{{ route('contact') }}">@lang('Contact')</a>
                        </li>
                        <li>
                            <a href="{{ route('pages', 'blog') }}">@lang('Blog')</a>
                        </li>
                        @guest
                            <li>
                                <a href="{{ route('user.login') }}">@lang('Login')</a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('user.home') }}">@lang('Dashboard')</a>
                            </li>
                        @endguest
                    </ul>
                </div>

                <div class="footer-widget widget-link">
                    <h5 class="title">@lang('Exchange Gateways')</h5>
                    <ul>
                        @foreach ($topCurrencies as $topCurrency)
                            <li>
                                <a href="{{ route('home') }}">
                                    {{ __(@$topCurrency->sendCurrency->name) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="footer-widget widget-link">
                    <h5 class="title">@lang('Useful Link')</h5>
                    <ul>
                        @foreach ($policyPages as $policy)
                            <li>
                                <a href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">
                                    {{ __($policy->data_values->title) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom section-bg">
        <div class="container d-flex flex-wrap align-items-center justify-content-between">
            <p>@lang('Copyright') &copy; {{ date('Y') }} {{ __(@$footerContent->data_values->copyright) }}</p>
            <ul class="social-icons">
                @foreach ($socialIcons as $socialIcon)
                    <li title="{{ ucfirst(@$socialIcon->data_values->title) }}">
                        <a href="{{ @$socialIcon->data_values->url }}" class="{{ strtolower(@$socialIcon->data_values->name) }}">
                            @php  echo @$socialIcon->data_values->icon; @endphp
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
<!--=======Footer-Section Ends Here=======-->

@if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
    <div class="cookies-card text-center hide">
        <div class="cookies-card__icon bg--base">
            <i class="fas fa-cookie-bite"></i>
        </div>
        <p class="mt-4 cookies-card__content">{{ __($cookie->data_values->short_desc) }} <a href="{{ route('cookie.policy') }}" target="_blank" class="text--base">@lang('learn more')</a></p>
        <div class="cookies-card__btn mt-4">
            <button type="button" class="btn btn--base w-100 policy cookie-btn">@lang('Allow')</button>
        </div>
    </div>
@endif
