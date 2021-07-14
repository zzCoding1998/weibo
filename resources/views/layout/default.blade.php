<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title','Weibo App')</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>
    <body>

        @include('layout._header')

        <div class="container">
            @include('shared._message')

            @yield('content')

            @include('layout._footer')
        </div>
    </body>
</html>
