@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $content = getContent('login.content', true);
    @endphp
    <section class="padding-top padding-bottom  section-bg">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-4">
                <div class="card card-form custom--card">
                    <div class="card-body">
                        <h4 class="form__title mg-5">{{ __(@$content->data_values->heading) }}</h4>
                        <form method="POST" action="{{ route('user.login') }}" class=">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="form-label">@lang('Username Or Email')</label>
                                <input type="text" name="username" value="{{ old('username') }}" class="form-control form--control" required>
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">@lang('Password')</label>
                                <input id="password" type="password" class="form-control form--control" name="password" required>
                            </div>
                            <x-captcha />
                            <div class="d-flex justify-content-between">
                                <div class="form-group form--check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember"> @lang('Remember Me')</label>
                                </div>
                                <a class="text--base" href="{{ route('user.password.request') }}">
                                    @lang('Forgot Password?')
                                </a>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="recaptcha" class="btn btn--base w-100">@lang('Login') </button>
                            </div>
                            @php
                                $credentials = $general->socialite_credentials;
                            @endphp
                            @if (@$credentials->google->status == Status::ENABLE ||
                                @$credentials->facebook->status == Status::ENABLE ||
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
                                <span>
                                    @lang("Don't have an account?") <a class="text--base" href="{{ route('user.register') }}">@lang('Register')</a>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.captcha div').css({
                "background-color": "transparent",
                "border": "1px dashed #ebebeb",
                "height": "55px",
                "line-height": "55px",
                "width": "260px"
            });
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
