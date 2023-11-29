@php
    $content = getContent('about.content', true);
    $elements = getContent('about.element');
@endphp

@if ($content)
    <div class="about-section pt-80 pb-80">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 pe-lg-5">
                    <div class="section-title style-two">
                        <div class="section-title__wrapper">
                            <h2 class="section-title__title mb-1">{{ __(@$content->data_values->heading) }}</h2>
                            <p class="section-title__desc">{{ __(@$content->data_values->subheading) }}</p>
                        </div>
                    </div>
                    <div class="about-content">
                        <div> @php echo @$content->data_values->description @endphp </div>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="about-thumb">
                        <img src="{{ getImage('assets/images/frontend/about/' . @$content->data_values->about_image, '400x360') }}" class="mw-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
