@php
    $content = getContent('payment_gateway.content', true);
    $elements = getContent('payment_gateway.element');
@endphp

@if ($content)
    <div class="brand-section py-4">
        <div class="container">
            <div class="brand-slider">
                @foreach ($elements as $element)
                    <div class="single-slide">
                        <div class="brand-item">
                            <img src="{{ getImage('assets/images/frontend/payment_gateway/' . @$element->data_values->gateway_image, '100x100') }}" alt="icon">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
