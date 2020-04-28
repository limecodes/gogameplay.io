<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GoGamePlay :: Get Game</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
        <link rel="preload" href="http://static.offers.gogameplay.io/images/android.png" as="image">
        <link rel="preload" href="http://static.offers.gogameplay.io/images/ios.png" as="image">
        <link rel="preload" href="{{ $image }}" as="image">
        
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
            <!-- TODO: Before merge or upload, change device to be variable -->
            <div id="app" class="content" data-uid="{{ $uid }}" data-device="ios" data-connection="{{ $connection }}" data-carrier="{{ $carrier }}">
                <div class="container"><div class="row justify-content-center" style="margin-top: 1rem;"><div class="col-12"><div class="card"><div class="card-header" style="text-align: center;"><p style="margin-bottom: 0px;">1. Select your device/platform</p><small style="margin-bottom: 0px;">Tap on your platform</small></div><div class="card-body"><div class="row"><button class="btn btn-link col-6" style="padding: 1rem;"><img src="http://static.offers.gogameplay.io/images/android.png" style="width: 50%;"></button><button class="btn btn-link col-6" style="padding: 1rem;"><img src="http://static.offers.gogameplay.io/images/ios.png" style="width: 50%;"></button></div></div><div class="card-footer"><div></div></div></div></div></div></div>
            </div>
        </div>
        <script type="text/javascript" src="/js/app.js"></script>
    </body>
</html>
