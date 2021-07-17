<?php


namespace App\Http\Controllers;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:3,1')->only([
            'sendResetEmail'
        ]);
    }

    public function emailForm()
    {
        return view('passwords.email_form');
    }

    public function sendResetEmail(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email|exists:users'
        ]);

        $user = User::where('email',$request->email)->first();
        if(!$user){
            session()->flash('danger','用户不存在');
            return redirect()->back()->withInput();
        }

        $token = hash_hmac('sha256',Str::random(40),config('app.key'));

        DB::table('passwords_reset')->insert([
           'email' => $user->email, 'token' => Hash::make($token), 'created_at' => Carbon::now()
        ]);

        Mail::send('emails.reset_password',compact('token'),function ($message) use ($user){
            /* @var Message $message */
            $message->to($user->email)->subject('忘记密码');
        });

        session()->flash('success','已发送重置邮件，请前往邮箱确认');
        return redirect()->back();
    }

    public function resetForm(Request $request)
    {
        $token = $request->token;

        return view('passwords.reset_form',compact('token'));
    }

    public function reset(Request $request)
    {
        $this->validate($request,[
           'email' => 'required|max:32',
           'password' => 'required|confirmed|min:6'
        ]);

        $user = User::where('email',$request->email)->first();

        if(is_null($user)){
            session()->flash('danger','用户不存在');
            return redirect()->back()->withInput();
        }

        $record = DB::table('passwords_reset')->where('email',$request->email)->orderByDesc('created_at')->first();

        if(is_null($record)){
            session()->flash('danger','未找到重置密码记录');
            return redirect()->back()->withInput();
        }

        if(Carbon::parse($record->created_at)->addMinutes(5)->isPast()){
            session()->flash('danger','链接已过期，请重新尝试');
            return redirect()->back()->withInput();
        }

        if(!Hash::check($request->token,$record->token)){
            session()->flash('danger','令牌错误');
            return redirect()->back()->withInput();
        }

        $user->password = bcrypt($request->password);
        $user->save();

        session()->flash('success','重置成功');
        return redirect()->route('login');
    }
}
