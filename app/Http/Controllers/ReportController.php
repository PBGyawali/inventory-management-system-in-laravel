<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use App\Helper\Helper;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use Dompdf\Dompdf;
use \NumberFormatter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
// Reference the Options namespace
use Barryvdh\Dompdf\Options;
// Reference the Font Metrics namespace
use Barryvdh\Dompdf\FontMetrics;

class ReportController extends Controller
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
        $info=$this->companyInfo;
        $page='report';
        $sales_target=$this->get_target('sales');
        $revenue_target=$this->get_target('revenue');
        $user_wise_total_sales=$this->user_wise_total('sale');
        $user_wise_total_purchase=$this->user_wise_total('purchase');
        return view('report',compact('info','page','sales_target',
        'revenue_target',
        'user_wise_total_sales',
        'user_wise_total_purchase', ) );
    }


    /**
     * Generates a pdf document for sales or purchase transaction
     * acccording to the type of document requested
     */

    public function create(Request $request)
    {
        // Get the name of the table from the request
        $table = $request->table;
        // Get the plural name of the table
        $tables = Str::plural($table);
        // dynamically get the model corresponding to the table
        $model = 'App\Models\\'.$table;
        // Find the row with the specified ID
        $row = $model::find($request->id);
        // Get the model for the product of the table
        $model2='App\Models\product'.$table;
        // Get the company info
        $info=$this->companyInfo;
        // show type of document as requested
        $view=$request->document;
        // Join the product table with the products table and get all results where the table ID matches the requested ID
        $product_result = $model2::where($table.'_id',$request->id)
            ->leftjoin('products','products.product_id','product_'.$tables.'.product_id')
            ->select('*')
            ->selectRaw('quantity * price as actual_amount ')
            ->selectRaw('quantity * price * tax/100 as tax_amount ')
            ->selectRaw('(quantity * price)+(quantity * price * tax/100) as product_amount ')
            ->get();

        $total_actual_amount = $product_result->sum('actual_amount');
        $total_tax_amount = $product_result->sum('tax_amount');
        $total = $product_result->sum('product_amount');
        // Format some of the totals and amounts
        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        $total_actual_amount = number_format($total_actual_amount, 2);
        $total_tax_amount = number_format($total_tax_amount, 2);
        $grandtotal = number_format($total, 2, '.', ''); // format $total with 2 decimal places and remove thousands separator
        $total_in_words = ucwords($formatter->format(floatval($total))); // pass float value of $total to format method

        // Set the maximum execution time for this script to 100 seconds
        set_time_limit(100);

        // Render the view with the relevant variables
        $output= view($view, compact('info','product_result','row','table','tables','total','grandtotal',
        'total_actual_amount','total_tax_amount','total_in_words') )->render();

        // if($view=='bill')
        // return $output;

        // Create a new Dompdf instance
        $pdf = new Dompdf();
        // Set the file name for the PDF to be the table name, followed by the ID of the row being viewed
        $file_name = $tables.'-'.$row->{$table.'_id'}.'.pdf';
        // Load the HTML output into the Dompdf instance
        $pdf->loadHtml($output);
        //remove this if you want a4 size
        $pdf->setPaper('a2', 'landscape');
        // Render the PDF
        $pdf->render();
        // Stream the PDF to the browser as an attachment with the specified file name
        $pdf->stream($file_name, array("Attachment" => false));
    }


    /**
     * Generates a tabular report in the browser itself for sales or purchase transaction
     * between certain dates
    * This function accepts a HTTP request object with 'from_date', 'to_date' and 'table' parameters,
    *and retrieves a list of active orders from the corresponding table model in the given date range.
    * It also calculates the total amount of the orders and passes it to the 'orderreport' view, along
    *with other necessary data.
    * @param \Illuminate\Http\Request $request The HTTP request object containing the necessary parameters.
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The rendered view of the 'orderreport' page with necessary data.
    */
    public function show(Request $request)
    {
        // Retrieve necessary data from the HTTP request object
        $from_date = $request->from_date;
        $table=$request->table;
        $to_date = $request->to_date;

        // Get the model corresponding to the table
        $model = 'App\Models\\'.$table;

        // Retrieve orders from the model in the given date range
        if(!empty($from_date)&&!empty($to_date))
            $query= $model::select('*',$table.'_date AS date',$table.'_name AS name')
                            ->addSelect($table.'_address as address')
                            ->addSelect($table.'_sub_total as sub_total')
                            ->whereBetween($table.'_date', [$from_date, $to_date]) ;
                            // ->where($table.'_status','active' )

                if($table=='sale')
                    $query->addSelect($table.'_tax as tax');
                $results =$query->get();

        // Retrieve company information and prepare necessary variables for the view
        $info=$this->companyInfo;
        //define page name according to the type of report
        $page=ucwords($table).' Order Report';
        $tables=ucwords(Str::plural($table));

        // Calculate total amount of the orders
            $totalAmount=$results->sum($table.'_sub_total')+(($table=='sale')?$results->sum($table.'_tax'):0);
        // Return the rendered view of the 'orderreport' page with necessary data
        return view('orderreport',compact('info','results','from_date',
            'to_date','table','tables','totalAmount','page') );
    }

    function user_wise_total_sales(){
		return $this->user_wise_total('sale');
	}
	function user_wise_total_purchase(){
		return $this->user_wise_total('purchase');
	}
	function user_wise_total($table)
	{
        $currency=$this->companyInfo->currency_symbol;
        $tables=Str::plural($table);
        $sum="SUM($tables.{$table}_sub_total) AS transaction_total ,
                SUM(CASE WHEN $tables.payment_status = 'cash' THEN $tables.{$table}_sub_total ELSE 0 END )
                 AS cash_total ,
                 SUM( CASE WHEN {$tables}.payment_status = 'credit' THEN $tables.{$table}_sub_total ELSE 0 END )
                  AS credit_total,
                COUNT(user_id) AS transaction_count
                ";
        $attr['select']=array('users.username AS username') ;
		$join=array(
			'Left JOIN'=>array('users','users.id',$tables.'.user_id')
        );
        $attr['selectraw']=$sum;
        $attr['rawsum']=true;
		$attr['groupby']=$tables.".user_id";
		 $result =Helper::instance()->total($tables,$sum,'','',$join,$attr);
        $output = view('user_wise_total',compact('result','tables','currency'))->render();
        return $output;
	}

    /**
     * This function takes an item as a parameter and calculates the current status of the item's target based on the company's information.
     *
     * @param string $item The item for which the target needs to be calculated.
     * @return float|int Returns the current status of the item's target as a percentage, rounded to two decimal places, or 0 if the target is 0.
     */
    function get_target($item){
        // Get the target for the given item from the company information.
        $target = $this->companyInfo->{'company_'.$item.'_target'};

        // If the target is 0, return 0.
        if($target == 0)
            return 0;

        // Calculate the current status based on the item and the total sales.
        if ($item == 'sales')
            $status = Sale::count(); // Get the count of sales.
        else
            $status = Helper::instance()->total('sales','sale_sub_total'); // Get the total of the sale_sub_total field from the sales table.

        $current_status = ($status/$target) * 100; // Calculate the current status as a percentage.

        // If the current status is greater than 100, set it to 100.
        if($current_status > 100)
            return 100;
        else
            return number_format($current_status); // Return the current status as a percentage rounded to two decimal places.
    }



    // function to download database data as csv file
    public function downloadCSV(Request $request,$table, $from_date, $to_date)
    {

        $validatedData = Validator::make(['start_date'=>$from_date, 'end_date'=>$to_date], [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date','before:tomorrow'],
        ])->validate();

               // Define the MySQL table name
                $table = Str::plural($table);
                $model = 'App\Models\\'.$table;
                $delimiter=$request->delimiter??',';
                // Set the file path and name
                $filename=date("Y_m_d").'_'.time().'_'.$table.'_data.csv';
                // Get the column names from the MySQL table
                $columns = DB::getSchemaBuilder()->getColumnListing($table);

                // Fetch the data from the MySQL table and cast it to array
                $query = DB::table($table);
                if($request->from_date!=''&& $request->to_date!='')
                $query->whereBetween($table.'.created_at',[$request->from_date, $request->to_date]);
                $data=$query->get();
                if($data->isEmpty())
                    return view('not-found',['element'=>$table]);

                $data=$data->toArray();
                // Open the CSV file for writing
                $handle = fopen($filename, 'w+');
                // Write the column names to the CSV file
                fputcsv($handle, $columns);

                // Write the data to the CSV file
                foreach ($data as $row) {
                    fputcsv($handle, (array) $row,$delimiter);
                }
                    // Close the file
                    fclose($handle);

                // get the CSV file contents
                $content = file_get_contents($filename);
                // Set the CSV file content as the response body
                $response = response($content);

            // Set the response headers for downloading the file
            $headers = array('Content-Type' => 'text/csv',
                // declaring as an attachment proceeds to download of file directly
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            );

            // Add the headers to the response
            $response->headers->replace($headers);
            // Return the response
            return $response;
    }

}
