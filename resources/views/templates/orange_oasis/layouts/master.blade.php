@extends($activeTemplate.'layouts.app')
@section('panel')

@stack('fbComment')

<div class="overlay"></div>
<a href="javascript::void(0)" class="scrollToTop"><i class="las la-chevron-up"></i></a>

@include($activeTemplate.'partials.auth_header')

@include($activeTemplate.'partials.breadcumb')
<div class="pt-80 pb-80 bg--light full-display">
    @yield('content')
</div>
@endsection
