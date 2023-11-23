<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Validator;
use DB;
use Session;

class StaffController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:akun-staff-read|akun-staff-create|akun-staff-edit|akun-staff-delete', ['only' => ['index','show']]);
        $this->middleware('permission:akun-staff-create', ['only' => ['create','store']]);
        $this->middleware('permission:akun-staff-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:akun-staff-delete', ['only' => ['destroy']]);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $staffs = User::orderBy('created_at','asc')->paginate(10);
        $data = compact('staffs');
        return view('accounts.staffs.index')->with($data);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $roles = Role::orderBy('created_at', 'DESC')->get();

        $data = compact('roles');
        return view('accounts.staffs.create')->with($data);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $valid = [
            'name'=> 'required|string|max:255',
            'username'=> 'required|string|max:255|unique:'.User::class,
            'email'=> 'required|string|email|max:255|unique:'.User::class,
            'roles'=> 'required',
            'password'=> 'required|confirmed',
        ];
        
        $validate = Validator::make($request->all(),$valid);
        if($validate->fails()){
            return back()
                ->withErrors($validate)
                ->withInput();
        }else{
            DB::beginTransaction();
            try{
                $staff = new User();
                $staff->name = $request['name'];
                $staff->username = $request['username'];
                $staff->email = $request['email'];
                $staff->password = Hash::make($request['password']);

                $staff->save();

                $staff->assignRole($request->input('roles'));
            }catch(Exception $e){
                DB::rollback();
                Session::flash('error', 'Failed create personnel because system error');
                return back();
            }
            DB::commit();
            Session::flash('success', 'Success create personnel');
            return redirect()->route('personnels.index');
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\contact  $group
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $asd = User::findOrFail($id);
        $users = User::where('role', 1)->orWhere('role',2)->get();
        $data = compact('asd','users');
        return view('accounts.staffs.index')->with($data);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Group  $group
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $staff = User::findOrFail($id);
        $roles = Role::all();
        $staffRole = $staff->roles->pluck('name')->all();
        
        $data = compact('staff', 'roles', 'staffRole');
        return view('accounts.staffs.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $valid = [
            'name'=> 'required|string|max:255',
            'username'=> 'required|string|max:255',
            'email'=> 'required|string|email|max:255',
            'roles' => 'required',
        ];
        
        $validate = Validator::make($request->all(),$valid);
        if($validate->fails()){
            return back()
                ->withErrors($validate)
                ->withInput();
        }else{
            DB::beginTransaction();
            try{
                $staff = User::find($id);
                $staff->name = $request['name'];
                $staff->username = $request['username'];
                $staff->email = $request['email'];

                if($request->file('password') != "") {
                    $staff->password = $request['password'];
                }

                $staff->update();

                DB::table('model_has_roles')->where('model_id',$id)->delete();
    
                $staff->assignRole($request->input('roles'));
            }catch(Exception $e){
                DB::rollback();
                Session::flash('error', 'Failed update personnel because system error');
                return back();
            }
            DB::commit();
            Session::flash('success', 'Success update personnel');
            return redirect()->route('personnels.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::find($id);
        $data->delete();

        Session::flash('success', 'Success delete personnel'.$data->name);
        return redirect()->route('personnels.index');
    }
}