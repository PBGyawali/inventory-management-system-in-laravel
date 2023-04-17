<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Helper\Select;
use Yajra\DataTables\Facades\DataTables;
class BrandController extends Controller
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

        if ($request->ajax()) {
            //get all brands with their respective category
            $data = Brand::with("category")->get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    // primary key of the row
                    $id=$data->brand_id;
                    // status of the row
                    $status=$data->brand_status;
                    // data to display on modal, tables
                    $prefix="brand";
                    // message to display on change status button
                    $statusbutton=$status=="active"?"Disable":"Enable";
                    // button class of change status button
                    $status_class=$status=="active"?"warning":"success";
                    // optional button to display
                    $buttons=['delete'];
                    //render buttons from view
                    $actionBtn = view('control-buttons',compact('buttons','id','prefix','status','statusbutton','status_class'))->render();
                    return $actionBtn;
                })
                ->editColumn('brand_status', function ($data) {
                    $status =$data->brand_status;
                    $class=$status == 'active'?'success':'danger';
                    //render status with css from view
                    return view('badge',compact('status','class'))->render();
                 })
                ->make(true);
        }
        $info=$this->companyInfo;
        $page='brand';
        $category_list=Select::instance()->category_list();
        return view('brand',compact('info','page','category_list' ) );
    }

    public function store(Request $request)
    {
       // Validate the request data
        $this->validate($request, [
            'brand_name' => ['required', 'string', 'max:255', 'unique:brands'],
        ]);

        // Create a new Brand model instance and store it in the database
        Brand::create($request->all());

        // Return a JSON response indicating successful creation of the brand using a translation string
        return response()->json(['response' => __('message.create', ['name' => 'brand'])]);

    }

    public function edit(Brand $brand)
    {
        return response()->json($brand);
    }


    public function update(Request $request, Brand $brand)
    {
        // validate the request data If 'brand_status' field is not present
        if (!$request->hasAny('brand_status','status')){
            $this->validate($request, [
                'brand_name' => ['required', 'string', 'max:255', Rule::unique('brands')->ignore($brand)],
            ]);
        }

        // Update the existing Brand model instance with the request data
        $brand->update($request->all());

        // Return a JSON response indicating successful update of the brand
        return response()->json(['response' => __('message.update', ['name' => 'brand'])]);

    }

  public function destroy(Request $request,Brand $brand)
    {
        $brand->delete();
        return response()->json(['response'=>__('message.delete',['name'=>'brand'])]);
    }
}
