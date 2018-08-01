<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="{{URL::to('css/app.css')}}" rel="stylesheet" type="text/css">
        <!-- Styles -->
    </head>
    <body>
        <div  id="app">
            <router-view></router-view>
        </div>
    </body>
    <script src="{{URL::to('js/app.js')}}"></script>

</html>
