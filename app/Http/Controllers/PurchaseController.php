<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use App\Helper\Select;
use App\Models\Product;
use App\Models\ProductPurchase;
use Yajra\DataTables\Facades\DataTables;
class PurchaseController extends Controller
{
    public $companyInfo=array();
    public $query='';

    
    public function index(Request $request)
    {
        //fetch all purchases from DB
       if ($request->ajax()) {
        $query = Purchase::with('user');
            if($request->from_date!=''&& $request->to_date!='')
            $query->whereBetween('purchase_date',[$request->from_date, $request->to_date]);
            $data=$query->get();
        return DataTables::of($data)
            ->addColumn('action', function($data){
            $actionBtn = '
                <div class="btn-group text-center">
        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Menu
        </button>
        <ul class="dropdown-menu dropdown-menu-right" >
            <li><button type="button"  id="'.$data->purchase_id.'"  data-prefix="Purchase order" class="w-100 mb-1 text-center btn btn-info btn-sm update"><i class="fas fa-edit"></i> Update</button></li>
            <li><button type="button" id="'.$data->purchase_id.'"  data-prefix="purchase" class="w-100 btn mb-1 '.(($data->purchase_status=="active")?'btn-success':' btn-danger').' btn-sm status" data-status="'.$data->purchase_status.'">'.(($data->purchase_status=="active")?"Settle":"Reopen").'</button></li>
            <li><a href="report/'.($data->purchase_status=='active'?'bill':'invoice').'-purchase/'.$data->purchase_id.'" id="'.$data->purchase_id.'" target="_blank"  class="w-100 mb-1 text-center btn btn-warning btn-sm "><i class="fas fa-eye"></i> View PDF</a></li>'.
           (( auth()->user()->is_admin()
               //1==1
           )?'<li><button type="button" id="'.$data->purchase_id.'"  class="w-100 btn mb-1 btn-danger btn-sm delete">Delete</button></li>':'').
            '
            </ul>
        </div>';
                return $actionBtn;
            })
           ->editColumn('created_at', function ($data) {
                return $data->created_at->format('Y/m/d');
             })
            ->editColumn('payment_status', function ($data) {
                if($data->payment_status == 'cash')
                    $status = '<span class="badge badge-primary">Cash</span>';
                else
                    $status = '<span class="badge badge-warning">Credit</span>';
                return $status;
            })
            ->editColumn('purchase_status', function ($data) {
                if($data->purchase_status == 'active')
                    $status = '<span  class="badge badge-danger btn-sm">Unpaid</span>';
                else
                    $status = '<span  class="badge badge-success btn-sm">Paid</span>';
                return $status;
            })
            ->editColumn('username', function ($data) {
                return
                (auth()->user()->is_admin())?
                $data->username
                :''
                ;
            })
            ->make(true);
    }
        $info=CompanyInfo::first();
        $page='purchase';
        $supplier_list=Select::instance()->supplier_list();
        return view('purchase',compact('info',
        'page','supplier_list') );
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'purchase_name' => ['required','max:255'],
            'purchase_date' => ['required', 'date','before:tomorrow'],
            'payment_status'=>['required'],
        ]);
        $data=Purchase::create($request->all());
        $total_amount = 0;$total_tax = 0;
        for($count = 0; $count<count($request->product_id); $count++)
        {
            $product_details =Product::find($request->product_id[$count]);
            $value=	array('purchase_id'=>$data->purchase_id,'product_id'=>$request->product_id[$count],
            'quantity'=> $request->quantity[$count],'price'=>$product_details->product_base_price,
            'tax'=>	$product_details->product_tax);
            ProductPurchase::create($value);
            $base_price = $product_details->product_base_price * $request->quantity[$count];
            $tax = ($base_price/100)*$product_details->tax;
            $total_tax  = $total_tax + $tax ;
            $total_amount = $total_amount + $base_price ;
            Product::find($request->product_id[$count])->increment('product_quantity', $request->quantity[$count]);
        }
        $value=array("purchase_sub_total"=>$total_amount);
        $data->update($value);
        return response()->json(array('response'=>'<div class="alert alert-success">The Purchase data was created!</div>'));
    }


    public function show()
    {
        $product_list=Select::instance()->product_list('','active');
        return response()->json($product_list);
    }

    function Productlist($count=null,$row=null){
        $product_details = '
        <span class="item_details" id="row'.$count.'">
        <input type="hidden" name="hidden_product_id[]" id="hidden_product_id'.$count.'" value="'.(($row)?$row->product_id:'').'" />
        <input type="hidden" name="hidden_quantity[]"  id="hidden_quantity'.$count.'" value="'.(($row)?$row->quantity:'').'"  />
            <div class="row" id="item_details_row'.$count.'">
                <div class="col-md-8">
                    <select name="product_id[]" id="product_id'.$count.'" class="form-control " data-live-search="true" required>'.
                    Select::instance()->product_list(($row)?$row->product_id:'').'
                    </select>
                </div>
                <div class="col-md-3 px-0">
                    <input type="number" name="quantity[]" class="form-control" value="'.(($row)?$row->quantity:'').'" required />
                </div>
                <div class="col-md-1 pl-0">'.
                (($count== '')?'<button type="button" name="add_more" id="add_more" class="btn btn-success">+</button>'
                :'<button type="button" name="remove" id="'.$count.'" class="btn btn-danger remove">-</button>').'
                    </div>
                </div>
            </div>
        </span>	';
        return 	$product_details;
    }


    public function edit(Purchase $purchase)
    {
		$purchase->item_details = '';
        $count = '';
        $subtable=$purchase->product_purchases;
        if(!$subtable->isEmpty()){
            foreach($subtable as $sub_row){
                $purchase->item_details.=$this->Productlist($count,$sub_row);
                if ($count=='')
                    $count=1;
                else
                    $count = $count++;
            }
        }
        else
            $purchase->item_details=$this->Productlist($count);
        return response()->json($purchase);
    }

    public function update(Request $request, Purchase $purchase)
    {
        $value=array();
        if(!$request->hasAny('purchase_status','status'))
        {
            $this->validate($request,[
                'purchase_name' => ['required','max:255'],
                'purchase_date' => ['required', 'date','before:tomorrow'],
                'payment_status'=>['required'],
            ]);
            $purchase->product_purchases->each->delete();
			for($count = 0; $count < count($request->hidden_product_id); $count++)
			{
				$previous_quantity=$request->hidden_quantity[$count];
                $product_details = Product::find($request->hidden_product_id[$count]);
                if($product_details){
                    $real_quantity= $product_details->product_quantity-$previous_quantity;
                    $product_details->update(['product_quantity'=>$real_quantity]);
                }
			}
            $total_amount = 0;$total_tax = 0;

            for($count = 0; $count<count($request->product_id); $count++)
			{
				$product_details =Product::find($request->product_id[$count]);
				$value=	array('purchase_id'=>$purchase->purchase_id,'product_id'=>$request->product_id[$count],
                'quantity'=> $request->quantity[$count],'price'=>$product_details->product_base_price,
                'tax'=>	$product_details->product_tax);
                ProductPurchase::create($value);
				$base_price = $product_details->product_base_price * $request->quantity[$count];
				$tax = ($base_price/100)*$product_details->tax;
				$total_tax  = $total_tax + $tax ;
                $total_amount = $total_amount + $base_price ;
                Product::find($request->product_id[$count])->increment('product_quantity', $request->quantity[$count]);
            }
            $value=array("purchase_sub_total"=>$total_amount);
        }
            $purchase->update(array_merge($request->all(),$value));
        return response()->json(array('response'=>'<div class="alert alert-success">The data was updated!</div>'));
    }

  public function destroy(Purchase $purchase)
    {
        $purchase->product_purchases->each->delete();
        $purchase->delete();
        return response()->json(array('response'=>'<div class="alert alert-success">The data was deleted!</div>'));
    }
}
