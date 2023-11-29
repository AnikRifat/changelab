@extends($activeTemplate.'layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card custom--card">
                <div class="card-body">
                    <form action="{{route('user.kyc.submit')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <x-viser-form identifier="act" identifierValue="kyc" />
                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    "use strict";
    window.addEventListener('DOMContentLoaded', () => {
        Array.from(document.querySelectorAll(`input[type=checkbox]`)).forEach(checkbox => {
            if (checkbox.hasAttribute('id')) {
                checkbox.nextSibling.nextElementSibling.setAttribute('for', checkbox.id);
            }
        })
    });
</script>
@endpush
