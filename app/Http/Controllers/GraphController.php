<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helper\Helper;
class GraphController extends Controller
{
    public $companyInfo=array();
    public $query='';

    public function __construct()
    {
        $this->companyInfo=CompanyInfo::first();
    }
    public function index(Request $request)
    {
        $info=$this->companyInfo;
        $allcategory= $this->category();
        $allcategoryvalue=$this->categoryvalue();
        $category= $this->category(true);
        $categoryvalue=$this->categoryvalue(true);
        $categoryvaluehtml=$this->categoryvaluehtml(true);
        $fullmonth=$this->getfullmonth();
        $month=$this->getmonth();
        $fullmonthvalue=$this->getfullmonthvalue();
        $monthvalue=$this->getmonthvalue();
        $monthvalue_purchase=$this->getmonthvalue('purchase');
        $fullmonthvalue_purchase=$this->getfullmonthvalue('purchase');
        $monthvalue_sale_revenue=$this->getmonthvalue('sale','revenue');
        $fullmonthvalue_sale_revenue=$this->getfullmonthvalue('sale','revenue');
        $monthvalue_purchase_revenue=$this->getmonthvalue('purchase','revenue');
        $fullmonthvalue_purchase_revenue=$this->getfullmonthvalue('purchase','revenue');
        $page='graph';
        return view('graph',compact('info','page',
         'fullmonthvalue','fullmonth','month','monthvalue',
         'category','categoryvalue','allcategory','allcategoryvalue','categoryvaluehtml',
        'monthvalue_purchase','fullmonthvalue_purchase',
        'monthvalue_sale_revenue','fullmonthvalue_sale_revenue',
        'monthvalue_purchase_revenue','fullmonthvalue_purchase_revenue',
        )
        );
    }

    function category($status=null)	{
		return $this->get_category('&quot;','category_name',$status);
	}

	function categoryvalue($status=null){
		return $this->get_category_value('&quot;',$status);
	}

	function categoryhtml()	{
		return $this->get_category('','category_name',true);
	}

	function categoryvaluehtml(){
		return $this->get_category_value('',true);
	}
	function get_category($quote=null,$value=null,$join=null){
        $allcategory= Category::ordergroup();
        if($join)
            $allcategory=$allcategory->joinRelationship('products');
        $allcategory=$allcategory->get();
        $data=$allcategory->pluck($value);
            foreach( $data as $collection)
                $returnvalue[]=$quote.$collection.$quote;
        if($quote)
            return implode(',', $returnvalue);
        return $data;
	}

	function get_category_value($quote=null,$null=null){
		$value=array();
		$category_ids=$this->get_category('','category_id',$null);
		foreach($category_ids as $category_id){
            $product_quantity=$this->total('products','product_quantity','category_id',$category_id);
            $opening_stock=$this->total('products','opening_stock','category_id',$category_id);
            $defective_quantity=$this->total('products','defective_quantity','category_id',$category_id);
			array_push($value,$quote .($product_quantity+$opening_stock-$defective_quantity).$quote);
		}
		if ($quote)
			return implode(',',$value);
		return $value;
    }


    public function edit(Request $request)
    {
        $table=$request->table;
        $type=$request->type;
        if($table=='product')
        {
          $data=$this->categoryvaluehtml();
          $labels=$this->categoryhtml();
        }
        else
        {
          $data=$this->getfullmonthvaluehtml($table,$type);
          $labels=$this->getfullmonthhtml();
        }
        return response()->json(array('labels'=>$labels,'data'=>$data));
    }

    function getfullmonthhtml(){
		return $this->loopfullmonth();
	}
	function getfullmonth(){
		return $this->loopfullmonth("&quot;");
	}
	function getmonthhtml()	{
		return $this->loopmonth();
	}
	function getmonth()	{
		return $this->loopmonth('&quot;');
    }

    function loopfullmonth($quote=null)
	{	$value=array();
		$startpos = date('n');
		for($i=1;$i<=12;$i++)
			array_push($value,$quote .substr(date('F', mktime(0, 0, 0, ($i), 2, date('Y'))),0,3).$quote);
		$output = array_merge(array_slice($value,$startpos), array_slice($value, 0, $startpos));
		if ($quote)
			return implode(',',$output);
		return $output;
	}
	function loopmonth($quote=null,$start=1){
		$months=array();
		for($i=$start;$i<=date('n');$i++)
			array_push($months,$quote.substr(date('F', mktime(0, 0, 0, ($i), 2, date('Y'))),0,3).$quote);
		if ($quote)
			return  implode(',',$months);
		return  $months;
	}
	function getmonthvalue($table='sale',$type='number'){
		return  $this->loopmonthvalue($table,$type,'&quot;');
	}
	function getmonthvaluehtml($table='sale',$type='number'){
		return $this-> loopmonthvalue($table,$type);
	}
	function loopmonthvalue($table='sale',$type='number',$quote=null)
	{	$value=array();
		for($i=1;$i<=date('n');$i++)
			array_push($value,$quote .$this->getValuePerMonth($i,$table,$type).$quote);
		if ($quote)
			return  implode(',',$value);
		return  $value;
	}
	function getfullmonthvalue($table='sale',$type='number'){
		return $this->loopfullmonthvalue($table,$type,"&quot;");
	}
	function getfullmonthvaluehtml($table='sale',$type='number'){
		return $this->loopfullmonthvalue($table,$type);
	}
	function loopfullmonthvalue($table='sale',$type='number',$quote=null)
	{	$value=array();
		$startpos = date('n');
		for($i=1;$i<=12;$i++)
			array_push($value,$quote .$this->getValuePerMonth($i,$table,$type).$quote);
		$output = array_merge(array_slice($value,$startpos), array_slice($value, 0, $startpos));
		if ($quote)
			return implode(',',$output);
		return $output;
	}

	function getValuePerMonth($value,$table='sale',$type='number'){
        $condition['raw']= 'MONTH(created_at)';
        $values['raw']=$value;
		if($type=='number')
            return Helper::CountTable($table.'s',$condition,$values);
		else
            return Helper::total($table.'s',$table.'_sub_total',$condition,$values);
	}

    function total($table=null,$column=null,$placeholder=null,$value=null,$join=array(),$attr=array()){
         return Helper::total($table,$column,$placeholder,$value,$join,$attr);
    }

}
