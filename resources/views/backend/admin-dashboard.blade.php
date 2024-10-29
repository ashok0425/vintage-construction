<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DREBBA POS - Login</title>

    @include('backend.layouts.assets.css')
    @yield('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/helper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/main.css')}}">
    @stack('css')
</head>

<body class="bg-gradient-primary">

<div class="container mt-5">

    <div class="row">
        @foreach (\App\Models\Business::all() as $business)
        <div class="col-md-6">
            <div class="card bg-brand-secondary text-white">
                <div class="card-header">
                    <h4 class="card-title">{{ $business->name }}</h4>
                    </div>
                    <div class="card-body">

                        <div>
                            Business Name: {{$business->name}}
                            <br>
                            Business Email: {{$business->email}}
                            <br>
                            Business Phone: {{$business->phone}}
                        </div>

                    </div>
                    <div class="card-header border-top">
                        <a class="text-white d-block" target="_blank" href="{{url('admin/business-login',['id'=>$business->id])}}">Login to - {{ $business->name }} ➡</a>
                    </div>
            </div>
        </div>
        @endforeach

    </div>

</div>

<!-- Bootstrap core JavaScript-->
<script src="{{asset('backend/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Core plugin JavaScript-->
<script src="{{asset('backend/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

<!-- Custom scripts for all pages-->
<script src="{{asset('backend/js/script.js')}}"></script>
@include('backend.layouts.assets.js')
</body>

</html>

