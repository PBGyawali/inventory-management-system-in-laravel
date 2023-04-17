<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Helper\Select;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public $companyInfo=[];

    public function __construct(Request $request)
    {
        if (!$request->ajax()) {
            $this->companyInfo=CompanyInfo::first();
        }
    }

    public function index()
    {
        $info=$this->companyInfo;
        // sales info in numbers
        $today_sales=$this->today_sales();
        $yesterday_sales=$this->yesterday_sales();
        $last_seven_day_sales=$this->last_seven_day_sales();
        $total_sales=$this->total_sales();
        $today_sales_recorded=$this->today_sales_recorded();
        $yesterday_sales_recorded=$this->yesterday_sales_recorded();
        $last_seven_day_sales_recorded=$this->last_seven_day_sales_recorded();

        // purchases info in numbers
        $today_purchases=$this->today_purchases();
        $yesterday_purchases=$this->yesterday_purchases();
        $last_seven_day_purchases=$this->last_seven_day_purchases();
        $total_purchases=$this->total_purchases();
        $today_purchases_recorded=$this->today_purchases_recorded();
        $yesterday_purchases_recorded=$this->yesterday_purchases_recorded();
        $last_seven_day_purchases_recorded=$this->last_seven_day_purchases_recorded();

        //total items in each category
        $total_user=$this->total_user();
        $total_unit=$this->total_unit();
        $total_tax=$this->total_tax();
        $total_category=$this->total_category();
        $total_brand=$this->total_brand();
        $total_product=$this->total_product();
        $total_supplier=$this->total_supplier();
        // sales info in revenue
        $total_sales_value=$this->total_sales_value();
        $total_cash_sales_value=$this->total_cash_sales_value();
        $total_credit_sales_value=$this->total_credit_sales_value();
        //product sold and revenue received
        $total_revenue_value=$this->total_revenue_value();
        $total_cash_revenue_value=$this->total_cash_revenue_value();
        $total_credit_revenue_value=$this->total_credit_revenue_value();
        // purchases info in revenue
        $total_purchase_value=$this->total_purchase_value();
        $total_cash_purchase_value=$this->total_cash_purchase_value();
        $total_credit_purchase_value=$this->total_credit_purchase_value();
        //product purchased and payment done
        $total_expense_value=$this->total_expense_value();
        $total_cash_expense_value=$this->total_cash_expense_value();
        $total_credit_expense_value=$this->total_credit_expense_value();
        $page='dashboard';
        return view('home',
        compact('info',
        'today_sales','yesterday_sales','last_seven_day_sales','total_sales',
        'today_sales_recorded','yesterday_sales_recorded','last_seven_day_sales_recorded',
        'today_purchases','yesterday_purchases','last_seven_day_purchases','total_purchases',
        'today_purchases_recorded','yesterday_purchases_recorded','last_seven_day_purchases_recorded',
        'total_user','total_unit',
        'total_tax','total_category','total_brand', 'total_product','total_supplier',
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

	// This function calculates the total value of transactions in a given table, based on specified filters.
	function transaction_value($table, $type=null, $active=null){

		// Create empty arrays for our placeholders and conditions that we'll use in our SQL query.
		$placeholder=$condition=[];

		// Pluralize the table name for use in the SQL query.
		$tables=Str::plural($table);

		// If the 'active' filter is set, add it to our arrays.
		if($active) {
			$placeholder[]=$table."_status";
			$condition[]="active";
		}

		// If the 'type' filter is set, add it to our arrays.
		if($type) {
			$placeholder[]="payment_status";
			$condition[]=$type;
		}

		// If the user is not an admin, add their user ID to our arrays.
		if(!auth()->user()->is_admin()) {
			$placeholder[]="user_id";
			$condition[]=auth()->user()->id;
		}

		// Use the Helper::total function to calculate the total value of transactions based on our filters.
		$result= Helper::total($tables,$table."_sub_total",$placeholder,$condition);

		// Return the result, formatted to two decimal places.
		return number_format($result,2);
	}

    function today_sales(){
		return $this->Get_sales_value('sale_date');
	}
	function yesterday_sales(){
        return $this->Get_sales_value('sale_date','1');
	}

	function last_seven_day_sales(){
		return $this->Get_sales_value('sale_date','7','>');
	}

    function today_sales_recorded(){
		return $this->Get_sales_value('created_at');
	}
	function yesterday_sales_recorded(){
        return $this->Get_sales_value('created_at','1');
	}

	function last_seven_day_sales_recorded(){
		return $this->Get_sales_value('created_at','7','>');
	}
    function total_sales(){
		return $this->Get_sales_value();
    }

    function today_purchases(){
		return $this->Get_purchases_value('purchase_date');
	}
	function yesterday_purchases(){
        return $this->Get_purchases_value('purchase_date','1');
	}

	function last_seven_day_purchases(){
		return $this->Get_purchases_value('purchase_date','7','>');
	}

    function today_purchases_recorded(){
		return $this->Get_purchases_value('created_at');
	}
	function yesterday_purchases_recorded(){
        return $this->Get_purchases_value('created_at','1');
	}

	function last_seven_day_purchases_recorded(){
		return $this->Get_purchases_value('created_at','7','>');
	}
    function total_purchases(){
		return $this->Get_purchases_value();
    }
    function Get_sales_value($date=null,$interval=null,$sign=null,$attr=[])
	{
        return $this->Get_value('sales',$date,$interval,$sign,$attr);
	}

    function Get_purchases_value($date=null,$interval=null,$sign=null,$attr=[])
	{
        return $this->Get_value('purchases',$date,$interval,$sign,$attr);
	}

		// This function retrieves a value from a given table based on specified filters.
	function Get_value($table, $date=null, $interval=null, $sign=null, $attr=[])
	{
		// Create empty arrays for our conditions, values, comparisons, and attributes that we'll use in our SQL query.
		$condition=$value=$compare=$attr=[];

		// If the 'date' filter is set, add it to our arrays.
		if ($date){
			// Use the 'raw' key to write the date condition as raw SQL.
			$condition['raw']= 'date('.$date.')';

			// Set the value to today's date, also as raw SQL.
			$value['raw']=date('Y/m/d');

			// If the 'sign' parameter is set, add it to our comparisons array. Otherwise, default to '='.
			$compare['raw']=$sign?$sign.'=':'=';
		}

		// If the 'interval' filter is set, add it to our attributes array to subtract from the date condition.
		if($interval){
			$attr['interval']='- INTERVAL '. $interval  .' DAY';
		}

		// If the user is not an admin, add their user ID to our conditions array.
		if(!auth()->user()->is_admin()){
			$condition[]='user_id';
			$value[]=auth()->user()->id;
			$compare[]='=';
		}

		// Use the CountTable function to retrieve the value from the given table based on our filters.
		return $this->CountTable($table,$condition,$value,$compare,$attr);
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
		$tables=Str::plural($table);
        return Helper::CountTable($tables,$table.'_status',$active);
    }
    function CountTable($table,$condition=null,$value=null,$compare='=',$attr=[]){
        return Helper::CountTable($table,$condition,$value,$compare,$attr);
     }






}
