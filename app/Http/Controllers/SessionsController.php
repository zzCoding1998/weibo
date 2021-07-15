<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest',[
            'only' => [
                'create','store'
            ]
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|max:128|exists:users',
            'password' => 'required|max:20|min:6'
        ]);

        if(Auth::attempt(['email' => $request->email,'password' => $request->password],$request->has('remember'))){
            session()->flash('success','欢迎回来！');
            return redirect()->intended();
        }else{
            session()->flash('danger','用户密码不正确');
            return redirect()->back()->withInput();
        }
    }

    public function destroy()
    {
        Auth::logout();

        session()->flash('success','您已退出！');

        return redirect()->route('login');
    }

}
