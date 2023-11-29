@php
    $customCaptcha = loadCustomCaptcha();
    $googleCaptcha = loadReCaptcha();
@endphp

@if ($googleCaptcha)
    <div class="mb-3">
        @php echo $googleCaptcha @endphp
    </div>
@endif

@if ($customCaptcha)
    <div class="form-group customCaptcha">
        <label class="form-label">@lang('Captcha')</label>

        <div class="d-flex flex-wrap justify-content-between align-items-end gap-25px">
            <div class="captcha ">
                @php echo $customCaptcha @endphp
            </div>
            <div class="flex-grow-1 captchaInput">
                <input type="text" name="captcha" class="form-control form--control" required>
            </div>
        </div>
    </div>
@endif

@if ($googleCaptcha)
    @push('script')
        <script>
            (function($) {
                "use strict"
                $('.verify-gcaptcha').on('submit', function() {
                    var response = grecaptcha.getResponse();
                    if (response.length == 0) {
                        document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang('Captcha field is required.')</span>';
                        return false;
                    }
                    return true;
                });


            })(jQuery);
        </script>
    @endpush
@endif

@push('style')
    <style>
        .gap-25px {
            gap: 25px;
        }
    </style>
@endpush
