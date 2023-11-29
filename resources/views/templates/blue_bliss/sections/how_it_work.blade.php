@php
    $content = getContent('how_it_work.content', true);
    $elements = getContent('how_it_work.element', orderById: true);
@endphp
@if ($content)
    <section class="how-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="section-header">
                        <h3 class="title">{{ __(@$content->data_values->heading) }}</h3>
                        <p>{{ __(@$content->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center gy-4">
                @foreach ($elements as $element)
                    <div class="col-xl-4 col-sm-8 col-lg-7">
                        <div class="how-item">
                            <div class="how-thumb"> @php echo @$element->data_values->icon; @endphp </div>
                            <div class="how-content text-center">
                                <h5 class="title">{{ __(@$element->data_values->title) }}</h5>
                                <p class="desc">{{ __(@$element->data_values->subtitle) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
