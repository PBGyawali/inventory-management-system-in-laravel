<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductTax;
use App\Models\ProductEntry;
use App\Models\Tax;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Yajra\DataTables\Facades\DataTables;
use App\Helper\Select;
use App\Helper\Helper;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public $companyInfo=[];

    var $fields=array();
    public function __construct(Request $request)
    {
        if (!$request->ajax()) {
            $this->companyInfo=CompanyInfo::first();
        }
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data =Product::with('category','brand','user')
            ->select('*')
            ->selectRaw('opening_stock + product_quantity - defective_quantity AS available_quantity')
            ->orderBy('category_id')
            ->orderBy('brand_id')
            ->orderBy('product_name')
            ->get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    // primary key of the row
                    $id=$data->getKey();
                    // status of the row
                    $status=$data->product_status;
                    // data to display on modal, tables
                    $prefix="product";
                    // message to display on change status button
                    $statusbutton=$status=="active"?"Disable":"Enable";
                    // button class of change status button
                    $status_class=$status=="active"?"danger":"success";
                    // optional button to display
                    $buttons=['view', 'delete'];
                    //render action button from view
                    $actionBtn = view('control-buttons',compact('buttons','id','status','prefix','statusbutton','status_class'))->render();
                    return $actionBtn;
                })
                ->editColumn('product_status', function ($data) {
                    $status =$data->product_status;
                    $class=$status == 'active'?'success':'danger';
                    //render status with css from view
                    return view('badge',compact('status','class'))->render();
                })
                ->editColumn('available_quantity', function($data){
                    $amount=$data->available_quantity. ' ' . $data->product_unit;
                    return $amount;
                })
                ->setRowClass(function ($data) {
                        $quantity=$data->available_quantity;
                        /* set background red if there is less than 10 stock*/
                        if($quantity<=10)
                            return  'bg-danger text-white';
                        /*set background orange if stock is less than 100 */
                        if($quantity<100)
                        return  'bg-warning text-white';
                })
                ->make(true);
        }
        
        //create required select list for the product
        $category_list=Select::instance()->category_list();
        $unit_list=Select::instance()->unit_list();
        $tax_list=Select::instance()->tax_list();
        return view('product',compact('category_list','unit_list','tax_list') );
    }


    public function create()
    {
        $brand=Brand::all();
        //create required select list for the tax
        $tax=Select::instance()->tax_list();
        return response()->json(compact('tax','brand'));
    }

    /**
     * Creates a new product and saves it to the database.
     *
     * @param Request $request The HTTP request object.
     * @return Illuminate\Http\JsonResponse A JSON response indicating whether the product was successfully created or not.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $this->validate($request,[
            'product_name' => ['required','max:255'],
            'category_id' => ['required', 'numeric'],
            'brand_id'=>['required','numeric'],
            'product_base_price'=>['required','numeric'],
            'tax'=>['required'],
        ]);
        // Initialize the total tax percentage to 0
        $total_tax = 0;

        DB::beginTransaction();

        try {
                // If tax IDs are present in the request data, calculate the total tax percentage
                if ($request->has('tax')) {
                    // Extract the tax IDs from the request data
                    $taxIds = $request->tax; // Array of tax_id values
                    // Calculate total tax percentage
                    $total_tax = Tax::whereIn('tax_id', $taxIds)->sum('tax_percentage');
                }

                // Add the total tax percentage to the mergable data
                $this->fields['product_tax']=$total_tax;

                if($request->hasFile('product_image')){
                    // If a product image file is uploaded, store it and add its filename to the mergable data
                    $filename=$request->file('product_image')->store('public/product_images');
                    $this->fields['product_image']=basename($filename);
                }

                // Create a new product and merge the request data with additional fields
                $created_product=Product::create(array_merge($request->all(), $this->fields));

                // Initialize an empty array to hold product tax data
                $productTaxes = [];

                // For each tax ID, add a new entry to the productTaxes array
                foreach ($taxIds as $taxId) {
                    $productTaxes[] = [
                                        'product_id' => $created_product->getKey(),
                                        'tax_id' => $taxId,
                                    ];
            }
            ProductEntry::create([
                'product_id' => $created_product->getKey(),
                'entry_date' =>$created_product->created_at,
                'total value' =>$created_product->opening_stock*$created_product->product_base_price,
                'opening_stock' =>$created_product->opening_stock
            ]);
            // Insert the product tax data into the pivot table in a batch insert operation
            ProductTax::insert($productTaxes);
               DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception and return an error response
            return response()->json(['error'=>__('message.error.create',['reason'=>$e->getMessage()])]);
        }
        // Return a success response
        return response()->json(['response'=>__('message.create',['name'=>'product Details'])]);
    }


    public function show($productId)
    {
        $product=Product::where('product_id',$productId)
                ->select('*')
                ->selectRaw('opening_stock + product_quantity - defective_quantity AS available_quantity')
                ->leftJoin('users', 'users.id', 'products.user_id')
                ->leftJoin('categories', 'categories.category_id', 'products.category_id')
                ->leftJoin('brands', 'brands.brand_id', 'products.brand_id')
                ->first();

        $viewdatas=[
                    'Product Name'=>$product->product_name,
                    'Product Description'=>$product->product_description,
                    'Category'=>$product->category_name,
                    'Brand'=>$product->brand_name,
                    'Available Quantity'=>$product->available_quantity.' '.$product->product_unit,
                    'Base Price (Without Any Tax)'=>$product->product_base_price,
                    'Tax (%)'=>$product->product_tax,
                    'Entered By'=>$product->username,
                    'Status'=>['class'=>$product->product_status == "inactive"?'danger':'success',
                                'value'=>$product->product_status == "inactive"?'Not available':'Available'
                            ],
                    ];
            // Render the HTML for the view modal, passing in the viewdatas variables
            $output =view('view-modal',compact('viewdatas'))->render();
            return response()->json($output);
    }


    public function edit(Product $product)
    {
         // Clear the "item_details" property of the product
        $product->item_details = '';

        // Initialize the "count" variable to an empty string
        $count = '';

        // Get the taxes associated with the product
        $subtable = $product->taxes;

        // If the product has taxes associated with it
        if (!$subtable->isEmpty()) {
            // Loop through each tax row and append it to the "item_details" property
            foreach ($subtable as $sub_row) {
                // Call the "Taxlist" function and append its result to the "item_details" property
                $product->item_details .= $this->Taxlist($count, $sub_row);

                // Increment the "count" variable
                $count++;
            }
        }
        // If the product has no taxes associated with it
        else {
            // Call the "Taxlist" function and set the result as the "item_details" property
            $product->item_details = $this->Taxlist($count);
        }

        // Return the product as a JSON response
        return response()->json($product);
    }


    /**
     * Generates a list of tax options as an HTML string.
     *
     * @param int|null $count - The number of the tax item in the list (optional).
     * @param mixed|null $row - The tax item to select in the list (optional).
     * @return string - The tax options list as an HTML string.
     */
    function Taxlist($count = null, $row = null)
    {
        // Get the tax options list as a Select object, with the selected tax item if provided
        $select_menu = Select::instance()->tax_list(($row) ? $row->tax_id : '');

        // Render the HTML for the tax options list, passing in the count and select_menu variables
        $html = view('tax-select', compact('count', 'select_menu'))->render();

        // Return the HTML string for the tax options list
        return $html;
    }

    public function update(Request $request, Product $product)
    {
        if(!$request->has('product_status')){
            $this->validate($request,[
                'product_name' => ['required','max:255'],
                'category_id' => ['required', 'numeric'],
                'brand_id'=>['required','numeric'],
                'product_base_price'=>['required','numeric'],
                'tax'=>['required',],
            ]);
        }
        DB::beginTransaction();

        try {
            if ($request->has('tax')) {

                $old_tax=$product->taxes()->pluck('taxes.tax_id');
                // Array of tax_id values
                $new_tax = $request->tax;
                $deleted_tax=$old_tax->diff($new_tax);
                // Remove existing relations of pivot table
                $product->taxes()->detach($deleted_tax);

                // Update pivot table with new tax_id values
                $product->taxes()->sync($new_tax);
                // Calculate total tax
                $total_tax = Tax::whereIn('tax_id', $new_tax)->sum('tax_percentage');
                $this->fields['product_tax']=$total_tax;
            }
            if($request->hasFile('product_image')){
                // If a product image file is uploaded, store it and add its filename to the mergable data
                $filename=$request->file('product_image')->store('/product_images');
                $this->fields['product_image']=basename($filename);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception and return an error response
            return response()->json(['error'=>__('message.error.update',['reason'=>$e->getMessage()])]);
        }
        $product->update(array_merge($request->all(), $this->fields));
        return response()->json(['response'=>__('message.update',['name'=>'product Details'])]);

    }


    public function destroy(Product $product)
    {
        // start a database transaction
        //  to ensure either all database changes are made or none of them.
        DB::beginTransaction();

        try {
            //delete related product taxes first to avoid foreign id constraints error
            $product->product_taxes()->delete();
            $product->delete();

            // Commit the transaction if everything is good
            DB::commit();
            return response()->json(['response'=>__('message.delete',['name'=>'product'])]);
        } catch (\Exception $e) {
            DB::rollBack();
              // Handle the exception and return an error response
              return response()->json(['error'=>__('message.error.delete',['reason'=>$e->getMessage()])]);
        }
    }
}
