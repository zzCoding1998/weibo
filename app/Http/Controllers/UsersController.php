<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['show','create','store','index']
        ]);

        $this->middleware('guest',[
           'only' => [
               'create','store'
           ]
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => $request->password
        ]);

        //登录
        Auth::login($user);

        session()->flash('success','欢迎，您将在这里开启一段新的旅程');

        return redirect()->route('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(User $user,Request $request)
    {
        $this->authorize('update',$user);
        $this->validate($request,[
            'name' => [
                'required',
                'max:128',
                'min:2',
                Rule::unique('users')->ignore($user->id)
            ],
            'password'=>'nullable|confirmed|min:6|max:20'
        ]);

        $user->name = $request->name;
        if($request->password){
            $user->password = bcrypt($request->password);
        }
        $user->save();

        session()->flash('success','修改个人信息成功！');

        return redirect()->route('users.show',compact('user'));
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy',$user);

        $user->delete();

        session()->flash('success','删除成功！');

        return redirect()->back();
    }
}
