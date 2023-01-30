<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
class SupplierController extends Controller
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
            $data = Supplier::all();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                $actionBtn = '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Menu
                </button>
                <ul class="dropdown-menu" >
                  <li><button type="button" id="'.$data->supplier_id.'"  data-prefix="Supplier" class="w-100 mb-1 text-center btn btn-info btn-sm update"><i class="fas fa-edit"></i> Update</button></li>
                  <li><button type="button" id="'.$data->supplier_id.'" data-id="'.$data->supplier_id.'" data-prefix="supplier" class="w-100 btn mb-1 '.(($data->supplier_status=="inactive")?'btn-success':' btn-warning').' btn-sm status" data-status="'.$data->supplier_status.'">'.(($data->supplier_status=="active")?"Disable":"Enable").'</button></li>
                  <li><button type="button" id="'.$data->supplier_id.'" data-id="'.$data->supplier_id.'" class="w-100 btn btn-danger btn-sm delete" >Delete</button></li>
                </ul>
              </div>';
                    return $actionBtn;
                })
                ->editColumn('supplier_status', function ($data) {
                    $status = '<span  class="badge badge-danger btn-sm">'.$data->supplier_status.'</span>';
                    if($data->supplier_status == 'active')
                        $status = '<span  class="badge badge-success btn-sm">'.$data->supplier_status.'</span>';
                        return $status;
                     })
                ->make(true);
        }
        $info=$this->companyInfo;
        $page='supplier';
        return view('supplier',compact('info','page') );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
                'supplier_name' => ['required','max:255',Rule::unique('suppliers')],
                'supplier_email' => ['required','max:255',Rule::unique('suppliers')],
        ]);
        Supplier::create($request->all());
       return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The supplier was created!</div>'));
    }

    public function edit(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    public function update(Request $request, Supplier $supplier)
    {
        if (!$request->hasAny('supplier_status')){
            $this->validate($request, [
                'supplier_name' => ['required','max:255',Rule::unique('suppliers')->ignore($supplier)],
                'supplier_email' => ['required','max:255',Rule::unique('suppliers')->ignore($supplier)],
            ]);
        }
        $supplier->update($request->all());
        return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The supplier was updated!</div>'));
    }


  public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(array('response'=>'<div class="alert alert-success">The data was deleted!</div>'));
    }
}
