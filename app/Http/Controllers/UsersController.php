<?php


namespace App\Http\Controllers;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['show','create','store','index','confirmSignupEmail']
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
        $statuses = $user->statuses()->orderByDesc('created_at')->paginate(10);
        return view('users.show', compact('user','statuses'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = DB::transaction(function () use ($request){
            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => bcrypt($request->password)
            ]);

            $this->sendSignupConfirmEmail($user);

            return $user;
        });

        session()->flash('success','注册成功,请前往邮箱进行认证激活');

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

    public function confirmSignupEmail($token)
    {
        $user = User::where('activation_code',$token)->first();

        if(!$user){
            session()->flash('danger','激活账户失败，无效的token');
            return redirect()->route('home');
        }

        $user->is_active = true;
        $user->activation_code = null;
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->save();

        Auth::login($user);

        session()->flash('success','激活成功，欢迎回来！');
        return redirect()->route('home');
    }

    protected function sendSignupConfirmEmail(User $user)
    {
        $view = 'emails.signup_confirm';
        $data = compact('user');
        $from = 'zhangsan@qq.com';
        $name = 'zhangsan';
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";
        Mail::send($view,$data,function ($message) use ($from,$name,$to,$subject){
            /* @var Message $message */
            $message->from($from,$name)->to($to)->subject($subject);
        });
    }
}
