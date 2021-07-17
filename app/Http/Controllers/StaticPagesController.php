<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

class StaticPagesController extends Controller
{
    public function home()
    {
        $feed_items = [];
        if(Auth::check()){
            $feed_items = Auth::user()->statuses()->orderByDesc('created_at')->paginate(10);
        }
        return view('statics.home', compact('feed_items'));
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
