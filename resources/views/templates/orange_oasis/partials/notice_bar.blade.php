@php
    $content = getContent('notice_bar.content', true);
@endphp
@if ($content && $general->show_notice_bar)
    <div class="top-notice py-2 bg--accent d-flex" id="top-notice">
        <div class="container">
            <p class="top-notice-text text-white">{{ __(@$content->data_values->title) }}</p>
        </div>
        <div class="notice-close px-3 fs--18px text-white"><i class="las la-times"></i></div>
    </div>
@endif
