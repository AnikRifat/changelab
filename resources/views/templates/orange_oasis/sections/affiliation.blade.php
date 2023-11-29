@php
    $affiliate = getContent('affiliation.content', true);
    $elements = getContent('affiliation.element', false, null, true);
@endphp

<section class="pt-80 pb-80 affiliation section-bg">
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-md-8">
                <div class="section-title">
                    <div class="section-title__wrapper">
                        <h2 class="section-title__title mb-1">{{ __(@$affiliate->data_values->heading) }}</h2>
                        <p class="section-title__desc">{{ __(@$affiliate->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4 justify-content-center">
            @forelse ($elements as $element)
                <div class="@if ($loop->odd && $loop->last) col-lg-12 @else col-lg-6 @endif">
                    <div class="affiliate-item">
                        <div class="affiliate-item__left">
                            <h6 class="affiliate-item__subtitle">@lang('LEVEL') {{ @$element->data_values->level }}</h6>
                            <h3 class="affiliate-item__title">{{ @$element->data_values->commission }}%</h3>
                        </div>
                        <div class="affiliate-item__right">
                            <p class="affiliate-item__desc">
                                {{ __(@$element->data_values->description) }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-lg-12 text-center">
                    {{ __($emptyMessage) }}
                </div>
            @endforelse
        </div>
    </div>
</section>
