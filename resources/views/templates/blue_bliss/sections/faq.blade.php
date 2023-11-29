@php
$content = getContent('faq.content',true);
$elements = getContent('faq.element',false);
@endphp
<section class="faq-section padding-top padding-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 d-lg-block d-none rtl">
                <img src="{{getImage('assets/images/frontend/faq/'.@$content->data_values->faq_image,'600x600')}}">
            </div>
            <div class="col-lg-7">
                <div class="section-header left-style">
                    <h3 class="title">{{__($content->data_values->heading)}}</h3>
                    <p>{{__(@$content->data_values->subheading)}}</p>
                </div>
                <div class="faq-wrapper mb--20">
                    @foreach($elements as $element)
                    <div class="faq-item {{$loop->first == 1 ? 'active open' : ''}}">
                        <div class="faq-title">
                            <h6 class="title">{{__(@$element->data_values->question)}}</h6>
                            <span class="right-icon"></span>
                        </div>
                        <div class="faq-content">{{__($element->data_values->answer)}}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
