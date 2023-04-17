<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
class SupplierController extends Controller
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
            $data = Supplier::all();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    // primary key of the row
                    $id=$data->supplier_id;
                    // status of the row
                    $status=$data->supplier_status;
                    // data to display on modal, tables
                    $prefix="supplier";
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
                ->editColumn('supplier_status', function ($data) {
                        $status =$data->supplier_status;
                        $class=$status == 'active'?'success':'danger';
                        //render status with css from view 
                        return view('badge',compact('status','class'))->render();
                    })
                ->make(true);
        }
        $info=$this->companyInfo;
        $page='supplier';
        return view('supplier',compact('info','page') );
    }

    public function store(Request $request)
    {
         // Validate input data
        $this->validate($request, [
                'supplier_name' => ['required','max:255',Rule::unique('suppliers')],
                'supplier_email' => ['required','max:255',Rule::unique('suppliers')],
                'supplier_contact_no' => ['required','max:255'],
        ]);
        // Create a new supplier record with the data from the request
        Supplier::create($request->all());
        // Return JSON response with success message from translation string
        return response()->json(['response'=>__('message.create',['name'=>'supplier'])]);
    }


    /**
     * Prepares a Supplier model to be edited by the user in the frontend.
     *
     * @param Supplier $supplier The Supplier model to be edited.
     * @return \Illuminate\Http\JsonResponse A JSON response with the Supplier data
     */
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
                'supplier_contact_no' => ['required','max:255'],
            ]);
        }
        // Update supplier data
        $supplier->update($request->all());
        // Return JSON response with success message from translation string
        return response()->json(['response'=>__('message.update',['name'=>'supplier'])]);       
    }


  public function destroy(Supplier $supplier)
    {

        // Delete the supplier data
        $supplier->delete();
        // Return JSON response with success message from translation string
        return response()->json(['response'=>__('message.delete',['name'=>'supplier'])]);        
    }
}
