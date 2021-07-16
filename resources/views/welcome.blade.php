<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>D-Restaurant</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {

                background-image: url("{{asset('template/backgroung.png')}}");
                width: auto;
                height: auto;

                color: #d5d5d8;
                font-family: 'Nunito', sans-serif;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #d5d5d8;
                padding: 0 25px;
                font-size: 15px;
                font-weight: 800;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    D-Restaurant <br> 
                </div>
                <font size="5 px">By  <b>Star Technologie S.A.R.L</b></font>
                <br>
                <br>

                <div class="links">
                    <a href="{{url('/administrateur')}}">Adminstrateur</a>
                    <a href="{{url('/serveur/home')}}">Serveur</a>
                    <a href="{{url('/caisse')}}">Caissier</a>
                    <a href="{{url('/cuisine')}}">Cuisinier</a>
                    
                </div>
            </div>
        </div>
    </body>
</html>
