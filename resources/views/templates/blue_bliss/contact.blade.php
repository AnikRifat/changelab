@extends($activeTemplate.'layouts.frontend')
@php
$contact = getContent('contact_us.content',true);
$content = getContent('header.content', true);
@endphp
@section('content')
<section class="contact-section padding-top padding-bottom">
    <div class="container">
        <div class="row justify-content-center gy-5 ">
            <div class="col-xl-7 col-lg-6">
                <div class="account-form">
                    <div class="account-form__content mb-5">
                        <h3 class="account-form__title mb-3">{{__(@$contact->data_values->heading)}}</h3>
                        <p class="account-form__desc">{{__(@$contact->data_values->subheading)}}</p>
                    </div>
                    <form action="{{ route('contact') }}" method="POST" class="verify-gcaptcha">
                        @csrf
                        <div class="row gy-3">
                            <div class="form-group col-sm-12">
                                <label>@lang('Your Name')</label>
                                <input name="name" class="form--control" type="text" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>@lang('Email Address')</label>
                                <input name="email" type="text" class="form--control" value="{{old('email')}}" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>@lang('Subject')</label>
                                <input name="subject" type="text" class="form--control" value="{{old('subject')}}" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>@lang('Your Message')</label>
                                <textarea name="message" wrap="off" required class="form--control">{{old('message')}}</textarea>
                            </div>
                            <x-captcha />

                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xl-5 col-lg-6 ps-lg-5">
                <div class="contact-item">
                    <div class="contact-item__icon"><i class="far fa-envelope"></i></div>
                    <div class="contact-item__content">
                        <h6 class="contact-item__title">@lang('Mail Us')</h6>
                        <a class="contact-item__desc text-dark" href="mailto:{{@$content->data_values->email }}">{{ @$content->data_values->email }}</a>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item__icon"><i class="fa fa-phone"></i></div>
                    <div class="contact-item__content">
                        <h6 class="contact-item__title">@lang('Mobile')</h6>
                        <a class="contact-item__desc text-dark"  href="tel:{{@$content->data_values->mobile }}">{{ @$content->data_values->mobile }}</a>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item__icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="contact-item__content">
                        <h6 class="contact-item__title">@lang('Addres')</h6>
                        <p class="contact-item__desc">{{ @$content->data_values->address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
    <script>
        "use strict";
        (function ($) {
            $(".account-form").find('.mb-2').addClass('mb-3')
        })(jQuery);

    </script>
@endpush


