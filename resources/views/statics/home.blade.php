@extends('layouts.default')

@section('title','首页')

@section('content')
    <div class="jumbotron">
        <h1>Hello Laravel</h1>
        <p class="lead">
            你现在所看到的是<a href="{{ route('home') }}">Weibo App</a>的实例教程首页
        </p>
        <p>
            一切从这里开始
        </p>
        <p>
            <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">现在注册</a>
        </p>
    </div>
@endsection
