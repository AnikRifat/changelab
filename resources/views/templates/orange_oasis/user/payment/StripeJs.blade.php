@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="text-center">@lang('Stripe Storefront')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ $data->url }}" method="{{ $data->method }}">
                            <h6> @lang('You have to pay') {{ showAmount($deposit->final_amo) }} {{ __($deposit->method_currency) }}</h6>
                            <script src="{{ @$data->src }}" class="stripe-button" @foreach ($data->val as $key => $value)
                            data-{{ $key }}="{{ $value }}" @endforeach></script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-lib')
    <script src="https://js.stripe.com/v3/"></script>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            $('button[type="submit"]').addClass("btn btn--base w-100 mt-3");
            $('button[type="submit"]').removeClass("stripe-button-el");
            $('button[type="submit"]').text("Pay Now");
        })(jQuery);
    </script>
@endpush
