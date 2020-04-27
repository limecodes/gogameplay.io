<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GoGamePlay :: Get Game</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
        
    </head>
    <body>
        <div>
            <p>Game Data {{ $name }}</p>
            <div id="app" class="content" data-uid="{{ $uid }}"></div>
        </div>
        <script type="text/javascript" src="/js/app.js"></script>
    </body>
</html>
