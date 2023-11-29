<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $general->sitename(__($pageTitle)) }}</title>
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        .btn--base {
            color: #fff;
            background-color: #{{ $general->base_color }};
        }
    </style>
</head>

<body>
    <section class="maintenance-page">
        <div class="container">
            <div class="row justify-content-center text-center mt-5">
                <div class="col-lg-10">
                    <img src="{{ getImage(getFilePath('maintenance') . '/' . $maintenance->data_values->image, getFileSize('maintenance')) }}">

                    <h2 class="my-3 text-danger">{{ __($maintenance->data_values->heading) }}</h2>
                    <div class="mb-1">
                        @php echo $maintenance->data_values->description @endphp
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
