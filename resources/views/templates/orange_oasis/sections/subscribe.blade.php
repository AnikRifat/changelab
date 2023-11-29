@php
    $content = getContent('subscribe.content', true);
@endphp
<section class="subscribe-section bg--accent position-relative overflow-hidden">
    <div class="container">
        <div class="newsletter-section bg-3">
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-xl-7 col-lg-6">
                        <div class="newsletter-header">
                            <h3 class="title text-white mb-2">{{ __(@$content->data_values->heading) }}</h3>
                            <p class="section-desc text-white ms-0">{{ __(@$content->data_values->subheading) }}</p>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6">
                        <form action="" method="POST" id="newsletr-form">
                            @csrf
                            <div class="subscribe-form">
                                <div class="input-group">
                                    <input type="email" class="form--control form-control" placeholder="@lang('Enter Your Email')" name="email">
                                    <button type="submit" class="btn btn--base input-group-text border-0">@lang('Subscribe')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('script')
    <script>
        "use strict";
        (function($) {
            $("#newsletr-form").on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData($(this)[0]);
                $.ajax({
                    url: `{{ route('subscribe') }}`,
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        if (resp.success) {
                            notify('success', resp.message)
                        } else {
                            notify('error', resp.error || `@lang('Something went wrong')`)
                        }
                    },
                    error: function(e) {
                        notify(`@lang('Something went wrong')`)
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
