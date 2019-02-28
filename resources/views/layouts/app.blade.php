<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendor/DataTables-1.10.19/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    @yield('css')
</head>
<body>
<div id="app">
    @include('layouts.navbar')
    <main class="py-4">
        @if(Session::get('message'))
            {!! Session::get('message') !!}
        @endif
        @yield('content')
    </main>
</div>
<!-- Scripts -->
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('/vendor/DataTables-1.10.19/js/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('/vendor/DataTables-1.10.19/js/jquery.dataTables.min.js') }}"></script>
@yield('js')
</body>
</html>
