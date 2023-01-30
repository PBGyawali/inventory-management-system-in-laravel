<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Helper\Select;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate ;
class CompanyInfoController extends Controller
{
    public $companyInfo=array();
    public $fields=array();

    public function __construct()
    {
        $this->companyInfo=CompanyInfo::first();
    }

    public function index(Request $request)
    {
        $info=$this->companyInfo;
        $today_sales=$this->today_sales();
        $yesterday_sales=$this->yesterday_sales();
        $last_seven_day_sales=$this->last_seven_day_sales();
        $total_sales=$this->total_sales();
        $total_purchases=$this->total_sales();
        $total_user=$this->total_user();
        $total_unit=$this->total_unit();
        $total_tax=$this->total_tax();
        $total_category=$this->total_category();
        $total_brand=$this->total_brand();
        $total_product=$this->total_product();
        $total_supplier=$this->total_supplier();
        $total_sales_value=$this->total_sales_value();
        $total_cash_sales_value=$this->total_cash_sales_value();
        $total_credit_sales_value=$this->total_credit_sales_value();
        $total_revenue_value=$this->total_revenue_value();
        $total_cash_revenue_value=$this->total_cash_revenue_value();
        $total_credit_revenue_value=$this->total_credit_revenue_value();
        $total_purchase_value=$this->total_purchase_value();
        $total_cash_purchase_value=$this->total_cash_purchase_value();
        $total_credit_purchase_value=$this->total_credit_purchase_value();
        $total_expense_value=$this->total_expense_value();
        $total_cash_expense_value=$this->total_cash_expense_value();
        $total_credit_expense_value=$this->total_credit_expense_value();
        $page='dashboard';
        return view('home',
        compact('info','today_sales','yesterday_sales','last_seven_day_sales','total_sales',
        'total_purchases','total_user','total_unit',
        'total_tax','total_category','total_brand',
        'total_product','total_supplier',
        'total_sales_value','total_cash_sales_value','total_credit_sales_value',
        'total_revenue_value','total_cash_revenue_value','total_credit_revenue_value',
        'total_purchase_value','total_cash_purchase_value','total_credit_purchase_value',
        'total_expense_value','total_cash_expense_value','total_credit_expense_value',
        'page')
        );
    }
    function total_sales_value(){
		return $this->transaction_value('sale');
	}
	function total_revenue_value(){
		return $this->transaction_value('sale','','active');
	}
	function total_cash_sales_value(){
		return $this->transaction_value('sale','cash');
	}
	function total_cash_revenue_value(){
		return $this->transaction_value('sale','cash','active');
	}
	function total_credit_sales_value(){
		return $this->transaction_value('sale','credit');
	}
	function total_credit_revenue_value(){
		return $this->transaction_value('sale','credit','active');
	}
	function total_purchase_value(){
		return $this->transaction_value('purchase');
	}
	function total_expense_value(){
		return $this->transaction_value('purchase','','active');
	}
	function total_cash_purchase_value(){
		return $this->transaction_value('purchase','cash');
	}
	function total_cash_expense_value(){
		return $this->transaction_value('purchase','cash','active');
	}
	function total_credit_purchase_value(){
		return $this->transaction_value('purchase','credit');
	}
	function total_credit_expense_value(){
		return $this->transaction_value('purchase','credit','active');
	}
    public function show()
    {
        $page='Welcome';
        $info=$this->companyInfo;
        if(!$info)
         return $this->create();
         session(['setup' =>null]);
        return view('welcome',['info'=> $this->companyInfo,'page'=>$page]);
    }
    public function create()
    {
        $info=$this->companyInfo;
        if($info)
         return $this->edit();
        session(['setup' =>true]);
        $timezonelist=Select::instance()->Timezone_list();
        $currencylist=Select::instance()->Currency_list();
        return view('settings',compact('timezonelist','currencylist'));
    }

    public function edit()
    {
        $info=$this->companyInfo;
        if(!$info)
            return redirect()->route('settings_create');
        session(['setup' =>null]);
        $timezonelist=Select::instance()->Timezone_list($info->company_timezone);
        $currencylist=Select::instance()->Currency_list($info->company_currency);
        return view('settings',compact('info','timezonelist','currencylist'));
    }

    public function store(Request $request)
    {
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
            CompanyInfo::create($request->all());
            $fields['user_type']='master';
        if ($request->has('email'))
            User::create(array_merge(array_filter($request->all()), $fields));
        session(['setup' =>null]);
        session(['website' => $request->company_name]);
        Auth::logout();
        return response()->json(array('redirect'=>route("login"),'response'=>'<div class="alert alert-success">Details Created Successfully. Please login </div>'));
    }

    public function update(Request $request, CompanyInfo $company_info)
    {
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
        $fields['currency_symbol']=Select::Get_currency_symbol($request->company_currency);
        $company_info->update(array_merge(array_filter($request->all()), $fields));
        session(['website' => $request->company_name]);
        return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">Details Updated Successfully</div>'));
    }

    function today_sales(){
		return $this->Get_sales_value('created_at');
	}
	function yesterday_sales(){
        return $this->Get_sales_value('created_at','1');
	}

	function last_seven_day_sales(){
		return $this->Get_sales_value('created_at','7','>');
	}
    function total_sales(){
		return $this->Get_sales_value();
    }
    function Get_sales_value($date=null,$interval=null,$sign=null,$attr=array())
	{
		$condition=$value=$compare=$attr=array();
		if ($date){
           $condition['raw']= 'date('.$date.')';
           $value['raw']=date('Y/m/d');
           $compare['raw']=$sign?$sign.'=':'=';
		}
		if($interval){
           $attr['interval']='- INTERVAL '. $interval  .' DAY';
        }
        if(!auth()->user()->is_admin()){
            $condition[]='user_id';	$value[]=auth()->user()->id;
			$compare[]='=';
		}
		return $this->CountTable('sales',$condition,$value,$compare,$attr);
	}

    function total_user(){
		return $this->total('user');
	}
	function total_supplier(){
		return $this->total('supplier');
	}
	function total_category(){
		return $this->total('category');
	}
	function total_unit(){
		return $this->total('unit');
	}
	function total_brand(){
		return $this->total('brand');
	}
	function total_product(){
		return $this->total('product');
	}
	function total_tax(){
		return $this->total('tax');
	}
	function total($table,$active='active'){
        return Helper::CountTable(Str::plural($table),$table.'_status',$active);
    }
    function CountTable($table,$condition=null,$value=null,$compare='=',$attr=array()){
        return Helper::CountTable($table,$condition,$value,$compare,$attr);
     }

	function transaction_value($table,$type=null,$active=null){
		$placeholder=$condition=array();
		if($active)			{	$placeholder[]=$table."_status";	$condition[]="active";		}
		if($type)	{	$placeholder[]="payment_status";		$condition[]=$type;	}
	    if(!auth()->user()->is_admin()){	$placeholder[]="user_id";$condition[]=auth()->user()->id;		}
		$result= Helper::total($table.'s',$table."_sub_total",$placeholder,$condition);
		return number_format($result,2);
	}




}
