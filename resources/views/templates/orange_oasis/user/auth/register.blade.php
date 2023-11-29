@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $content = getContent('register.content', true);
    @endphp
    <div class="account-section registration">
        <a class="account-section__close" href="{{ route('home') }}"> <i class="las la-times"></i></a>
        <div class="account-wrapper d-flex justify-content-between w-100 flex-wrap flex-lg-nowrap">
            <div class="account-left pe-lg-5">
                <div class="account-content">
                    <div class="pb-60">
                        <a href="{{ route('home') }}" class="logo">
                            <img src="{{ siteLogo() }}" alt="{{ __($general->site_name) }}" title="{{ __($general->site_name) }}">
                        </a>
                    </div>
                    <h2 class="title">{{ __(@$content->data_values->heading) }}</h2>
                </div>
                <div class="account-thumb pt-80">
                    <img src="{{ getImage('assets/images/frontend/register/' . @$content->data_values->register_image, '800x400') }}" class="mt-auto mw-100">
                </div>
            </div>
            <div class="account-right my-auto sign-up">
                <div class="form-wrapper">
                    <div class="mb-4 mb-lg-5">
                        <h3>@lang('Signup Account')</h3>
                    </div>
                    <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
                        @csrf
                        <div class="row gy-1">
                            @if (session()->has('reference'))
                                <div class="col-12">
                                    <div class="floating-label form-group">
                                        <input type="text" placeholder="none" name="referBy" id="referenceBy" class="floating-input form-control form--control" value="{{ session()->get('reference') }}" readonly autofocus autocomplete="off">
                                        <label for="referBy" class="form-label-two fw-semibold">@lang('Reference by')</label>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="floating-label mb-0">
                                        <input type="text" class="floating-input form-control form--control checkUser" placeholder="none" value="{{ old('username') }}" name="username" required autocomplete="off">
                                        <label class="form-label-two fw-semibold" for="">@lang('Username')</label>
                                    </div>
                                    <small class="text--danger usernameExist"></small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="floating-label form-group">
                                    <input type="email" name="email" placeholder="none" class="floating-input form-control form--control checkUser" value="{{ old('email') }}" required>
                                    <label class="form-label-two fw-semibold" for="">@lang('Email')</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="floating-label form-group">
                                    <select name="country" class="form-select floating-input form-control form--control">
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                    <label class="form-label-two fw-semibold required" for="">@lang('Country')</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input type="hidden" name="mobile_code">
                                <input type="hidden" name="country_code">
                                <div class="form-group">
                                    <div class="floating-label form-group phone-number mb-0">
                                        <span class="mobile-code"></span>
                                        <input type="number" class="floating-input form-control form--control checkUser" name="mobile" placeholder="none" required>
                                        <label class="form-label-two fw-semibod" for="">@lang('Mobile') </label>
                                    </div>
                                    <small class="text--danger mobileExist"></small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="floating-label form-group">
                                    <input type="password" class="floating-input form-control form--control" name="password" placeholder="none" required>
                                    <label class="form-label-two fw-semibold" for="">@lang('Password')</label>
                                    @if ($general->secure_password)
                                        <div class="input-popup">
                                            <p class="error lower">@lang('1 small letter minimum')</p>
                                            <p class="error capital">@lang('1 capital letter minimum')</p>
                                            <p class="error number">@lang('1 number minimum')</p>
                                            <p class="error special">@lang('1 special character minimum')</p>
                                            <p class="error minimum">@lang('6 character password')</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="floating-label form-group">
                                    <input type="password" name="password_confirmation" class="floating-input form-control form--control" placeholder="none" required>
                                    <label class="form-label-two fw-semibold" for="">@lang('Confirm Password')</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <x-captcha />
                            </div>

                            @if ($general->agree)
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" @checked(old('agree')) name="agree" required>
                                        <label for="agree" class="fw-semibold form-check-label">@lang('I agree with')
                                            @foreach ($policyPages as $policy)
                                                <a class="text--base" href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">
                                                    {{ __($policy->data_values->title) }}
                                                </a>
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <div class="col-12">
                                <button class="btn--base btn  w-100" type="submit">@lang('Signup')</button>
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
                                            <span class="me-1"><i class="lab la-facebook-f"></i></span>@lang('Facebook')
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
                        </div>
                        <p class="text-start  mt-3">@lang('Already have account?')
                            <a href="{{ route('user.login') }}" class="text--base">@lang('Sign In')</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="existModalCenter">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn--base btn-sm">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@if ($general->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function($) {
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });

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
            padding: 0 12px;
        }
    </style>
@endpush
