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
    public $companyInfo=array();
    public $query='';

    public function __construct()
    {
        $this->companyInfo=CompanyInfo::first();
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Brand::with("category");
            return DataTables::of($data)
                ->addColumn('action', function($data){
                $actionBtn = '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Menu
                </button>
                <ul class="dropdown-menu" >
                  <li><button type="button" id="'.$data->brand_id.'" data-id="'.$data->brand_id.'" data-prefix="Brand" class="w-100 mb-1 text-center btn btn-info btn-sm update"><i class="fas fa-edit"></i> Update</button></li>
                  <li><button type="button" id="'.$data->brand_id.'" data-id="'.$data->brand_id.'" data-prefix="brand" class="w-100 btn mb-1 '.(($data->brand_status=="inactive")?'btn-success':' btn-warning').' btn-sm status" data-status="'.$data->brand_status.'">'.(($data->brand_status=="active")?"Disable":"Enable").'</button></li>
                  <li><button type="button" id="'.$data->brand_id.'" data-id="'.$data->brand_id.'" class="w-100 btn btn-danger btn-sm delete" >Delete</button></li>
                </ul>
              </div>';
                    return $actionBtn;
                })
                ->editColumn('brand_status', function ($data) {
                    if($data->brand_status == 'active')
                        $status = '<span  class="badge badge-success btn-sm">'.$data->brand_status.'</span>';
                    else
                        $status = '<span  class="badge badge-danger btn-sm">'.$data->brand_status.'</span>';
                        return $status;
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
        $this->validate($request, [
            'brand_name' => ['required', 'string', 'max:255','unique:brands'],
        ]);
        Brand::create($request->all());
       return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The brand was created!</div>'));
    }

    public function edit(Brand $brand)
    {
        return response()->json($brand);
    }


    public function update(Request $request, Brand $brand)
    {
        if (!$request->hasAny('brand_status','status')){
        $this->validate($request, [
            'brand_name' => ['required', 'string', 'max:255',Rule::unique('brands')->ignore($brand)]]);
    }
        $brand->update($request->all());
        return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The brand was updated!</div>'));
    }

  public function destroy(Request $request,Brand $brand)
    {
        $brand->delete();
        return response()->json(array('response'=>'<div class="alert alert-success">The data was deleted!</div>'));
    }
}
