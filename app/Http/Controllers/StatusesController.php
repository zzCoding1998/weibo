<?php


namespace App\Http\Controllers;


use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
           'content' => 'max:256'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request->input('content'),
        ]);

        session()->flash('success','发布成功');
        return redirect()->back();
    }

    public function destroy(Status $status)
    {
        $this->authorize('delete',$status);

        $status->delete();

        session()->flash('删除成功');
        return redirect()->back();
    }

}
