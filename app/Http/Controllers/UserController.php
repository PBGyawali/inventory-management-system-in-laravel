<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Events\UserDeactivated;

class UserController extends Controller
{
    public $fields=[];
    public $companyInfo=[];
    public $imageName;

    public function __construct(Request $request)
    {
        if (!$request->ajax()) {
            $this->companyInfo=CompanyInfo::first();
        }
    }

    public function index(Request $request)
    {
        //fetch all users from DB
       if ($request->ajax()) {
            //do not send data of master users to prevent them from getting changed
            $data = User::all()->reject(function (User $user) {
                return auth()->user()->is_master()?null:$user->user_type== 'master';
            });
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    // primary key of the row
                    $id=$data->getKey();
                    // status of the row
                    $status=$data->user_status;
                    // data to display on modal, tables
                    $prefix="user";
                    // message to display on change status button
                    $statusbutton=$status=="active"?"Disable":"Enable";
                    // button class of change status button
                    $status_class=$status=="active"?"warning":"success";
                    // optional button to display
                    $buttons=['delete','reset'];
                    $actionBtn = view('control-buttons',compact('buttons','id','status','prefix','statusbutton','status_class'))->render();
                    return $actionBtn;
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at->format('Y/m/d');
                 })
                ->editColumn('user_status', function ($data) {
                    $status =$data->user_status;
                    $class=$status == 'active'?'success':'danger';
                    //render status with css from view
                    return view('badge',compact('status','class'))->render();
                    })
                ->make(true);
        }
        
        return view('user');
    }


    public function create()
    {
        $user=auth()->user();
        $info=$this->companyInfo;
        $name = $user->username;
        $email = $user->email;
        $username=$user->username;
        $profile_image=$user->profile_image;
        return view('profile',compact('info','user','name','email','profile_image','username'));
    }

    public function store(Request $request)
    {
       $this->validate($request, [
            'username' => ['required','max:255','unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password'=>['required','min:6','max:72'],
        ]);

        // If the request contains a file with key 'profile_image',
        //  store the file in the 'public/images' directory and update the 'profile_image' field.
        if($request->hasFile('profile_image')){
            $imageName=$request->file('profile_image')->store();
            $this->fields['profile_image']=basename($imageName);
        }
        $user =User::create(array_merge($request->all(), $this->fields));
        event(new Registered($user));
       // $user->sendEmailVerificationNotification();
        return response()->json(['response'=>__('message.create',['name'=>'user'])]);
    }

    public function update(Request $request, User $user)
    {
        // if 'user_status' field is not present in the request validate the request
        if(!$request->has('user_status')){
            $this->validate($request, [
                'username' => ['required', 'string', 'max:255',Rule::unique('users')->ignore($user)],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
                'current_password' => ['sometimes','exclude_if:password,null','required_with:password','current_password:web'],
                'password' => ['nullable','min:6','different:current_password'],
                'password_confirmation' => ['sometimes','exclude_if:password,null','required_with:password','same:password'],
                 ]);
        }   
       DB::beginTransaction();
       try{        
            // If the request contains a file with key 'profile_image',
            //  store the file in the 'public/images' directory and update the 'profile_image' field.
            if($request->hasFile('profile_image')){
                // Get the original profile image file name before updating the user
                $previousProfileImage = $user->getRawOriginal('profile_image');
                $imageName=$request->file('profile_image')->store();
                $this->fields['profile_image']=basename($imageName);
            }

            // remove any empty fields such as profile image or password and Update the existing User model
            $user->update(array_merge(array_filter($request->all()), $this->fields));

            if($user->wasChanged('user_status') && !$user->is_active()){
                //send deactivated email notification to the user               
                event(new UserDeactivated($user));
            }
            if($user->wasChanged('profile_image')){
                    $url_parts = parse_url($previousProfileImage);
                    if ($previousProfileImage && !isset($url_parts['scheme']) && !isset($url_parts['host']))
                        Storage::delete($previousProfileImage);
                    if(auth()->user()->is_same_user($user))
                        $this->imageName=$user->profile_image;
            }
            // Commit the transaction if everything is good
            DB::commit();
            return response()->json(['response'=>__('message.update',['name'=>'user']),'image'=>$this->imageName]);
        } catch (\Exception $e) {
            DB::rollBack();
             //delete newly stored photo
                if($this->fields['profile_image'])
                Storage::delete($this->fields['profile_image']);
              // Handle the exception and return an error response
              return response()->json(['error'=>__('message.error.update',['reason'=>$e->getMessage()])]);
        }
    }


    // when password reset request is performed by admin on behalf of user
    public function password_reset(Request $request)
    {
        $email=User::find($request->id)->email;
        $status = Password::sendResetLink(['email'=>$email]);
        $messagestatus=($status == Password::RESET_LINK_SENT? 'success': 'error');
        return response()->json([$messagestatus=>__($status)]);
    }


    public function edit(User $user)
    {
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        if(!$user->is_admin()){
            $user->delete();
            return response()->json(['response'=>__('message.delete',['name'=>'user'])]);
        }
        else{
            return response()->json(['error'=>__('message.error.admin_user')]);

        }

    }
}
