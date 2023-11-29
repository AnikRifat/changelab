@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    @php echo $policy->data_values->description ; @endphp
                </div>
            </div>
        </div>
    </div>
@endsection
