<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Rules\UniqueSingularOrPlural;

class UnitController extends Controller
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
            $data = Unit::all();
            return DataTables::of($data)
               ->addColumn('action', function($data){
                // primary key of the row
                $id=$data->getKey();
                // status of the row
                $status=$data->unit_status;
                // data to display on modal, tables
                $prefix="unit";
                // message to display on change status button
                $statusbutton=$status=="active"?"Disable":"Enable";
                // button class of change status button
                $status_class=$status=="active"?"warning":"success";
                // optional button to display
                $buttons=['delete'];
                //render action button from view
                $actionBtn = view('control-buttons',compact('buttons','id','status','prefix','statusbutton','status_class'))->render();
                return $actionBtn;
                })
                ->editColumn('unit_status', function ($data) {
                    $status =$data->unit_status;
                    $class=$status == 'active'?'success':'danger';
                    //render status with css from view
                    return view('badge',compact('status','class'))->render();
                  })
                ->make(true);
        }
        
        return view('unit' );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'unit_name' => ['required','max:255',
            new UniqueSingularOrPlural('units'),
            ],
        ]);
        Unit::create($request->all());
        // Return JSON response with success message from translation string
        return response()->json(['response'=>__('message.create',['name'=>'unit'])]);
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
            'unit_name' => ['required','max:255',
                            new UniqueSingularOrPlural('units',$unit),
                            ]
        ]);
        }
            $unit->update($request->all());
            // Return JSON response with success message from translation string
            return response()->json(['response'=>__('message.update',['name'=>'unit'])]);
    }


  public function destroy(Request $request,Unit $unit)
    {
        $unit->delete();
        // Return JSON response with success message from translation string
        return response()->json(['response'=>__('message.delete',['name'=>'unit'])]);
    }
}
