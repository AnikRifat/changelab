@php
$content  = getContent('feature.content',true);
$elements = getContent('feature.element');
@endphp
@if ($content)
<section class="feature-section padding-top padding-bottom section-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-header">
                    <h3 class="title">{{__(@$content->data_values->heading)}}</h3>
                    <p> {{__(@$content->data_values->subheading)}}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-30-none">
            @foreach($elements as $element)
            <div class="col-md-6 col-sm-10 col-xl-4">
                <div class="feature-item">
                    <div class="feature-thumb">
                        @php echo $element->data_values->feature_icon; @endphp
                    </div>
                    <div class="feature-content">
                        <h5 class="title">{{__(@$element->data_values->title)}}</h5>
                        <p>{{__(@$element->data_values->description)}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
