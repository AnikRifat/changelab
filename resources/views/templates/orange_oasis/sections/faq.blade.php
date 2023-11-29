@php
    $elements = getContent('faq.element');
    $content = getContent('faq.content', true);
@endphp
@if ($elements->count())
    <div class="pt-80 pb-80">
        <div class="container">
            <div class="row justify-content-center mb-3">
                <div class="col-md-12">
                    <div class="section-title">
                        <div class="section-title__wrapper">
                            <h2 class="section-title__title mb-1">{{ __(@$content->data_values->heading) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gy-4 justify-content-center">
                @foreach ($elements as $element)
                    <div class="col-lg-6 pe-lg-4">
                        <div class="d-flex gap-3 faq-item-wrapper">
                            <div class="faq-icon"><i class="fas fa-question"></i></div>
                            <div class="faq-item">
                                <div class="faq-item__title">
                                    <h6 class="title">{{ __(@$element->data_values->question) }}</h6>
                                </div>
                                <div class="faq-item__content">
                                    <div class="py-3">{{ __(@$element->data_values->answer) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
