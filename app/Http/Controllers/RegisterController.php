<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\Department;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\QueryException;


//use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    //

    public function index(){
        $departments = Department::all();
        return view("Page.index");
    }
    //新增的view
    public function create(){
        $departments = Department::all();
        return view("Page.Account.register",['departments'=> $departments]);
    }
    //新增
    public function store(Request $request){
        $request->validate([
            'did' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|max:255|',
        ]);

        //$register = Register::create($request->all()); 全部傳
        $register = new register();
        $register->did      = $request->input('did');
        $register->name     = $request->input('name');
        $register->username = $request->input('username');
        $register->password = Hash::make($request->Password);
        $register->save();
        $request->session()->flash('success', '新增成功！');
        return redirect()->route('root');
    }
    //修改頁面
    public function edit($id){
        $user = User::with('department')->where('id',$id)->first();
        if (!$user) {
        // 沒找到使用者回報錯誤
        abort(404);
        }
        $dname = optional($user->department)->dname;//用找到的user到 關聯的dep去取dname
        $departments = Department::all();

        return view("Page.Account.edit",['user'=> $user,'dname'=>$dname,'departments'=> $departments]);
    }
    //修改程式
    public function update(Request $request , $register)
    {
        $edit= User::where('id', '=', $register)->first();
        $request->validate([
        'did' => 'string|max:255',
        'name' => 'string|max:255',
        'username' => 'string|max:255|unique:users,username,' .$edit->id, //排除自己以外不重複
        'password' => 'required|max:255|',
    ]);
        $edit->did      = $request->input('did');
        $edit->name     = $request->input('name');
        //$edit->username = $request->input('username');
        /*
        $username = User::where('username','=', $edit->username)->first();
        if ($username) {
            return request()
        }*/
        if ($request->input('username') !== $edit->username) {
            $edit->username = $request->input('username');
        }
        if ($request->has('password')) {
            $edit->password = Hash::make($request->input('password'));
        }//$register = User::create($request->all()); //全部傳
        //return $edit;
        $edit->update();
        $request->session()->flash('success', '修改成功！');
        return redirect()->route('AccountList');
    }


    //透過搜尋的列表

    public function search_show(Request $request){
        $query = Register::with('department');//呼叫Re model中的department關聯程式
            if ($request->has('did')) {
                $query->where('did', 'like', "%{$request->get('did')}%");
                }
            if ($request->has('name')) {
                $query->where('name', 'like', "%{$request->get('name')}%");
                }

        $searchs = $query->orderby('did','asc')->paginate(10);
        if ($searchs->isEmpty()) {
            $message = '查無資料';
        }else $message = NULL;
        $departments = Department::all();

        return view("Page.Account.search_account_list",[
            'searchs' => $searchs,
            'departments' => $departments,
            'message' => $message
            ]);

    }
    //test


    //前端帳號列表
    public function usertable(){
        $departments = Department::all();
        return view("Page.Account.datatable",['departments'=> $departments]);
    }
    //撈資料回丟掉前端帳號列表
    public function getData(Request $request){
        //$datas = DB::table('users')->select('*')->get();
        $datas =User::with('department')->select('id','username','name','did','created_at')->get();
        //丟回datatable
        return datatables()->of($datas)
        ->addColumn('dname', function($user){
            return $user->did ? $user->department->dname :'無部門';
        })
        ->editColumn('id', '{{$id}}')->editColumn('name', '{{$name}}')->editColumn('username', '{{$username}}')->editColumn('did','{{$did}}')->editColumn('time','{{$created_at}}')
        //->escapeColumns([])
        ->make(true);
    }

    //刪除
    public function destroy($id){
        //$id = User::where('id', $id)->first();
            $id= User::findOrFail($id);
            $id->delete();
            //return redirect()->route('AccountList')->with('success','成功刪除');
           if($id->delete()){
            return redirect()->route('AccountList')->with('success','成功刪除');
         // return response()->json(['success'=>true,'message'=>'已成功刪除']) ;
        } else{
            return redirect()->route('Account.edit',$id)->with('success','刪除失敗');
         // return response()->json(['success'=>false,'message'=> '錯誤'],404) ;
        }
    }
}
