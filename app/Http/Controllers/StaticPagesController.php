<?php


namespace App\Http\Controllers;


use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class StaticPagesController extends Controller
{
    public function home()
    {
        $feed_items = [];
        if(Auth::check()){
            $user_ids = Auth::user()->followings->pluck('id')->toArray();
            array_push($user_ids,Auth::id());
            $feed_items = Status::whereIn('user_id',$user_ids)
                ->orderByDesc('created_at')
                ->with('user')
                ->paginate(10);
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
