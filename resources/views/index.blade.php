@extends('layouts.app')

@section('content')
<section id="homepage">
    <header>
        <h1><img src="{{ asset('/images/logo.png') }}" alt="GoGamePlay.io" /></h1>
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
                    <div class="col-6 download">
                        <span class="badge badge-primary">${{ $game->price }}</span>
                        <p><img src="{{ asset('/images/appstore.png') }}" /></p>
                    </div>

                    <div class="col-6 download">
                        <span class="badge badge-primary">${{ $game->price }}</span>
                        <p><img src="{{ asset('/images/googleplay.png') }}" /></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('game', ['game' => $game->slug], false) }}" class="btn btn-success tyl">
                            <span class="badge badge-light">
                                <span class="strike">${{ $game->price }}</span> => $0.00
                            </span>
                            <br/>Try your luck, Get it Free >
                        </a>
                    </div>
                </div>
            </section>
            @endforeach
        </div>
    </main>
</section>
@endsection
