<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use App\Helper\Select;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductEntry;
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
                $id=$data->getKey();
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
                $buttons=[auth()->user()->is_admin()?'delete':null,'report'];
                //url for view pdf report
                $reporturl=route('report.document',['table'=>'purchase','id'=>$id,'document'=>$status=='active'?'bill':'invoice']);
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
        
        $supplier_list=Select::instance()->supplier_list();
        // Generate a list of active products as a Select object and return it as a JSON response
        $product_list=Select::instance()->product_list('','active');
        return view('purchase',compact('supplier_list','product_list') );
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

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $totalTax = 0;
            $productPurchases = [];

            $total_quantities=[];
            foreach ($request->product_id as $index => $productId)
            {
                $quantity = $request->quantity[$index];
                $discount=$request->discount[$index];
                if(!isset($total_quantities[$productId]))
                    $total_quantities[$productId]=0;
                $total_quantities[$productId]+=$quantity;
                if(!isset($total_discounts[$productId]))
                    $total_discounts[$productId]=0;
                $total_discounts[$productId]+=$discount;
            }

            // Loop through each product in the request data and calculate its base price and tax amount
            foreach ($total_quantities as $productId=>$quantity)
            {
                // Get the product details from the database
                $product =Product::find($productId);
                $price = $product->product_base_price;
                $taxPercentage = $product->product_tax;
                $discount=$total_discounts[$productId];
                $basePrice = ($price-$discount) * $quantity;
                $taxAmount = ($basePrice / 100) * $taxPercentage;
                $totalTax += $taxAmount;
                $totalAmount += $basePrice;
                // Add the product purchase record to the list of product purchases for the Purchase
                $productPurchases[] = [
                                        'product_id' => $productId,
                                        'quantity' => $quantity,
                                        'price' => $price,
                                        'tax' => $taxAmount,
                                        'discount'=>$discount
                                    ];
                                    ProductEntry::insert([[
                                        'product_id' => $productId,
                                        'entry_date' =>$request->purchase_date,
                                        'total value' =>$basePrice,
                                        'opening_stock' =>$quantity
                                    ]]);
                // Increment the product quantity in the database by the quantity purchased
                $product->increment('product_quantity', $quantity);
            }
            $extra_fields=[
                'purchase_sub_total' => $totalAmount,
                'purchase_tax'=>$totalTax,
                'purchase_discount'=>collect($total_discounts)->sum()
            ];

            $purchase=Purchase::create($request->all()+$extra_fields);
            foreach ($productPurchases as $index => $productPurchase)
            {
                $productPurchases[$index]['purchase_id']=$purchase->getKey();
            }
            
            // batch insert instead of individual insert in loop is more effecient
            ProductPurchase::insert($productPurchases);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception and return an error response
            return response()->json(['error'=>__('message.error.create',['reason'=>$e->getMessage()])]);
        }
            $total_quantities=[];
            foreach ($request->product_id as $index => $productId)
            {
                $quantity = $request->quantity[$index];
                if(!isset($total_quantities[$productId]))
                    $total_quantities[$productId]=0;
                $total_quantities[$productId]+=$quantity;
            }

            // Loop through each product in the request data and calculate its base price and tax amount
            foreach ($total_quantities as $productId=>$quantity)
            {
                // Get the product details from the database
                $product =Product::find($productId);
                $price = $product->product_base_price;
                $taxPercentage = $product->product_tax;
                $basePrice = $price * $quantity;
                $taxAmount = ($basePrice / 100) * $taxPercentage;
                $totalTax += $taxAmount;
                $totalAmount += $basePrice;
                // Add the product purchase record to the list of product purchases for the Purchase
                $productPurchases[] = [
                                        'product_id' => $productId,
                                        'quantity' => $quantity,
                                        'price' => $price,
                                        'tax' => $taxAmount
                                    ];
                // Increment the product quantity in the database by the quantity purchased
                $product->increment('product_quantity', $quantity);
            }
            $extra_fields=[
                'purchase_sub_total' => $totalAmount,
                'purchase_tax'=>$totalTax
            ];

            $purchase=Purchase::create($request->all()+$extra_fields);
            foreach ($productPurchases as $index => $productPurchase)
            {
                $productPurchases[$index]['purchase_id']=$purchase->purchase_id;
            }

            // batch insert instead of individual insert in loop is more effecient
            ProductPurchase::insert($productPurchases);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception and return an error response
            return response()->json(['error'=>__('message.error.create',['reason'=>$e->getMessage()])]);
        }
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
        $product=Select::instance()->product_list('','active');
        return response()->json(compact('product'));
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
        $discount=$row?$row->discount:'';
        // Generate a list of products as a Select object
        $select_menu=Select::instance()->product_list($product_id);
        $element='purchase';
        //render into interactive html
        $product_details =view('productlist-select',compact('select_menu','count','quantity','product_id','element','discount'))->render();
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
            DB::beginTransaction();

            try {
                    $purchase_id=$purchase->getKey();

                    $old_purchases=ProductPurchase::where('purchase_id',$purchase_id)
                            ->get();

                    $old_products=$old_purchases->pluck('product_id');
                    $new_products=$request->product_id;
                    $deleted_products=$old_products->diff($new_products);

                    // Delete product purchases for deleted products
                    if ($deleted_products->count() > 0) {
                        ProductPurchase::where('purchase_id', $purchase_id)
                            ->whereIn('product_id', $deleted_products)
                            ->delete();
                    }
                    // Update product quantity for previously purchased products
                    foreach ($old_purchases as $old_purchase) {
                        $previous_quantity = $old_purchase->quantity;
                        $product_id=$old_purchase->product_id;
                        $product_details = Product::find($product_id);
                        if ($product_details) {
                            $real_quantity = $product_details->product_quantity - $previous_quantity;
                            // purchase return reduces available quantity so update it
                            $product_details->update(['product_quantity' => $real_quantity]);
                        }
                    }

                    $total_quantities=[];
                    foreach ($request->product_id as $index => $productId)
                    {
                        $quantity = $request->quantity[$index];
                        $discount=$request->discount[$index];
                        if(!isset($total_quantities[$productId]))
                            $total_quantities[$productId]=0;
                        $total_quantities[$productId]+=$quantity;
                        if(!isset($total_discounts[$productId]))
                            $total_discounts[$productId]=0;
                        $total_discounts[$productId]+=$discount;
                    }
                    $totalAmount = 0;
                    $totalTax = 0;
                    $productPurchases = [];
                    // Create new product purchases
                    foreach ($total_quantities as $productId=>$quantity)
                    {
                        $product =Product::find($productId);
                        $price = $product->product_base_price;
                        $taxPercentage = $product->product_tax;
                        $discount=$total_discounts[$productId];
                        $basePrice = ($price-$discount) * $quantity;
                        $taxAmount = ($basePrice / 100) * $taxPercentage;
                        $totalTax += $taxAmount;
                        $totalAmount += $basePrice;
                        $productPurchases[]=['purchase_id'=>$purchase_id,
                                            'product_id'=>$productId,
                                            'quantity'=> $quantity,
                                            'price'=>$price,
                                            'tax'=>	$taxAmount,
                                            'discount'=>$discount
                                            ];
                        $product->increment('product_quantity', $quantity);
                    }

                    foreach($productPurchases as $index=>$productPurchase){
                        $product_id=$productPurchase['product_id'];
                        ProductPurchase::updateOrCreate(
                            ['purchase_id' => $purchase_id,'product_id'=>$product_id], // attributes to search for
                            $productPurchase // attributes to update or create
                        );
                    }
                    $value = ["purchase_sub_total" => $totalAmount,
                    'purchase_discount'=>collect($total_discounts)->sum(),
                    'purchase_tax'=>$totalTax];
                    DB::commit();
            }
            catch (\Exception $e) {
                DB::rollBack();
                // Handle the exception and return an error response
                return response()->json(['error'=>__('message.error.update',['reason'=>$e->getMessage()])]);
            }
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

            $purchase_id=$purchase->getKey();
            $old_purchases=ProductPurchase::where('purchase_id',$purchase_id)
                    ->get();
            // Update product quantity for previously purchased products
            foreach ($old_purchases as $old_purchase) {
                $previous_quantity = $old_purchase->quantity;
                $product_id=$old_purchase->product_id;
                $product_details = Product::find($product_id);
                if ($product_details) {
                    $real_quantity = $product_details->product_quantity - $previous_quantity;
                    // purchase return reduces available quantity so update it
                    $product_details->update(['product_quantity' => $real_quantity]);
                }
            }

            //delete related pivot table data to avoid foreign id constraints
            $purchase->product_purchases()->delete();
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




    // function to download database data as csv file
    public function uploadCSV(Request $request)
    {
        // validate the file
        $validated = $request->validate([
            'csv' => 'required|mimes:csv,txt'
        ]);

            //get the csv file
        if ($request->hasFile('csv')) {
            $csv = $request->file('csv');

            //get the csv data and convert it into array
            $csv_data = array_map('str_getcsv', file($csv));
            //get the header file from the csv
            $header=$csv_data[0];
            //getting the primary key name of the model
            $primary_key=(new Purchase)->getKeyName();

             // remove the first row from $csv_data since it is the header
            array_shift($csv_data);

            //create a key value pair from headers and its value
            array_walk($csv_data , function(&$row) use ($header) {
              $row = array_combine($header, $row);
            });

            //remove the primary key combination of values
            foreach($csv_data as $index=>$data){
                unset($csv_data[$index][$primary_key]);
            }

            echo'<pre>';
            print_r($csv_data);
            return;
            //Mass insert for performance
               Purchase::insert($csv_data);
               if ($request->ajax()) {
                return response()->json(['response'=>__('message.upload',['name'=>'Purchase'])]);
               }


        }
    }
}
