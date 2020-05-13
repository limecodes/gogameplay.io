<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GoGamePlay.io :: Get Free Games</title>

        <!-- Styles -->
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

        <!-- Preload Images -->
        <!-- TODO: I might want to put the static assets endpoint in a .env variable -->
        <link rel="preload" href="{{ env('MIX_ASSET_URL') }}/images/appstore.png" as="image">
        <link rel="preload" href="{{ env('MIX_ASSET_URL') }}/images/googleplay.png" as="image">
        <link rel="preload" href="{{ env('MIX_ASSET_URL') }}/images/logo.png" as="image">
    </head>
    <body id="homepage">
        <header>
            <h1><img src="/images/logo.png" alt="GoGamePlay.io" /></h1>
        </header>
        <main id="main" role="main">
            <div class="container-fluid">
                @foreach ($games as $game)
                <section>
                    <div class="row screenshot">
                        <div class="col-sm-12">
                            <img src="{{ $game->image }}" />
                        </div>
                    </div>

                    <div class="row getitpiad">
                        <div class="col-6 download"><span class="badge badge-primary">${{ $game->price }}</span>
                            <p><img src="{{ env('MIX_ASSET_URL') }}/images/appstore.png" /></p>

                        </div>

                        <div class="col-6 download">
                            <span class="badge badge-primary">${{ $game->price }}</span>
                            <p><img src="{{ env('MIX_ASSET_URL') }}/images/googleplay.png" /></p>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a href="/game/{{ $game->slug }}" class="btn btn-success tyl">
                                <span class="badge badge-light"><span class="strike">${{ $game->price }}</span> => $0.00</span><br/>Try your luck, Get it Free >
                            </a>
                        </div>
                    </div>
                </section>
                @endforeach
            </div>
        </main>
    </body>
</html>
