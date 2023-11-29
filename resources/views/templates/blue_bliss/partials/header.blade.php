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
                            <a href="mailto:{{ @$header->data_values->email }}">{{ @$header->data_values->email }}</a>
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
                            <a href="{{ route('home') }}" class="{{ menuActive('home') }}">@lang('Home')</a>
                        </li>
                        @foreach ($pages as $page)
                            <li>
                                <a href="{{ route('pages', [$page->slug]) }}" class="{{ menuActive('pages', null, $page->slug) }}">{{ __($page->name) }}</a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('blog') }}" class="{{ menuActive('blog') }}">@lang('Blog')</a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="{{ menuActive('contact') }}">@lang('Contact')</a>
                        </li>
                        @guest
                            <li class="d-lg-none d-block">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <a href="{{ route('user.login') }}" class="btn btn--base-outline btn-sm">@lang('Login')</a>
                                    <a href="{{ route('user.register') }}" class="btn btn--base btn-sm ">@lang('Register')</a>
                                </div>
                            </li>
                        @else
                            <li class="d-lg-none d-block">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <a href="{{ route('user.home') }}" class="btn btn--base btn-sm">@lang('Dashboard')</a>
                                    <a href="{{ route('user.logout') }}" class="btn btn--base-outline btn-sm">@lang('Logout')</a>
                                </div>
                            </li>
                        @endguest

                    </ul>

                    @guest
                        <div class="header-buttons d-lg-block d-none">
                            <a href="{{ route('user.login') }}" class="btn btn--base-outline btn-sm">@lang('Login')</a>
                            <a href="{{ route('user.register') }}" class="btn btn--base btn-sm ">@lang('Register')</a>
                        </div>
                    @else
                        <div class="header-buttons d-lg-block d-none">
                            <a href="{{ route('user.home') }}" class="btn btn--base btn-sm">@lang('Dashboard')</a>
                            <a href="{{ route('user.logout') }}" class="btn btn--base-outline btn-sm">@lang('Logout')</a>
                        </div>
                    @endguest

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
