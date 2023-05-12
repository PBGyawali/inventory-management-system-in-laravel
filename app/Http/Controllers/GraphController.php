<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helper\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    public $companyInfo=[];


    public function __construct(Request $request)
    {
        if (!$request->ajax()) {
            $this->companyInfo=CompanyInfo::first();
        }
    }
    public function index(Request $request)
    {
        
        //data for all type of categories
        $categoryValues=$this->categoryvalue(true);
        $category= $categoryValues['labels'];
        $categoryvalue=$categoryValues['data'];
        //data for all categories which have available quantities
        $allCategoryValues=$this->categoryvalue();
        $allcategory= $allCategoryValues['labels'];
        $allcategoryvalue=$allCategoryValues['data'];
        $fullmonth=$this->getfullmonth();
        $month=$this->getmonth();
        $fullmonthvalue=$this->getfullmonthvalue();
        $monthvalue=$this->getmonthvalue();
        $monthvalue_purchase=$this->getmonthvalue('purchase');
        $fullmonthvalue_purchase=$this->getfullmonthvalue('purchase');
        $monthvalue_sale_revenue=$this->getmonthvalue('sale','revenue');
        $fullmonthvalue_sale_revenue=$this->getfullmonthvalue('sale','revenue');
        $monthvalue_purchase_revenue=$this->getmonthvalue('purchase','revenue');
        $fullmonthvalue_purchase_revenue=$this->getfullmonthvalue('purchase','revenue');
 
        return view('graph',compact(
         'fullmonthvalue','fullmonth','month','monthvalue',
         'category','categoryvalue','allcategory','allcategoryvalue',
        'monthvalue_purchase','fullmonthvalue_purchase',
        'monthvalue_sale_revenue','fullmonthvalue_sale_revenue',
        'monthvalue_purchase_revenue','fullmonthvalue_purchase_revenue',
        )
        );
    }

	// all category value for loading in html
	function categoryvalue($status=null){
		return $this->get_category_value('&quot;',$status);
	}


	// all category value for javascript or ajax
	function categoryvaluehtml($status=true){
		return $this->get_category_value('',$status);
	}


    /**
     * Retrieves category and their respective product quantity
     * values based on the available and quote parameters.
     *
     * @param string|null $quote - An optional quote to enclose category values in.
     * @param string|null $available - An optional flag indicating whether to only retrieve categories with available stock.
     * @return string|array - The category values as an array or string (depending on the quote parameter).
     */
    function get_category_value(?string $quote = null, ?string $available = null): string|array
    {
        // Create a query to retrieve categories and their corresponding quantities.
        $query = Category::ordergroup()
            ->leftJoin('products', 'products.category_id', 'categories.category_id')
            ->select('categories.*')
            ->selectRaw('SUM(opening_stock) + SUM(product_quantity) - SUM(defective_quantity) AS quantity');

        // Add a "having" clause to retrieve only categories with available stock.
        if ($available) {
            $query->having('quantity', '>', 0);
            $query->where('product_status', 'active');
        }

        // Retrieve category data based on the query.
        $categoryDatas = $query->get();

        // Create empty arrays for our category values.
        $categoryValues = [];
        $categoryQuantities = $categoryDatas->pluck('quantity');
        $categoryLabels = $categoryDatas->pluck('category_name');

        // If a quote is specified, enclose the category values and labels in it.
        if ($quote) {
            foreach ($categoryDatas as $index => $categoryData) {
                $categoryValues['data'][] = $quote . $categoryQuantities[$index] . $quote;
                $categoryValues['labels'][] = $quote . $categoryLabels[$index] . $quote;
            }

            // Convert the data and labels arrays to comma-separated strings.
            $categoryValues['data'] = implode(',', $categoryValues['data']);
            $categoryValues['labels'] = implode(',', $categoryValues['labels']);
        } else {
            // If no quote is specified, return the data and labels arrays as is.
            $categoryValues['data'] = $categoryQuantities;
            $categoryValues['labels'] = $categoryLabels;
        }

        // Return the resulting category values.
        return $categoryValues;
    }




	public function edit(Request $request)
	{
		// Get the table, current graph display value and type from the request
		$table=$request->table;
		$type=$request->type;
        $value=$request->value;
		// If the table is "product", get category data
		if($table=='product')
		{
            $status=($value=='fullmonths')?false:true;
			$values=$this->categoryvaluehtml($status);
		}
		else
		{
            if($value=='fullmonths'){
                    // get data for all past 12 months
                $labels=$this->getfullmonthhtml();
                $data=$this->getfullmonthvaluehtml($table,$type);
            }
            else{
                    // get data for all month starting from the month 1 of this year
                $labels=$this->getmonthhtml();
                $data=$this->getmonthvaluehtml($table,$type);
            }
            $values=[ 'labels'=>$labels,
                      'data'=>$data
                    ];
		}
		// Return the labels and data as a JSON response
		return response()->json($values);
	}


    // all month names for javascript or ajax
    function getfullmonthhtml(){
		return $this->loopfullmonth();
	}
	// all month names
	function getfullmonth(){
		return $this->loopfullmonth("&quot;");
	}

	 // limited month names
	 function getmonth()	{
		return $this->loopmonth('&quot;');
    }

	// limited month names for javascript or ajax
	function getmonthhtml()	{
		return $this->loopmonth();
	}


		/**
	 * Returns an array of short month names for all 12 months, starting from the current month.
	 *
	 * @param string|null $quote - Optional quote to surround each month name with.
	 * @return array|string - Array of month names, or a comma-separated string if $quote is provided.
	 */
	function loopfullmonth($quote=null) {
		$value=[];
		$startpos = date('n');
		for($i=1;$i<=12;$i++)
			array_push($value,$quote .substr(date('F', mktime(0, 0, 0, ($i), 2, date('Y'))),0,3).$quote);
		$output = array_merge(array_slice($value,$startpos), array_slice($value, 0, $startpos));
		if ($quote)
			return implode(',',$output);
		return $output;
	}

	/**
	 * Returns an array of short month names from the start month to the current month.
	 *
	 * @param string|null $quote - Optional quote to surround each month name with.
	 * @param int $start - The month number to start from (1-12). Default is 1.
	 * @return array|string - Array of month names, or a comma-separated string if $quote is provided.
	 */
	function loopmonth($quote=null,$start=1){
		$months=[];
		for($i=$start;$i<=date('n');$i++)
			array_push($months,$quote.substr(date('F', mktime(0, 0, 0, ($i), 2, date('Y'))),0,3).$quote);
		if ($quote)
			return  implode(',',$months);
		return  $months;
	}

	/**
	 * Returns an array of values for a given table and type, for each month from January to the current month.
	 *
	 * @param string $table - The table name to retrieve values from.
	 * @param string $type - The type of value to retrieve (number or total).
	 * @return array|string - Array of values, or a comma-separated string if $quote is provided.
	 */
	function getmonthvalue($table='sale',$type='number'){
		return $this->loopmonthvalue($table,$type,'&quot;');
	}

	/**
	 * Returns an array of HTML ready values for a given table and type, for each month from January to the current month.
	 *
	 * @param string $table - The table name to retrieve values from.
	 * @param string $type - The type of value to retrieve (number or total).
	 * @return array|string - Array of values, or a comma-separated string if $quote is provided.
	 */
	function getmonthvaluehtml($table='sale',$type='number'){
		return $this-> loopmonthvalue($table,$type);
	}

	/**
	 * Returns an array of values for a given table and type, for each month from January to the current month.
	 *
	 * @param string $table - The table name to retrieve values from.
	 * @param string $type - The type of value to retrieve (number or total).
	 * @param string|null $quote - Optional quote to surround each value with.
	 * @return array|string - Array of values, or a comma-separated string if $quote is provided.
	 */
	function loopmonthvalue($table='sale',$type='number',$quote=null) {
		$value=[];
		for($i=1;$i<=date('n');$i++)
			array_push($value,$quote .$this->getValuePerMonth($i,$table,$type).$quote);
		if ($quote)
			return  implode(',',$value);
		return  $value;
	}

	/**
	 * Returns an array of values representing the total count or sub-total for each month of the current year.
	 *
	 * @param string $table The name of the database table to query.
	 * @param string $type The type of value to retrieve. Must be either "number" or "sub-total".
	 *
	 * @return array|string An array of values or a comma-separated string of values, depending on the value of $quote.
	 */
	function getfullmonthvalue($table='sale',$type='number'){
		return $this->loopfullmonthvalue($table,$type,"&quot;");
	}

	/**
	 * Returns an HTML ready string of values representing the total count or sub-total for each month of the current year.
	 *
	 * @param string $table The name of the database table to query.
	 * @param string $type The type of value to retrieve. Must be either "number" or "sub-total".
	 *
	 * @return string An HTML ready string of values.
	 */
	function getfullmonthvaluehtml($table='sale',$type='number'){
		return $this->loopfullmonthvalue($table,$type);
	}

	/**
	 * Returns an array of values representing the total count or sub-total for each month of the current year.
	 *
	 * @param string $table The name of the database table to query.
	 * @param string $type The type of value to retrieve. Must be either "number" or "revenue".
	 * @param string|null $quote The quote character to use when returning a comma-separated string of values. If null, returns an array.
	 *
	 * @return array|string An array of values or a comma-separated string of values, depending on the value of $quote.
	 */
	function loopfullmonthvalue($table='sale',$type='number',$quote=null)
	{   $value=[];
		$startpos = date('n');
		for($i=1;$i<=12;$i++)
			array_push($value,$quote .$this->getValuePerMonth($i,$table,$type).$quote);
		$output = array_merge(array_slice($value,$startpos), array_slice($value, 0, $startpos));
		if ($quote)
			return implode(',',$output);
		return $output;
	}

	/**
	 * Returns the total count or sub-total for a specific month of the current year.
	 *
	 * @param int $value The month for which to retrieve the value.
	 * @param string $table The name of the database table to query.
	 * @param string $type The type of value to retrieve. Must be either "number" or "sub-total".
	 *
	 * @return int The total count or sub-total for the specified month.
	 */
	function getValuePerMonth($value,$table='sale',$type='number'){
		$condition['raw']= 'MONTH(created_at)';
		$values['raw']=$value;
		$tables=Str::plural($table);
		if($type=='number')
			return Helper::CountTable($tables,$condition,$values);
		else
			return $this->total($tables,$table.'_sub_total',$condition,$values);
	}


    function total($table=null,$column=null,$placeholder=null,$value=null,$join=array(),$attr=array()){
         return Helper::total($table,$column,$placeholder,$value,$join,$attr);
    }

}
