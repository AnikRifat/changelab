@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="padding-top padding-bottom section-bg">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper bg-white">
                <div class="verification-area">
                    <form action="{{ route('user.go2fa.verify') }}" method="POST" class="submit-form">
                        @csrf
                        @include('partials.verification_code')
                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        label {
            margin-bottom: 2px;
        }
    </style>
@endpush
