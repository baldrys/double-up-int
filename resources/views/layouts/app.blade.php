<!<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Page Title</title>
    <base href="{{ URL::asset('/') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{url('css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{url('css/my-style.css')}}">
    <script src="{{url('js/jquery.js')}}"></script>
    <script src="{{url('js/requestsJquery.js')}}"></script>
</head>
<body>
    @include('layouts.nav')
    @yield('navbar')
    <div class="container my-4">
        @yield('content')
    </div>
    <script src="{{url('js/popper.js')}}"></script>
    <script src="{{url('js/bootstrap.min.js')}}"></script>
</body>
</html>