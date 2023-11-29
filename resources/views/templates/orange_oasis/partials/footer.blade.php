@php
    $footerContent = getContent('footer.content', true);
    $contactElements = getContent('contact_us.element', orderById: true);
    $policyPages = getContent('policy_pages.element', false, null, true);
    $socialIcons = getContent('social_icons.element');
@endphp

<footer class="bg--accent footer">
    <div class="container">
        <div class="pt-60 pb-60">
            <div class="row justify-content-between gy-5">
                <div class="col-lg-4 col-sm-6 col-md-5">
                    <div class="footer-widget">

                        <p> {{ __(@$footerContent->data_values->details) }}</p>
                        <ul class="social-links d-flex flex-wrap mt-3 gap-2">
                            @foreach ($socialIcons as $socialIcon)
                                <li title="{{ ucfirst(@$socialIcon->data_values->name) }}">
                                    <a href="{{ @$socialIcon->data_values->url }}" class="{{ strtolower(@$socialIcon->data_values->name) }}">
                                        @php echo @$socialIcon->data_values->icon; @endphp
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-md-5">
                    <div class="footer-widget">
                        <h5 class="footer-widget__title">@lang('About')</h5>
                        <ul class="footer-links">
                            <li>
                                <a href="{{ route('home') }}">@lang('Home')</a>
                            </li>
                            <li>
                                <a href="{{ route('blog') }}">@lang('Blog')</a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}">@lang('Contact')</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-md-5">
                    <div class="footer-widget">
                        <h5 class="footer-widget__title">@lang('Policy')</h5>
                        <ul class="footer-links">
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
                <div class="col-lg-3 col-sm-6 col-md-5">
                    <div class="footer-widget">
                        <h5 class="footer-widget__title">@lang('Contact Info')</h5>
                        <ul class="footer-contact-list">
                            @foreach ($contactElements as $element)
                                <li class="d-flex">
                                    <div class="image-icon">
                                        <img src="{{ getImage('assets/images/frontend/contact_us/' . @$element->data_values->icon, '60x60') }}" alt="icon">
                                    </div>
                                    <span>
                                        {{ @$element->data_values->value }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom text-center py-3">
        <div class="container">
            <p class="text-white">@lang('Copyright') &copy; {{ date('Y') }} {{ __(@$footerContent->data_values->copyright) }} </p>
        </div>
    </div>
</footer>

@php
    $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
@endphp
@if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
    <div class="cookies-card text-center hide">
        <div class="cookies-card__icon bg--base">
            <i class="las la-cookie-bite"></i>
        </div>
        <p class="mt-4 cookies-card__content">{{ __($cookie->data_values->short_desc) }} <a href="{{ route('cookie.policy') }}" target="_blank" class="text--base">@lang('learn more')</a></p>
        <div class="cookies-card__btn mt-4">
            <button type="button" class="btn btn--base w-100 policy cookie-btn">@lang('Allow')</button>
        </div>
    </div>
@endif

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.cookie-btn').on('click', function(e) {
                $.ajax({
                    url: `{{ route('cookie.accept') }}`,
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response.success) {
                            notify('success', response.message);
                            $('.cookies-card').remove();
                        }
                    },
                });
            });
        })(jQuery);
    </script>
@endpush
