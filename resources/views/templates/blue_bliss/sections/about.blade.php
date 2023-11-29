@php
    $content = getContent('about.content', true);
@endphp
@if ($content)
    <section class="about-section padding-top padding-bottom section-bg">
        <div class="container">
            <div class="row flex-wrap-reverse">
                <div class="col-lg-6">
                    <div class="section-header left-style margin-olpo text-left">
                        <h3 class="title">{{ __(@$content->data_values->heading) }}</h3>
                        <p>{{ __(@$content->data_values->subheading) }}</p>
                    </div>
                    <div class="about-content">
                        @php echo @$content->data_values->description; @endphp
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-thumb">
                        <img src="{{ getImage('assets/images/frontend/about/' . @$content->data_values->about_image, '600x400') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
