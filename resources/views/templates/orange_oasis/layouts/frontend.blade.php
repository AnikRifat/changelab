@extends($activeTemplate . 'layouts.app')
@section('panel')
    @stack('fbComment')

    <div class="overlay"></div>
    <a href="javascript::void(0)" class="scrollToTop"><i class="las la-chevron-up"></i></a>

    @include($activeTemplate . 'partials.header')
    @includeWhen(!request()->routeIs('home'), $activeTemplate . 'partials.breadcumb')
    @yield('content')
@endsection
