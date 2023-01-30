<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
class UserController extends Controller
{
    var $fields=array();
    public $companyInfo=array();
    public $query='';
    public $imageName='';

    public function __construct()
    {
        $this->companyInfo=CompanyInfo::first();
    }

    public function index(Request $request)
    {
        //fetch all users from DB
       if ($request->ajax()) {
            $data = User::all();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                $actionBtn = '
                    <div class="btn-group text-center">
			<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Menu
			</button>
			<ul class="dropdown-menu dropdown-menu-right" >
				<li><button type="button" id="'.$data->id.'"  data-prefix="User" class="w-100 mb-1 text-center btn btn-info btn-sm update"><i class="fas fa-edit"></i> Update</button></li>
                <li><button type="button"  id="'.$data->id.'"  data-prefix="user" class="w-100 btn mb-1 '.(($data->user_status=="active")?'btn-warning':' btn-success').' btn-sm status" data-status="'.$data->user_status.'">'.(($data->user_status=="active")?"Disable":"Enable").'</button></li>
                <li><button type="button"  id="'.$data->id.'"  class="w-100 btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> Delete</button></li>
			</ul>
			</div>';
                    return $actionBtn;
                })
                ->editColumn('created_at', function ($data) {
                return $data->created_at->format('Y/m/d');
                 })
                ->editColumn('profile_image', '<img src="{{config("app.storage_url").$profile_image}}" class="img img-thumbnail " width="75" >')
                ->editColumn('user_status', function ($data) {
                    if($data->user_status == 'active')
                        $status = '<span  class="badge badge-success btn-sm">'.$data->user_status.'</span>';
                    else
                        $status = '<span  class="badge badge-danger btn-sm">'.$data->user_status.'</span>';
                        return $status;
                     })
                ->make(true);
        }
        $info=$this->companyInfo;
        $page='user';
        return view('user',compact('info','page' ) );
    }


    public function create()
    {
        $user=auth()->user();
        $info=$this->companyInfo;
        return view('profile',compact('info','user'));
    }

    public function store(Request $request)
    {
       $this->validate($request, [
            'username' => ['required','max:255','unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password'=>['required','min:6','max:72'],
        ]);
        if($request->hasFile('profile_image')){
            $this->fields['profile_image']=basename($request->file('profile_image')->store('public/images'));
            $this->imageName=config('app.storage_url').$this->fields['profile_image'];
        }
        User::create(array_merge($request->all(), $this->fields));
       return response()->json(array('response'=>'<div class="alert alert-success">The user data was created!</div>','image'=>$this->imageName));
    }

    public function update(Request $request, User $user)
    {
        if(!$request->has('user_status')){
            $this->validate($request, [
                'username' => ['required', 'string', 'max:255',Rule::unique('users')->ignore($user)],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
                'current_password' => ['sometimes','exclude_if:password,null','required_with:password','password:web'],
                'password' => ['nullable','min:6','different:current_password'],
                'password_confirmation' => ['sometimes','exclude_if:password,null','required_with:password','same:password'],
                 ]);
            if($request->hasFile('profile_image')){
                $this->fields['profile_image']=basename($request->file('profile_image')->store('public/images'));
                $this->imageName=config('app.storage_url').$this->fields['profile_image'];
            }
        }
        $user->update(array_merge(array_filter($request->all()), $this->fields));
        return response()->json(array('response'=>'<div class="alert alert-success">The user data was updated!</div>','image'=>$this->imageName));
    }


    public function edit(User $user)
    {
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(array('response'=>'<div class="alert alert-success">The user was deleted!</div>'));
    }
}
