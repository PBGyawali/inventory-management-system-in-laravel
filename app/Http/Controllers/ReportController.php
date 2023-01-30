<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use App\Helper\Helper;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use Dompdf\Dompdf;
use \NumberFormatter;
class ReportController extends Controller
{
    public $companyInfo=array();
    public $query='';

    public function __construct()
    {
        $this->companyInfo=CompanyInfo::first();
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

    public function create(Request $request)
    {
        $table=$request->table;
        $tables=$table.'s';
        $model= 'App\Models\\'.$table;
        $row = $model::find($request->id);
        $model2='App\Models\product'.$table;
        $formatter=new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $product_result = $model2::leftjoin('products','products.product_id','product_'.$tables.'.product_id')
        ->where($table.'_id',$request->id)->get();
        $info=$this->companyInfo;
        $view=$request->document;
        $counting=$total=$total_actual_amount=$total_tax_amount= 0;
        foreach($product_result as $key=>$sub_row){
            $count [$key]= ++$counting;
            $actual_amount[$key] = $sub_row->quantity * $sub_row->price;
            $tax_amount[$key]= ($actual_amount[$key] * $sub_row->tax)/100;
            $total_product_amount[$key]= $actual_amount[$key]+ $tax_amount[$key];
            $total_actual_amount = $total_actual_amount + $actual_amount[$key];
            $total_tax_amount = $total_tax_amount + $tax_amount[$key];
            $total = $total + $total_product_amount[$key];
        }
        $total_actual_amount=number_format($total_actual_amount, 2);
        $total_tax_amount= number_format($total_tax_amount, 2);
        $total=number_format($total, 2);
        $total_in_words=ucwords($formatter->format($total));
       set_time_limit(100);
      // return
       $output= view($view, compact('info','product_result','row','table','tables','count','total',
       'total_actual_amount','total_tax_amount','actual_amount',
       'tax_amount','total_product_amount','total_in_words') )->render();
       //if($view=='bill')
            return $output;
        $pdf = new Dompdf();
        $file_name = $tables.'-'.$row->{$table.'_id'}.'.pdf';
        $pdf->loadHtml($output);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->render();
        $pdf->stream($file_name, array("Attachment" => false));
    }
    public function show(Request $request)
    {
        $from_date = $request->from_date;
        $table=$request->table;
        $to_date = $request->to_date;
        $model = 'App\Models\\'.$table;
        if(!empty($from_date)&&!empty($to_date))
        $results = $model::where('created_at','>=',$from_date)
        ->where('created_at','<=',$to_date )
        ->where($table.'_status','active' )
        ->get();
        $info=$this->companyInfo;
        $page=ucwords($table).' Order Report';
        $tables=ucwords($table.'s');
        $totalAmount=0;
        foreach ($results as $key=>$result)
         $totalAmount += $result->{$table.'_sub_total'}+(($table=='sale')?$result->{$table.'_tax'}:0)-$result->{$table.'_discount'};
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
        $tables=$table.'s';
        $sum="SUM($tables.{$table}_sub_total) AS {$tables}_total ,
        SUM(CASE WHEN $tables.payment_status = 'cash' THEN $tables.{$table}_sub_total ELSE 0 END )
         AS cash_{$tables}_total ,
         SUM( CASE WHEN {$tables}.payment_status = 'credit' THEN $tables.{$table}_sub_total ELSE 0 END )
          AS credit_{$tables}_total";
        $attr['select']=array('users.username AS username','users.id AS user_id') ;
		$join=array(
			'Left JOIN'=>array('users','users.id',$tables.'.user_id')
        );
        $attr['selectraw']=$sum;
        $attr['rawsum']=true;
		$attr['groupby']=$tables.".user_id";
		 $result =Helper::instance()->total($tables,$sum,$tables.'.'.$table.'_status','active',$join,$attr);

		 $output = '';
        ${"total_".$tables}=${"total_cash_".$tables}=${"total_credit_".$tables}
        =$totaltransaction=0;
		foreach($result as $row)
		{
            $total_user_transaction=Helper::instance()->CountTable($tables,'user_id',$row->user_id);
			$totaltransaction+=$total_user_transaction;
			$output .= '
			<tr>
				<td class="text-left">'.$row->username.'</td>
				<td class="text-right">'.$total_user_transaction.'</td>
				<td class="text-right">'.$currency.' '.$row->{$tables."_total"}.'</td>
				<td class="text-right"> '.$currency.' '.$row->{'cash_'.$tables."_total"}.'</td>
				<td class="text-right"> '.$currency.' '.$row->{'credit_'.$tables."_total"}.'</td>
			</tr>
			';
			${"total_".$tables} = ${"total_".$tables} + $row->{$tables."_total"};
			${"total_cash_".$tables} = ${"total_cash_".$tables} + $row->{"cash_".$tables."_total"};
			${"total_credit_".$tables} = ${"total_credit_".$tables} + $row->{"credit_".$tables."_total"};
		}
		$output .= '
		<tr>
			<td class="text-right"><b>Total</b></td>
			<td class="text-right"><b> '.$totaltransaction.'</b></td>
			<td class="text-right"><b> '.$currency.' '.${"total_".$tables}.'</b></td>
			<td class="text-right"><b> '.$currency.' '.${"total_cash_".$tables}.'</b></td>
			<td class="text-right"><b>'.$currency.' '.${"total_credit_".$tables}.'</b></td>
		</tr></table></div>
		';
		return $output;
	}

	function get_target($item){
		$target= $this->companyInfo->{'company_'.$item.'_target'};
		if($target==0)
			return 0;
		if ($item=='sales')
			$status=Sale::count();
		else
            $status=Helper::instance()->total('sales','sale_sub_total');
		$current_status=($status/$target)*100;
		 if($current_status>100)
		 	return 100;
		 else
		 	return number_format($current_status);
	}


}
