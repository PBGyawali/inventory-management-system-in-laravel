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
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public $companyInfo=[];


    public function index(Request $request)
    {
        if ($request->ajax()) {
            //get sales data with related user table data
            $query = Sale::with('user')
            ->select('*')
            ->SelectRaw('sale_sub_total+sale_tax AS total');
            if($request->from_date!=''&& $request->to_date!='')
                $query->whereBetween('sale_date',[$request->from_date, $request->to_date]);
            $data=$query->get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    // primary key of the row
                    $id=$data->sale_id;
                    // status of the row
                    $status=$data->sale_status;
                    // data to display on modal, tables
                    $prefix="sale";
                    // extra text to be added on prefix
                    $extratext='order';
                    // message to display on change status button
                    $statusbutton=$status=="active"?"Settle":"Reopen";
                    // button class of change status button
                    $status_class=$status=="active"?"success":"danger";
                    // optional button to display
                    $buttons=[auth()->user()->is_admin()?'delete':'','report'];
                    //url for view pdf report
                    $reporturl='report/'.($status=='active'?'bill':'invoice').'-sale/'.$id;
                    //render action button from view
                    $actionBtn = view('control-buttons',compact('buttons','id','status','prefix','extratext','statusbutton','status_class','reporturl'))->render();
                    return $actionBtn;
                })
                ->editColumn('payment_status', function ($data) {
                    $status =$data->payment_status;
                    $class=$status == 'cash'?'primary':'warning';
                    //render status with css from view
                    return view('badge',compact('status','class'))->render();

                })
                ->editColumn('username', function ($data) {
                    return (auth()->user()->is_admin())?$data->username:'';
                })
                ->editColumn('sale_status', function ($data) {
                    $class=$data->sale_status == 'active'?'danger':'success';
                    $status = $data->sale_status == 'active'?'Unpaid':'Paid';
                    //render status with css from view
                    return view('badge',compact('status','class'))->render();
                })
                ->make(true);
        }
        $info=CompanyInfo::first();
        $page='sales';
        return view('sales',compact('info','page') );
    }


    public function store(Request $request)
    {
         // Validate input data
        $this->validate($request,[
            'sale_name' => ['required','max:255'],
            'sale_date' => ['required', 'date','before:tomorrow'],
            'payment_status'=>['required'],
        ]);
        $sales=Sale::create($request->all());
			$totalAmount = 0;
            $totalTax = 0;
            $productSales = [];
            foreach ($request->product_id as $index => $productId)
            {
                $product =Product::find($productId);
                $quantity = $request->quantity[$index];
                $price = $product->product_base_price;
                $tax = $product->product_tax;
                $basePrice = $price * $quantity;
                $taxAmount = ($basePrice / 100) * $tax;
                $totalTax += $taxAmount;
                $totalAmount += $basePrice;
                $productSales[]=	['sale_id'=>$sales->sale_id,
                'product_id'=>$productId,
                'quantity'=> $quantity,
                'price'=>$price,
                'tax'=>	$taxAmount ];
                Product::find($productId)->decrement('product_quantity', $quantity);
            }
            // batch insert instead of individual insert in loop is more effecient
            ProductSale::insert($productSales);
			$value=array("sale_sub_total"=>$totalAmount,"sale_tax"=>$totalTax);
            $sales->update($value);
            return response()->json(['response'=>__('message.create',['name'=>'Sales'])]);
    }

    /**
     * Returns a JSON response of max quantity available for sale.
     *
     * @return JsonResponse - A JSON response of max available quantity.
     */
    public function create( Request $request)
    {
        $product_quantity=Helper::available_product_quantity($request->product_id);
        return response()->json($product_quantity);
    }


    /**
     * Returns a JSON response containing a list of products for use in dropdown menus.
     *
     * @return JsonResponse - A JSON response containing a list of products.
     */
    public function show()
    {
        // Generate a list of products as a Select object and return it as a JSON response
        $product_list=Select::instance()->product_list();
        return response()->json($product_list);
    }


    /**
     * Prepares a Sale model to be edited by the user in the frontend.
     * It populates the `item_details` attribute of the Sale with the HTML code of a list of products
     * and their quantities, which are related to the Sale through the `product_sales` relationship.
     *
     * @param Sale $sales The Sale model to be edited.
     * @return \Illuminate\Http\JsonResponse A JSON response with the Sale data, including the
     * `item_details` attribute.
     */
    public function edit(Sale $sales)
    {
        // select menu creation
		$sales->item_details = '';
        $count = '';
        $subtable=$sales->product_sales ;
        if(!$subtable->isEmpty()){
            foreach($subtable as $sub_row){
                // calls the Productlist function to generate the HTML code of a select element for a single product row
                $sales->item_details.=$this->Productlist($count,$sub_row);
                if ($count=='')
                    $count=1;
                else
                    $count = $count++;
            }
        }
        else
        // generates a select element with a single row, if no product sales exists yet
            $sales->item_details=$this->Productlist($count);
            // returns a JSON response with the updated sales data, including the `item_details` attribute
        return response()->json($sales);
    }


    /**
     * Generates an HTML string for a dropdown menu containing the list of products.
     *
     * @param int|null $count - The number of the product item in the list (optional).
     * @param mixed|null $row - The product item to select in the list (optional).
     * @return string - The product list dropdown menu as an HTML string.
     */
    function Productlist($count=null,$row=null){
        // Get the selected product and quantity (if provided) from the row data
        $product_id=$row?$row->product_id:'';
        $quantity=$row?$row->quantity:'';

        // Get the max saleable quantity (if provided) from the row data
        $max=$row?Helper::available_product_quantity($product_id)+$quantity:null;
        // Generate a list of products as a Select object
        $select_menu=Select::instance()->product_list($product_id);
        //render into interactive html
        $product_details =view('productlist-select',compact('select_menu','count','max','quantity','product_id'))->render();
        return 	$product_details;
    }


    public function update(Request $request, Sale $sales)
    {
        $value=[];
        if(!$request->hasAny('sale_status','status'))
        {
            // Validate input data
            $this->validate($request,[
                'sale_name' => ['required','max:255'],
                'sale_date' => ['required', 'date','before:tomorrow'],
                'payment_status'=>['required'],
            ]);
            // Delete existing product sales relation
            $sales->product_sales->each->delete();

            // Update product quantity for previously sold products
            foreach($request->hidden_product_id as $key => $productId){
                $productDetails = Product::find($productId);
                if($productDetails){
                    $quantity = $request->hidden_quantity[$key];

                    // sales return increases available quantity so update it
                    $productDetails->increment('product_quantity', $quantity);
                }
            }

            $totalAmount = 0;
            $totalTax = 0;
            $productSales = [];

            // Create new product sales
            foreach ($request->product_id as $index => $productId)
            {
                $product =Product::find($productId);
                $quantity = $request->quantity[$index];
                $price = $product->product_base_price;
                $tax = $product->product_tax;
                $basePrice = $price * $quantity;
                $taxAmount = ($basePrice / 100) * $tax;
                $totalTax += $taxAmount;
                $totalAmount += $basePrice;
                $productSales []=  [
                                    'sale_id'=>$sales->sale_id,
                                    'product_id'=>$productId,
                                    'quantity'=> $quantity,
                                    'price'=>$price,
                                    'tax'=>	$taxAmount
                                  ];
                $product->decrement('product_quantity', $quantity);
            }
            // create pivot table with batch insert instead of individual insert in loop
            // since it  is more effecient
            ProductSale::insert($productSales);
                $value=["sale_sub_total"=>$totalAmount,"sale_tax"=>$totalTax];
            }
            // Update sales data
            $sales->update(array_merge($request->all(),$value));

            // Return JSON response with success message
            return response()->json(['response'=>__('message.update',['name'=>'Sales'])]);
    }


  public function destroy(Sale $sales)
    {
         // start a database transaction
        //  to ensure either all database changes are made or none of them.
        DB::beginTransaction();

        try {
            //delete related product_sales first to avoid foreign id constraints error
            $sales->product_sales->each->delete();
            $sales->delete();

            // Commit the transaction if everything is good
            DB::commit();
            return response()->json(['response'=>__('message.delete',['name'=>'Sales'])]);
        } catch (\Exception $e) {
            DB::rollBack();
              // Handle the exception and return an error response
              return response()->json(['error'=>__('message.error.delete',['reason'=>$e->getMessage()])]);
        }
    }
}
