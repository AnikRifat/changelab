@php
    $content = getContent('trustpilot_review.content', true);
@endphp

@if ($content)
    <div class="how-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="section-header">
                        <h3 class="title">{{ __(@$content->data_values->heading) }}</h3>
                        <p>{{ __(@$content->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
            @php echo $general->trustpilot_widget_code; @endphp
        </div>
    </div>
@endif

@push('script')
    <script>
        "use strict";
        (function ($) {
            setTimeout(() => {
                $('body').find(".commonninja-ribbon-link").remove();
            },1000);
        })(jQuery);

    </script>
@endpush

@push('style')
    <style>
        .iRKbXZ {
            max-width: 100% !important;
        }

        .ejKmWB .review-text p{
            font-family: "Roboto", sans-serif;
        }
    </style>
@endpush
