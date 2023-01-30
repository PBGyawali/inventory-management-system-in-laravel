<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
class CategoryController extends Controller
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
            $data = Category::all();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                $actionBtn = '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Menu
                </button>
                <ul class="dropdown-menu" >
                  <li><button type="button" name="update" id="'.$data->category_id.'"  data-prefix="Category" class="w-100 mb-1 text-center btn btn-info btn-sm update"><i class="fas fa-edit"></i> Update</button></li>
                  <li><button type="button" name="delete" id="'.$data->category_id.'"  data-prefix="category" class="w-100 btn btn-primary btn-sm status" data-status="'.$data->category_status.'">Change Status</button></li>
                </ul>
              </div>';
                    return $actionBtn;
                })
                ->editColumn('category_status', function ($data) {
                    if($data->category_status == 'active')
                        $status = '<span  class="badge badge-success btn-sm">'.$data->category_status.'</span>';
                    else
                        $status = '<span  class="badge badge-danger btn-sm">'.$data->category_status.'</span>';
                        return $status;
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
       return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The category was created!</div>'));
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
        return response()->json(array('error'=>'','response'=>'<div class="alert alert-success">The Category was updated!</div>'));

    }

  public function destroy(Category $category)
    {
        $category->brands->each->delete();
        $category->delete();
        return response()->json(array('response'=>'<div class="alert alert-success">The data was deleted!</div>'));
    }
}
