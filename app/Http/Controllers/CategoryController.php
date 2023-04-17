<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
class CategoryController extends Controller
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
            $data = Category::all();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    // primary key of the row
                    $id=$data->category_id;
                    // status of the row
                    $status=$data->category_status;
                    // data to display on modal, tables
                    $prefix="category";
                    // message to display on change status button
                    $statusbutton=$status=="active"?"Disable":"Enable";
                    // button class of change status button
                    $status_class=$status=="active"?"danger":"success";
                    // optional button to display
                    $buttons=[];
                    //render buttons from view
                    $actionBtn = view('control-buttons',compact('buttons','id','status','prefix','statusbutton','status_class'))->render();
                    return $actionBtn;
                })
                ->editColumn('category_status', function ($data) {
                    $status =$data->category_status;
                    $class=$status == 'active'?'success':'danger';
                    //render status with css from view
                    return view('badge',compact('status','class'))->render();
                })
                ->make(true);
        }
        $info=$this->companyInfo;
        $page='category';
        return view('category',compact('info','page' ) );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category_name' => ['required', 'string', 'max:255','unique:categories'],
        ]);
        Category::create($request->all());
        return response()->json(['response'=>__('message.create',['name'=>'category'])]);       
    }

    public function edit(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        if (!$request->hasAny('category_status')){
            $this->validate($request, [
                'category_name' => ['required','max:255',Rule::unique('categories')->ignore($category)],
            ]);
        }
        $category->update($request->all());
        return response()->json(['response'=>__('message.update',['name'=>'category'])]);   
    }


    /**
     * Deletes a category and its related brands from the database.
     * 
     * @param Category $category The category to delete.
     * @return Illuminate\Http\JsonResponse A JSON response indicating whether the delete was successful or not.
     */

    public function destroy(Category $category)
    {
        // start a database transaction 
        //  to ensure either all database changes are made or none of them.
         DB::beginTransaction();

        try {
            //delete related brands to avoid foreign id constraints
            $category->brands->each->delete();
            // Delete the category
            $category->delete();
            // Commit the transaction if everything is good
            DB::commit();
            // Return a success response
            return response()->json(['response'=>__('message.delete',['name'=>'product'])]);            
        } catch (\Exception $e) {
            // Roll back the transaction if error occurs
            DB::rollBack();
              // Handle the exception and return an error response
              return response()->json(['error'=>__('message.error.delete',['reason'=>$e->getMessage()])]);           
        }
    }
}
