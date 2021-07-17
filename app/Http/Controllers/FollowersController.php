<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowersController extends Controller
{
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(10);
        $title = $user->name . '的粉丝';

        return view('followers.show_follow',compact('users','title'));
    }

    public function followings(User $user)
    {
        $users = $user->followings()->paginate(10);
        $title = $user->name . '关注的人';

        return view('followers.show_follow',compact('users','title'));
    }

    public function store(User $user)
    {
        $this->authorize('follow',$user);
        if(!Auth::user()->isFollowing($user->id)){
            Auth::user()->follow($user->id);
            session()->flash('success','关注成功！');
        }else{
            session()->flash('danger','您已关注过该用户');
        }

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        $this->authorize('follow',$user);
        if(Auth::user()->isFollowing($user->id)){
            Auth::user()->unfollow($user->id);
            session()->flash('success','取关成功！');
        }else{
            session()->flash('success','您未曾关注该用户！');
        }

        return redirect()->back();
    }
}
