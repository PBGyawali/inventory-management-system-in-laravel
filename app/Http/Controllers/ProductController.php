<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Yajra\DataTables\Facades\DataTables;
use App\Helper\Select;
use App\Helper\Helper;
use App\Models\Brand;
class ProductController extends Controller
{
    public $companyInfo=array();
    public $query='';
    var $fields=array();
    public function __construct()
    {
        $this->companyInfo=CompanyInfo::first();
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data =Product::with('category','brand','user');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                $actionBtn = '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Menu
                </button>
                <ul class="dropdown-menu" >
                <li><button type="button" name="view" id="'.$data->product_id.'"  class="w-100 mb-1 text-center btn btn-warning btn-sm view"><i class="fas fa-eye"></i> View</button></li>
                  <li><button type="button"  id="'.$data->product_id.'"  data-prefix="Product" class="w-100 mb-1 text-center btn btn-info btn-sm update"><i class="fas fa-edit"></i> Update</button></li>
                  <li><button type="button"  id="'.$data->product_id.'" data-prefix="product" class="w-100 btn btn-primary btn-sm status" data-status="'.$data->product_status.'">Change Status</button></li>
                </ul>
              </div>';
                    return $actionBtn;
                })
                ->addColumn('available_product_quantity', function($data){
                       $quantity = Helper::available_product_quantity($data->product_id) . ' ' . $data->product_unit;
                        return $quantity;
                    })
                ->editColumn('product_status', function ($data) {
                    if($data->product_status == 'active')
                        $status = '<span  class="badge badge-success btn-sm">'.$data->product_status.'</span>';
                    else
                        $status = '<span  class="badge badge-danger btn-sm">'.$data->product_status.'</span>';
                        return $status;
                     })
                ->make(true);
        }
        $info=$this->companyInfo;
        $page='product';
        $category_list=Select::instance()->category_list();
        $unit_list=Select::instance()->unit_list();
        return view('product',compact('info','page','category_list','unit_list') );
    }


    public function create()
    {
        $brands=Brand::all();
        $taxlist=Select::instance()->tax_list();
        return response()->json(compact('taxlist','brands'));
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'product_name' => ['required','max:255'],
            'category_id' => ['required', 'numeric'],
            'brand_id'=>['required','numeric'],
            'product_base_price'=>['required','numeric'],
            'tax'=>['required'],
        ]);
        $total_tax =0;
		for($count = 0; $count<count($request->tax); $count++){
			$total_tax += $request->tax[$count];
        }
        $this->fields['product_tax']=$total_tax;
        if($request->hasFile('product_image'))
            $this->fields['product_image']=basename($request->file('product_image')->store('public/product_images'));
        Product::create(array_merge($request->all(), $this->fields));
       return response()->json(array('response'=>'<div class="alert alert-success">The product data was created!</div>'));
    }


    public function show(Product $product)
    {
        $output = '<div class="table-responsive">
        <table class="table table-bordered">
            <tr> <td>Product Name</td>
                <td>'.$product->product_name.'</td>
            </tr> <tr>
                <td>Product Description</td>
                <td>'.$product->product_description.'</td>
            </tr> <tr>
                <td>Category</td>
                <td>'.$product->category->category_name.'</td>
            </tr> <tr>
                <td>Brand</td>
                <td>'.$product->brand->brand_name.'</td>
            </tr> <tr>
                <td>Available Quantity</td>
                <td>'.Helper::available_product_quantity($product->product_id).' '.$product->product_unit.'</td>
            </tr> <tr>
                <td>Base Price</td>
                <td>'.$product->product_base_price.'</td>
            </tr> <tr>
                <td>Tax (%)</td>
                <td>'.$product->product_tax.'</td>
            </tr> <tr>
                <td>Enter By</td>
                <td>'.$product->user->username.'</td>
            </tr> <tr>
                <td>Status</td>
                <td><span class="badge badge-'.($product->product_status == "inactive"?'danger">Not available':'success">Available').'</span></td>
            </tr></table></div>';
            return response()->json($output);
        }


    public function edit(Product $product)
    {
        return response()->json($product);
    }


    public function update(Request $request, Product $product)
    {
        if(!$request->has('product_status')){
            $this->validate($request,[
                'product_name' => ['required','max:255'],
                'category_id' => ['required', 'numeric'],
                'brand_id'=>['required','numeric'],
                'product_base_price'=>['required','numeric'],
                'product_tax'=>['required','numeric'],
            ]);
        }
        if($request->hasFile('product_image'))
            $this->fields['product_image']=basename($request->file('product_image')->store('public/product_images'));
        $product->update(array_merge($request->all(), $this->fields));
        return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">Product Details Updated Successfully</div>'));
    }


  public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(array('response'=>'<div class="alert alert-success">The data was deleted!</div>'));
    }
}
