<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\Select;
use Illuminate\Support\Facades\Auth;

class CompanyInfoController extends Controller
{
    public $companyInfo=[];
    public $fields=array();

    public function __construct(Request $request)
    {
        if (!$request->ajax()) {
            $this->companyInfo=CompanyInfo::first();
        }
    }

    public function index(Request $request)
    {
        $info=$this->companyInfo;
        // Set the page title
        $page='dashboard';
        return view('home',compact('info','page'));

    }

    public function show(Request $request)
    {
        // Set the page title
        $page = 'Welcome';

        // Get the website information from the model
        $info = $this->companyInfo;

        // If there is no website information, redirect to the settings page
        if(!$info)
            return $this->create($request);

        // Clear the setup session variables
        session(['setup' => null]);
        $company_name=$this->companyInfo->company_name;
        // Render the login view and pass the website information and page title as variables
        return view('welcome', compact(['info','company_name','page']));
    }

    //show first time settings page
    public function create(Request $request)
    {
        $info=$this->companyInfo;
        // If there is  website information, redirect to the edit settings page
        if($info)
         return $this->edit($request);
        session(['setup' =>true]);
        $timezonelist=Select::instance()->Timezone_list();
        $currencylist=Select::instance()->Currency_list();
        return view('settings',compact('timezonelist','currencylist'));
    }

     //show page for editing the wesite basic setting
    public function edit(Request $request)
    {
        $info=$this->companyInfo;
        // If there is no website information, redirect to the create settings page
        if(!$info)
            return redirect()->route('settings_create');
        session(['setup' =>null]);
        $timezonelist=Select::instance()->Timezone_list($info->company_timezone);
        $currencylist=Select::instance()->Currency_list($info->company_currency);
        return view('settings',compact('info','timezonelist','currencylist'));
    }

    /*
    Store the website information and create a master user account if an email is provided.
    @param \Illuminate\Http\Request $request
    @return \Illuminate\Http\JsonResponse
    */

    public function store(Request $request)
    {
        // Validate the incoming request data
       $this->validate($request, [
            'company_currency' => ['required', 'string'],
            'company_timezone' => ['required','timezone'],
            'company_address' => ['required'],
            'company_email' => ['required','email'],
            'company_revenue_target' => ['required','numeric'],
            'company_name'=>['required'],
            'company_contact_no' => ['required','numeric'],
            'company_sales_target' => ['required','numeric'],
            'user_name' => ['sometimes','required', 'string', 'max:255'],
            'email' => ['sometimes','required', 'email', 'max:255', 'unique:users'],
            'password'=>['sometimes','required']
        ]);

        // Create the WebsiteInfo model instance with the request data and save it to the database
        CompanyInfo::create($request->all());

        // Set the user_type to 'master' and create a new User model instance
        // if an email is provided in the request data
        if ($request->has('email')){
            $fields['user_type']='master';
            User::create(array_merge(array_filter($request->all()), $fields));
        }
        // Clear the 'setup' session variable and
        // set the 'website_name' session variable to the website name
        session(['setup' =>null]);
        session(['website' => $request->company_name]);

        // a user is logged in to verify csrf for creating data which has to be Logged out
          Auth::logout();
        return response()->json(array('redirect'=>route("login"),'response'=>__('message.first_create')));
    }

    public function update(Request $request, CompanyInfo $company_info)
    {
        // Validate the incoming request
        $this->validate($request, [
            'company_currency' => ['required','string'],
            'company_timezone' => ['required','timezone'],
            'company_name'=>['required'],
            'company_email' => ['required','email'],
            'company_address' => ['required'],
            'company_revenue_target' => ['required','numeric'],
            'company_sales_target' => ['required','numeric'],
            'company_contact_no' => ['required','numeric'],
        ]);

        // get currency symbol from currency name provided
        $fields['currency_symbol']=Select::Get_currency_symbol($request->company_currency);

        // Update the WebsiteInfo object by removing any empty fields from the request and uploaded images
        $company_info->update(array_merge(array_filter($request->all()), $fields));

        // Update the session with the website name
        session(['website' => $request->company_name]);

        // Return a JSON response indicating success
        return response()->json(['response'=>__('message.detail_update')]);
    }





}
