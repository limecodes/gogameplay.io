<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GoGamePlay.io :: Get Free Games</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">

        <!-- Preload Images -->
        <!-- TODO: I might want to put the static assets endpoint in a .env variable -->
        <link rel="preload" href="https://s3.amazonaws.com/staging.gogameplay.io/images/appstore.png" as="image">
        <link rel="preload" href="https://s3.amazonaws.com/staging.gogameplay.io/images/googleplay.png" as="image">
    </head>
    <body class="homepage">
        <header>
            <h1>GoGamePlay.io</h1>
        </header>
        <main id="main" role="main">
            <div class="container-fluid">
                <div class="row screenshot">
                    <div class="col-sm-12">
                        <img src="https://s3.amazonaws.com/static.offers.gogameplay.io/images/overdrivecity.jpg" />
                    </div>
                </div>

                <div class="row getitpiad">
                    <div class="col-6 download"><span class="badge badge-primary">$1.99</span>
                        <p><img src="https://s3.amazonaws.com/staging.gogameplay.io/images/appstore.png" /></p>
                        
                    </div>

                    <div class="col-6 download">
                        <span class="badge badge-primary">$1.99</span>
                        <p><img src="https://s3.amazonaws.com/staging.gogameplay.io/images/googleplay.png" /></p>
                        
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <a href="/game/example" class="btn btn-success tyl">
                            <span class="badge badge-light"><span class="strike">$1.99</span> => $0.00</span><br/>Try your luck, Get it Free >
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
