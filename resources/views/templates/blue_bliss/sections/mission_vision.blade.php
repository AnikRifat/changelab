@php
    $elements = getContent('mission_vision.element');
@endphp
<section class="overview-section ">
    @foreach ($elements as $element)
        <div class="overview-item section-bg">
            <div class="container mw-lg-100 p-0">
                <div class="row m-0 {{ $loop->even ? 'flex-row-reverse' : '' }}">
                    <div class="col-lg-6 p-lg-0">
                        <div class="overview-contnent padding-top">
                            <div class="content">
                                <div class="section-header left-style margin-olpo text-left">
                                    <h3 class="title">{{ __(@$element->data_values->heading) }}</h3>
                                    <p> {{ __(@$element->data_values->subheading) }}</p>
                                </div>
                                @php echo $element->data_values->description; @endphp
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 p-lg-0 bg_img" data-background="{{ getImage('assets/images/frontend/mission_vision/' . $element->data_values->image, '1000x675YsmentContro') }}">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</section>
