@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $content = getContent('login.content', true);
    @endphp
    <div class="account-section">
        <a class="account-section__close" href="{{ route('home') }}"> <i class="las la-times"></i></a>
        <div class="account-wrapper  d-flex justify-content-between w-100 flex-wrap flex-md-nowrap">
            <div class="account-left pe-lg-5 pe-md-4">
                <div class="account-content">
                    <div class="pb-60">
                        <a href="{{ route('home') }}" class="logo">
                            <img src="{{ siteLogo() }}" alt="{{ __($general->site_name) }}" title="{{ __($general->site_name) }}">
                        </a>
                    </div>
                    <h2 class="title">{{ __(@$content->data_values->heading) }}</h2>
                </div>
                <div class="account-thumb pt-80">
                    <img src="{{ getImage('assets/images/frontend/login/' . @$content->data_values->login_image, '800x400') }}" class="mt-auto mw-100">
                </div>
            </div>
            <div class="account-right my-auto">
                <div class="form-wrapper">
                    <div class="mb-4 mb-lg-5">
                        <h3>@lang('Sign in')</h3>
                    </div>
                    <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                        @csrf
                        <div class="floating-label form-group">
                            <input class="floating-input form-control form--control" name="username" type="text" required placeholder="none">
                            <label class="form-label-two fw-semibold">@lang('Username Or Email ')</label>
                        </div>
                        <div class="floating-label form-group">
                            <input class="floating-input form-control form--control" name="password" type="password" required placeholder="none">
                            <label class="form-label-two fw-semibold">@lang('Password')</label>
                        </div>
                        <x-captcha />
                        <div class="remember-wrapper d-flex flex-wrap justify-content-between my-3">
                            <div class="form-check">
                                <input type="checkbox" id="remember" class="form-check-input" name="remember">
                                <label for="remember" class="fw-semibold form-check-label">@lang('Remember Me')</label>
                            </div>
                            <p class="text-end"> <a href="{{ route('user.password.request') }}" class="text--base ms-2">@lang('Forgot Password?')</a></p>
                        </div>
                        <button class="btn--base btn  w-100" type="submit">@lang('Login')</button>
                        @php
                            $credentials = $general->socialite_credentials;
                        @endphp
                        @if (@$credentials->google->status == Status::ENABLE || @$credentials->facebook->status == Status::ENABLE ||
                            @$credentials->linkedin->status == Status::ENABLE)
                            <div class="col-12">
                                <div class="other-option">
                                    <span class="other-option__text">@lang('OR')</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-3">
                                @if ($credentials->facebook->status == Status::ENABLE)
                                    <a href="{{ route('user.social.login', 'facebook') }}"
                                        class="btn btn-outline-facebook btn-sm flex-grow-1">
                                        <span class="me-1"><i class="fab fa-facebook-f"></i></span>@lang('Facebook')
                                    </a>
                                @endif
                                @if ($credentials->google->status == Status::ENABLE)
                                    <a href="{{ route('user.social.login', 'google') }}"
                                        class="btn btn-outline-google btn-sm flex-grow-1">
                                        <span class="me-1"><i class="lab la-google"></i></span>@lang('Google')
                                    </a>
                                @endif
                                @if ($credentials->linkedin->status == Status::ENABLE)
                                    <a href="{{ route('user.social.login', 'linkedin') }}"
                                        class="btn btn-outline-linkedin btn-sm flex-grow-1">
                                        <span class="me-1"><i class="lab la-linkedin-in"></i></span>@lang('Linkedin')
                                    </a>
                                @endif
                            </div>
                        @endif

                        <div class="mt-3">
                            <p>@lang("Don't have account?")<a href="{{ route('user.register') }}" class="text--base ms-1">@lang('Sign Up')</a></p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            let captcha = $("input[name=captcha]");
            if (parseInt(captcha.length) > 0) {
                let html = `
                        <div class="floating-label form-group mb-0">
                                <input type="text" name="captcha" class="floating-input form-control form--control" placeholder="none" required>
                                <label class="form-label-two fw-semibold" for="">@lang('Captcha')</label>
                        </div>
                        `;
                $(captcha).remove();
                $(".captchaInput").html(html);
            }

            $('.customCaptcha').find('label').first().remove();
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .content-area {
            z-index: -1;
            height: 100%;
        }

        .btn-outline-linkedin {
            border-color: #0077B5;
            background-color: transparent;
            color: #0077B5;
        }

        .btn-outline-linkedin:hover {
            border-color: #0077B5;
            color: #fff !important;
            background-color: #0077B5;
        }

        .btn-outline-facebook {
            border-color: #395498;
            background-color: transparent;
            color: #395498;
        }

        .btn-outline-facebook:hover {
            border-color: #395498;
            color: #fff !important;
            background-color: #395498;
        }

        .btn-outline-google {
            border-color: #D64937;
            background-color: transparent;
            color: #D64937;
        }

        .btn-outline-google:hover {
            border-color: #D64937;
            color: #fff !important;
            background-color: #D64937;
        }

        .row>* {
            padding-right: calc(var(--bs-gutter-x) * .0);
        }
        .other-option {
            margin: 25px 0 25px;
            position: relative;
            text-align: center;
            z-index: 1;
        }
        .other-option::before {
            position: absolute;
            content: "";
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            z-index: -1;
            background: #eee;
        }
        .other-option__text {
            background-color: #fff;
            color: #000;
            display: inline-block;
            padding:0px 12px;
        }
    </style>
@endpush
