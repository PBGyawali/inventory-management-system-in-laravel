<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use App\Models\ProductSale;
use App\Models\Product;
use App\Helper\Select;
use App\Helper\Helper;
use Yajra\DataTables\Facades\DataTables;
class SaleController extends Controller
{
    public $companyInfo=array();
    public $query='';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Sale::with('user');
            if($request->from_date!=''&& $request->to_date!='')
                $query->whereBetween('sale_date',[$request->from_date, $request->to_date]);
            $data=$query->get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                $actionBtn = '
                    <div class="btn-group text-center">
            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Menu
            </button>
            <ul class="dropdown-menu dropdown-menu-right" >
                <li><button type="button"  id="'.$data->sale_id.'"  data-prefix="Sales Order" class="w-100 mb-1 text-center btn btn-info btn-sm update"><i class="fas fa-edit"></i> Update</button></li>
                <li><button type="button" id="'.$data->sale_id.'"  data-prefix="sale" class="w-100 btn '.(($data->sale_status=="inactive")?'btn-danger':' btn-success').' btn-sm status" data-status="'.$data->sale_status.'">'.(($data->sale_status=="active")?"Settle":"Reopen").'</button></li>
                <li><a href="report/'.($data->sale_status=='active'?'bill':'invoice').'-sale/'.$data->sale_id.'" id="'.$data->sale_id.'" target="_blank"  class="w-100 mb-1 text-center btn btn-warning btn-sm "><i class="fas fa-eye"></i> View PDF</a></li>'.
                ( ( auth()->user()->is_admin()
                    //1==1
                ) ?   '<li><button type="button" id="'.$data->sale_id.'"  class="w-100 btn mb-1 btn-danger btn-sm delete">Delete</button></li>':'').
            '</ul>
            </div>';
                    return $actionBtn;
                })
                ->addColumn('total', function ($data) {
                    return $data->sale_sub_total+$data->sale_tax.$data->sale_discount;
                     })
                ->editColumn('payment_status', function ($data) {
                    if($data->payment_status == 'cash')
                        $status = '<span class="badge badge-primary">Cash</span>';
                    else
                        $status = '<span class="badge badge-warning">Credit</span>';
                        return $status;
                })
                ->editColumn('username', function ($data) {
                    return
                     (auth()->user()->is_admin())?
                     $data->username
                     :''
                     ;
                })
                ->editColumn('sale_status', function ($data) {
                    if($data->sale_status == 'active')
                        $status = '<span  class="badge badge-danger btn-sm">Unpaid</span>';
                    else
                        $status = '<span  class="badge badge-success btn-sm">Paid</span>';
                        return $status;
                })
                ->make(true);
        }
        $info=CompanyInfo::first();
        $page='sales';
        return view('sales',compact('info','page') );
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'sale_name' => ['required','max:255'],
            'sale_date' => ['required', 'date','before:tomorrow'],
            'payment_status'=>['required'],
        ]);
        $data=Sale::create($request->all());
			$total_amount = 0;$total_tax = 0;
			for($count = 0; $count<count($request->product_id); $count++)
			{
				$item_details =Product::find($request->product_id[$count]);
				$value=	array('sale_id'=>$data->sale_id,'product_id'=>$request->product_id[$count],
                'quantity'=> $request->quantity[$count],'price'=>$item_details->product_base_price,
                'tax'=>	$item_details->product_tax);
                ProductSale::create($value);
				$base_price = $item_details->product_base_price * $request->quantity[$count];
				$tax = ($base_price/100)*$item_details->tax;
				$total_tax  = $total_tax + $tax ;
                $total_amount = $total_amount + $base_price ;
                Product::find($request->product_id[$count])->decrement('product_quantity', $request->quantity[$count]);
			}
			$value=array("sale_sub_total"=>$total_amount,"sale_tax"=>$total_tax);
            $data->update($value);
       return response()->json(array('response'=>'<div class="alert alert-success">The Sales data was created!</div>'));
    }


    function Productlist($count=null,$row=null){
        $item_details = '
        <span class="item_details" id="row'.$count.'">
        <input type="hidden" name="hidden_product_id[]" id="hidden_product_id'.$count.'" value="'.(($row)?$row->product_id:'').'" />
        <input type="hidden" name="hidden_quantity[]" id="hidden_quantity'.$count.'"  value="'.(($row)?$row->quantity:'').'"  />
            <div class="row" id="item_details_row'.$count.'">
                <div class="col-md-8">
                    <select name="product_id[]" id="product_id'.$count.'" class="form-control selectpicker"
                        data-live-search="true" required>'.Select::instance()->product_list(($row)?$row->product_id:'').'
                    </select>
                </div>
                <div class="col-md-3 px-0">
                    <input type="number" name="quantity[]" id="quantity'.$count.'" min="1" class="form-control" value="'.(($row)?$row->quantity:'').'"
                    max="'.(($row)?Helper::available_product_quantity($row->product_id)+$row->quantity:'').'"  required />
                </div>
                <div class="col-md-1 pl-0">'.
                (($count== '')?'<button type="button" name="add_more" id="add_more" class="btn btn-success">+</button>'
                :'<button type="button" name="remove" id="'.$count.'" class="btn btn-danger remove">-</button>').'
                    </div>
                </div>
            </div>
        </span>	';
        return 	$item_details;
    }

    public function create( Request $request)
    {
        return response()->json(Helper::available_product_quantity($request->product_id));
    }
    public function show()
    {
        return response()->json(Select::instance()->product_list());
    }

    public function edit(Sale $sales)
    {
        $sales->item_details = '';
		$count = '';
        $subtable=$sales->product_sales ;
        if(!$subtable->isEmpty()){
            foreach($subtable as $sub_row){
                $sales->item_details.=$this->Productlist($count,$sub_row);
                if ($count=='')
                    $count=1;
                else
                    $count = $count++;
            }
        }
        else
        $sales->item_details=$this->Productlist($count);
        return response()->json($sales);
    }


    public function update(Request $request, Sale $sales)
    {
        $value=array();
        if(!$request->hasAny('sale_status','status'))
        {
            $this->validate($request,[
                'sale_name' => ['required','max:255'],
                'sale_date' => ['required', 'date','before:tomorrow'],
                'payment_status'=>['required'],
            ]);
            $sales->product_sales->each->delete();
			for($count = 0; $count < count($request->hidden_product_id); $count++)
			{
                $product_details = Product::find($request->hidden_product_id[$count]);
                if($product_details){
                    $product_details->increment('product_quantity', $request->hidden_quantity[$count]);
                }
			}
            $total_amount = 0;$total_tax = 0;

            for($count = 0; $count<count($request->product_id); $count++)
			{
				$item_details =Product::find($request->product_id[$count]);
				$value=	array('sale_id'=>$sales->sale_id,'product_id'=>$request->product_id[$count],
                'quantity'=> $request->quantity[$count],'price'=>$item_details->product_base_price,
                'tax'=>	$item_details->product_tax);
                ProductSale::create($value);
				$base_price = $item_details->product_base_price * $request->quantity[$count];
				$tax = ($base_price/100)*$item_details->tax;
				$total_tax  = $total_tax + $tax ;
                $total_amount = $total_amount + $base_price ;
                Product::find($request->product_id[$count])->decrement('product_quantity', $request->quantity[$count]);
            }
            $value=array("sale_sub_total"=>$total_amount,"sale_tax"=>$total_tax);
        }
            $sales->update(array_merge($request->all(),$value));
        return response()->json(array('response'=>'<div class="alert alert-success">The data was updated!</div>'));
    }


  public function destroy(Sale $sales)
    {
        $sales->product_sales->each->delete();
        $sales->delete();
        return response()->json(array('response'=>'<div class="alert alert-success">The data was deleted!</div>'));
    }
}
