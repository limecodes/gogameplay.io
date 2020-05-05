<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GoGamePlay.io :: Get Free Games</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
        <!-- All of this should be in the stylesheet in SASS -->
        <style>
            img {
                width: 100%;
            }

            .download, p {
                text-align: center;
            }           

            .tyl {
                width: 100%;
            }           

            .strike {
                text-decoration: line-through;
            }           

            header {
                height: 60px;
                background-color: black;
                text-align: center;
                color: white;
            }           

            main {
                margin-top: 1rem;
            }           

            .screenshot {
                margin-bottom: 0.5rem;
            }           

            .download p, .download .badge {
                margin-bottom: 0.5rem;
            }           

            .getitpiad {
                margin-bottom: 0.5rem;
            }
        </style>
        
    </head>
    <body class="homepage">
        <header>
            <h1>GoGamePlay.io</h1>
        </header>
        <main id="main" role="main">
            <div class="container-fluid">
                <div class="row screenshot">
                    <div class="col-sm-12">
                        <img src="https://s3.amazonaws.com/staging.gogameplay.io/images/overdrivecity.png" />
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
