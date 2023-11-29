<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $general->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ $general->base_color }}">
    @stack('style-lib')
    @stack('style')
</head>

<body>
    <div class="preloader">
        <div class="preloader__img">
            <img src="{{ siteFavicon() }}" alt="image">
        </div>
    </div>

    @if (!request()->routeIs('user.login') && !request()->routeIs('user.register'))
        @include($activeTemplate . 'partials.notice_bar')
    @endif

    @yield('panel')

    @if (!request()->routeIs('user.login') && !request()->routeIs('user.register'))
        @include($activeTemplate . 'partials.footer')
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @include('partials.plugins')

    @include('partials.notify')

    @stack('script-lib')
    @stack('script')

    <script>
        (function($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{ route('home') }}/change/" + $(this).val();
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });
            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);


            $.each($('input, select, textarea'), function(index, element) {
                $(element).siblings('label').attr('for', $(element).attr('name'));
                if (!$(element).attr('id')) {
                    $(element).attr('id', $(element).attr('name'))
                }
            });

            $.each($('input, select, textarea'), function(i, element) {
                if (element.hasAttribute('required')) {
                    $(element).closest('.form-group').find('label').addClass('required');
                }
            });
            $('.captcha div').css({
                "background-color": "#fff",
                "border": "1px solid #f1f1f1",
                "border-radius": "5px",
                "font-size": "24px",
                "letter-spacing": "16px",
            });
        })(jQuery);
    </script>
</body>
</html>
