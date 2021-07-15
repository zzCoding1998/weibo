<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title','Weibo App')</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ @mix('js/app.js') }}" ></script>
    </head>
    <body>

        @include('layouts._header')

        <div class="container">
            @include('shared._message')

            @yield('content')

            @include('layouts._footer')
        </div>
    </body>
</html>
