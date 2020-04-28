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
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <img src="{{ $image }}" />
                </div>
                <div class="col-9">
                    <h4>{{ $title }}</h4>
                    <p><span class="badge badge-warning strike">${{ $price }}</span> => <span class="badge badge-success">Free</span></p>
                </div>
            </div>
        </div>
        <div>
            <!-- <div id="app" class="content" data-uid="{{ $uid }}" data-device="{{ $device }}" data-connection="{{ $connection }}" data-carrier="{{ $carrier }}"></div> -->
            <div id="app" class="content" data-uid="{{ $uid }}" data-device="android" data-connection="{{ $connection }}" data-carrier="{{ $carrier }}"></div>
        </div>
        <script type="text/javascript" src="/js/app.js"></script>
    </body>
</html>
