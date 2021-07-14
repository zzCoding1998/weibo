<?php


namespace App\Http\Controllers;


class StaticPagesController extends Controller
{
    public function home()
    {
        return view('statics.home');
    }

    public function about()
    {
        return view('statics.about');
    }

    public function help()
    {
        return view('statics.help');
    }
}
