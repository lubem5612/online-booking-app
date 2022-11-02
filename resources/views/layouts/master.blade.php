<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Online Booking Platform">
    <meta name="description" content="online booking application for checking out books and checking in">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- CSRF Token -->
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">

    <title>Online Booking - @yield('title')</title>
    <link href="{{asset('favicon.ico')}}" rel="icon">
    <link href="{{asset('favicon.ico')}}" rel="apple-touch-icon">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    @yield('css')
</head>
<body>
@yield('content')
<script src="{{asset('js/app.js')}}" async defer></script>
<script defer async>
    window.Laravel = '<?php echo json_encode(['csrfToken'=> csrf_token()]); ?>';
</script>
</body>
</html>
