@php
    $content = getContent('trustpilot_review.content', true);
@endphp

@if ($content)
<div class="pt-80 pb-80 section-bg">
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-md-12">
                <div class="section-title">
                    <div class="section-title__wrapper">
                        <h2 class="section-title__title mb-1">{{ __(@$content->data_values->heading) }}</h2>
                        <p class="section-title__desc">{{ __(@$content->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @php echo $general->trustpilot_widget_code; @endphp
            </div>
        </div>

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
