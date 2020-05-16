<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GoGamePlay.io :: Get Free Games</title>

        <!-- Styles -->
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

        <!-- Preload Images -->
        <link rel="preload" href="{{ asset('/images/appstore.png') }}" as="image">
        <link rel="preload" href="{{ asset('/images/googleplay.png') }}" as="image">
        <link rel="preload" href="{{ asset('/images/logo.png') }}" as="image">
    </head>
    <body>
        @yield('content')
    </body>
</html>
