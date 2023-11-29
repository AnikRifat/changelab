@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $banner = getContent('banner.content', true);
    @endphp
    <section class="banner-section bg_fixed bg_img banner-overlay" data-background="{{ getImage('assets/images/frontend/banner/' . @$banner->data_values->background_image, '1200x685') }}">
        <div class="container">
            <div class="banner-content row justify-content-between">
                <div class="col-lg-6">
                    <div class="content">
                        <h2 class="title text-white">{{ __(@$banner->data_values->heading) }}</h2>
                        <p>{{ __(@$banner->data_values->subheading) }}</p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="currency-converter">
                        @include($activeTemplate . 'partials.exchange_form')
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($sections && $sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
