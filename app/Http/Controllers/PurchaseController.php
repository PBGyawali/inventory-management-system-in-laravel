<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use App\Helper\Select;
use App\Models\Product;
use App\Models\ProductPurchase;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{

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
                // primary key of the row
                $id=$data->purchase_id;
                // status of the row
                $status=$data->purchase_status;
                // data to display on modal, tables
                $prefix="purchase";
                 // extra text to be added on prefix
                 $extratext='order';
                // message to display on change status button
                $statusbutton=$status=="active"?"Settle":"Reopen";
                // button class of change status button
                $status_class=$status=="active"?"success":"danger";
                // optional button to display
                $buttons=[auth()->user()->is_admin()?'delete':'','report'];
                //url for view pdf report
                $reporturl='report/'.($status=='active'?'bill':'invoice').'-purchase/'.$id;
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
            ->editColumn('purchase_status', function ($data) {
                $class=$data->purchase_status == 'active'?'danger':'success';
                $status = $data->purchase_status == 'active'?'Unpaid':'Paid';
                //render status with css from view
                return view('badge',compact('status','class'))->render();
            })
            ->editColumn('username', function ($data) {
                if(auth()->user()->is_admin())
                    return $data->username;
            })
            ->make(true);
    }
        $info=CompanyInfo::first();
        $page='purchase';
        $supplier_list=Select::instance()->supplier_list();
        return view('purchase',compact('info',
        'page','supplier_list') );
    }


    /**
     * Stores a new Purchase record in the database with the specified request data.
     *
     * @param Request $request - The HTTP request object containing the Purchase data to store.
     * @return JsonResponse - A JSON response indicating success or failure.
     */
    public function store(Request $request)
    {
        // Validate the Purchase data in the request, throwing a validation error if necessary
        $this->validate($request,[
            'purchase_name' => ['required','max:255'],
            'purchase_date' => ['required', 'date','before:tomorrow'],
            'payment_status'=>['required'],
        ]);
        // Create a new Purchase record with the data from the request
        $data=Purchase::create($request->all());
        $totalAmount = 0;
        $totalTax = 0;
        $productPurchases = [];

        // Loop through each product in the request data and calculate its base price and tax amount
        foreach ($request->product_id as $index => $productId)
        {
            // Get the product details from the database
            $product =Product::find($productId);
            $quantity = $request->quantity[$index];
            $price = $product->product_base_price;
            $taxPercentage = $product->product_tax;
            $basePrice = $price * $quantity;
            $taxAmount = ($basePrice / 100) * $taxPercentage;
            $totalTax += $taxAmount;
            $totalAmount += $basePrice;
            // Add the product purchase record to the list of product purchases for the Purchase
            $productPurchases[] = [
                                    'purchase_id' => $data->purchase_id,
                                    'product_id' => $productId,
                                    'quantity' => $quantity,
                                    'price' => $price,
                                    'tax' => $taxAmount
                                ];
            // Increment the product quantity in the database by the quantity purchased
            $product->increment('product_quantity', $quantity);
        }
        // batch insert instead of individual insert in loop is more effecient
        ProductPurchase::insert($productPurchases);

        // Update the Purchase record with the total amount and tax amount
        $data->update([
            'purchase_sub_total' => $totalAmount,
            'purchase_tax'=>$totalTax
        ]);
        return response()->json(['response'=>__('message.create',['name'=>'purchase'])]);
    }

    /**
     * Returns a JSON response containing a list of active products for use in dropdown menus.
     *
     * @return JsonResponse - A JSON response containing a list of active products.
     */
    public function show()
    {
        // Generate a list of active products as a Select object and return it as a JSON response
        $product_list=Select::instance()->product_list('','active');
        return response()->json($product_list);
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
        // Generate a list of products as a Select object
        $select_menu=Select::instance()->product_list($product_id);
        //render into interactive html
        $product_details =view('productlist-select',compact('select_menu','count','quantity','product_id'))->render();
        return 	$product_details;
    }



    /**
     * Prepares a Purchase model to be edited by the user in the frontend.
     * It populates the `item_details` attribute of the Purchase with the HTML code of a list of products and their quantities,
     * which are related to the Purchase through the `product_purchases` relationship.
     *
     * @param Purchase $purchase The Purchase model to be edited.
     * @return \Illuminate\Http\JsonResponse A JSON response with the Purchase data, including the `item_details` attribute.
     */
    public function edit(Purchase $purchase)
    {
        // select menu creation
		$purchase->item_details = '';
        $count = '';
        $subtable=$purchase->product_purchases;
        if(!$subtable->isEmpty()){
            foreach($subtable as $sub_row){
                // calls the Productlist function to generate the HTML code of a select element for a single product row
                $purchase->item_details.=$this->Productlist($count,$sub_row);
                if ($count=='')
                    $count=1;
                else
                    $count = $count++;
            }
        }
        else
        // generates a select element with a single row, if no product purchase exists yet
            $purchase->item_details=$this->Productlist($count);
            // returns a JSON response with the updated Purchase data, including the `item_details` attribute
        return response()->json($purchase);
    }

    public function update(Request $request, Purchase $purchase)
    {
        $value=[];
        if(!$request->hasAny('purchase_status','status'))
        {
            // Validate input data
            $this->validate($request,[
                'purchase_name' => ['required','max:255'],
                'purchase_date' => ['required', 'date','before:tomorrow'],
                'payment_status'=>['required'],
            ]);

            // Delete existing product purchases
            $purchase->product_purchases->each->delete();

            // Update product quantity for previously purchased products
            foreach ($request->hidden_product_id as $index => $hidden_product_id) {
                $previous_quantity = $request->$hidden_quantity[$index];
                $product_details = Product::find($hidden_product_id);
                if ($product_details) {
                    $real_quantity = $product_details->product_quantity - $previous_quantity;
                    // purchase return reduces available quantity so update it
                    $product_details->update(['product_quantity' => $real_quantity]);
                }
            }
            $totalAmount = 0;
            $totalTax = 0;
            $productPurchases = [];
            // Create new product purchases
            foreach ($request->product_id as $index => $productId)
            {
                $product =Product::find($productId);
                $quantity = $request->quantity[$index];
                $price = $product->product_base_price;
                $taxPercentage = $product->product_tax;
                $basePrice = $price * $quantity;
                $taxAmount = ($basePrice / 100) * $taxPercentage;
                $totalTax += $taxAmount;
                $totalAmount += $basePrice;
                $productPurchases[]=['purchase_id'=>$purchase->purchase_id,
                                    'product_id'=>$productId,
                                    'quantity'=> $quantity,
                                    'price'=>$price,
                                    'tax'=>	$taxAmount
                                    ];
                $product->increment('product_quantity', $quantity);
            }
            // batch insert instead of individual insert in loop is more effecient
            ProductPurchase::insert($productPurchases);
            $value = ["purchase_sub_total" => $totalAmount,'purchase_tax'=>$totalTax];
        }
            // Update purchase data
            $purchase->update(array_merge($request->all(),$value));
            // Return JSON response with success message
            return response()->json(['response'=>__('message.update',['name'=>'purchase'])]);
    }

    public function destroy(Purchase $purchase)
    {
         // start a database transaction
        //  to ensure either all database changes are made or none of them.
        DB::beginTransaction();

        try {
            //delete related pivot table data to avoid foreign id constraints
            $purchase->product_purchases->each->delete();
            // Delete the purchase data
            $purchase->delete();
            // Commit the transaction if everything is good
            DB::commit();
            // Return a success response
            return response()->json(['response'=>__('message.delete',['name'=>'purchase'])]);
        } catch (\Exception $e) {
            // Roll back the transaction if error occurs
            DB::rollBack();
              // Handle the exception and return an error response
              return response()->json(['error'=>__('message.error.delete',['reason'=>$e->getMessage()])]);
        }
    }
}
