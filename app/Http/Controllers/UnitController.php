<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
class UnitController extends Controller
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
            $data = Unit::all();
            return DataTables::of($data)
               ->addColumn('action', function($data){
                $actionBtn = '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Menu
                </button>
                <ul class="dropdown-menu" >
                  <li><button type="button"  data-id="'.$data->unit_id.'" id="'.$data->unit_id.'"  data-prefix="Unit" class="w-100 mb-1 text-center btn btn-info btn-sm update"><i class="fas fa-edit"></i> Update</button></li>
                  <li><button type="button"  data-id="'.$data->unit_id.'" id="'.$data->unit_id.'"  data-prefix="unit" class="w-100 btn mb-1 '.(($data->unit_status=="active")?'btn-warning':' btn-success').' btn-sm status" data-status="'.$data->unit_status.'">'.(($data->unit_status=="active")?"Disable":"Enable").'</button></li>
                  <li><button type="button"  data-id="'.$data->unit_id.'" id="'.$data->unit_id.'"  class="w-100 btn btn-danger btn-sm delete" >Delete</button></li>
                </ul>
              </div>';
                    return $actionBtn;
                })
                ->editColumn('unit_status', function ($data) {
                    $status = '<span  class="badge badge-danger btn-sm">'.$data->unit_status.'</span>';
                    if($data->unit_status == 'active')
                        $status = '<span  class="badge badge-success btn-sm">'.$data->unit_status.'</span>';
                        return $status;
                     })
                ->make(true);
        }
        $info=$this->companyInfo;
        $page='unit';
        return view('unit',compact('info','page' ) );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'unit_name' => ['required','max:255','unique:units'],
        ]);
        Unit::create($request->all());
       return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The Unit was created!</div>'));
    }

    public function edit(Unit $unit)
    {
        return response()->json($unit);
    }


    public function update(Request $request, Unit $unit)
    {
        if(!$request->hasAny('status','unit_status'))
        {
             $this->validate($request, [
            'unit_name' => ['required','max:255',Rule::unique('units')->ignore($unit)]]);
        }
            $unit->update($request->all());
        return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The unit data was updated!</div>'));
    }


  public function destroy(Request $request,Unit $unit)
    {
        $unit->delete();
        return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The unit name was deleted!</div>'));
    }
}
