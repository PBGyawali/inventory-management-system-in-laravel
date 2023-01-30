<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
class TaxController extends Controller
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
            $data = Tax::get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                $actionBtn = '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Menu
                </button>
                <ul class="dropdown-menu" >
                  <li><button type="button"  data-id="'.$data->tax_id.'"  id="'.$data->tax_id.'" data-prefix="Tax" class="w-100 mb-1 text-center btn btn-info btn-sm edit_button update"><i class="fas fa-edit"></i> Update</button></li>
                  <li><button type="button"  data-id="'.$data->tax_id.'" id="'.$data->tax_id.'" data-prefix="tax" class="w-100 btn mb-1 '.(($data->tax_status=="active")?'btn-warning':' btn-success').' btn-sm status" data-status="'.$data->tax_status.'">'.(($data->tax_status=="active")?"Disable":"Enable").'</button></li>
                  <li><button type="button"  data-id="'.$data->tax_id.'" id="'.$data->tax_id.'" class="w-100 btn btn-danger btn-sm delete" ><i class="fas fa-times"></i> Delete</button></li>
                </ul>
              </div>';
                    return $actionBtn;
                })
                ->editColumn('tax_status', function ($data) {
                    if($data->tax_status == 'active')
                    $status = '<button type="button" name="status_button" class="badge btn-success badge-sm status_button" data-id="'.$data->tax_id.'" data-status="'.$data->tax_status.'">Active</button>';
                else
                    $status = '<button type="button" name="status_button" class="badge btn-danger badge-sm status_button" data-id="'.$data->tax_id.'" data-status="'.$data->tax_status.'">Inactive</button>';

                        return $status;
                     })
                ->make(true);
        }

        $info=$this->companyInfo;
        $page='tax';
        return view('tax',compact('info','page' ) );
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'tax_name' => ['required', 'max:255','unique:taxes'],
            'tax_percentage' => ['required','numeric','between:0,99.99'],
        ]);
        Tax::create($request->all());
       return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The tax was created!</div>'));
    }

    public function edit(Tax $tax)
    {
        return response()->json($tax);
    }

    public function update(Request $request, Tax $tax)
    {
        if (!$request->hasAny('tax_status','status')){
        $this->validate($request, [
            'tax_name' => ['required','max:255',Rule::unique('taxes')->ignore($tax)],
            'tax_percentage' => ['required','numeric','between:0,99.99'],
            ]);
        }
        $tax->update($request->all());
        return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The tax was updated!</div>'));
    }

  public function destroy(Request $request,Tax $tax)
    {
        $tax->delete();
        return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The tax was deleted!</div>'));
    }
}
