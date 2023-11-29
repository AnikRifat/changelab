@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="padding-top padding-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="card-title">{{ __($pageTitle) }}</h5>
                    </div>
                    <div class="card-body">
                        @php
                            echo $cookie->data_values->description
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
