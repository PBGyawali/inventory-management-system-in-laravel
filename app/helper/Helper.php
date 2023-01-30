<?php
namespace App\Helper;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
class Helper
{
    public static function instance()
    {
        return new Helper();
    }

    public static function available_product_quantity($product_id){
        $product_data = Product::find($product_id);
        $available_quantity = intval($product_data->opening_stock)+intval($product_data->product_quantity)
        -intval($product_data->defective_quantity);
		return $available_quantity;
	}
	static function get_product_quantity($product_id,$table){
        $join=array( array($table.'s',$table.'s.'.$table."_id","product_".$table.'s.'.$table."_id"));
		return self::total("product_".$table.'s',"product_".$table.'s'.".quantity",
		array("product_".$table.'s'.".product_id"),	array($product_id),$join);
    }

    public static function total($table=null,$column=null,$placeholder=null,$value=null,$join=array(),$attr=array())
	{
        $query = DB::table($table);
        $compare='=';
        $placeholder=self::check_array($placeholder);
		$value=self::check_array($value);
		if($join)
        foreach($join as $key=> $joinattribute)
        {
            $query->leftjoin($joinattribute[0],$joinattribute[1],$joinattribute[2]);
        }
        if($placeholder)
        foreach($placeholder as $key=> $columnname)
        {
            if($key==='raw')
                $query->whereRaw("$columnname".(is_array($compare)?$compare[$key]:$compare)."'".$value[$key] ."'".(isset($attr['interval'])?$attr['interval']:' '));
            else
                $query->where($columnname, is_array($compare)?$compare[$key]:$compare, $value[$key]);
        }
        if(isset($attr['selectraw']))
            $query->selectRaw($attr['selectraw']);
        if(isset($attr['select']))
            foreach($attr['select'] as $key=> $columnname){
                $query->addselect($columnname);
            }
		if(isset($attr['groupby']))
            $query->groupby($attr['groupby']);
		if(isset($attr['groupby']))
            return $query->get();
        if(isset($attr['rawsum']))
            return $query->first();
		return $query->sum($column);
	}

    private static function check_array($value){
		if (is_array($value))
			return $value;
		return array($value);
	}
    public static function CountTable($table,$condition=null,$value=null,$compare='=',$attr=array()){
        $query = DB::table($table);
        $condition=self::check_array($condition);
        $value=self::check_array($value);
        if($condition)
        foreach($condition as $key=> $column)
        {
            if($key==='raw')
                $query->whereRaw("$column".(is_array($compare)?$compare[$key]:$compare)."'".$value[$key] ."'".(isset($attr['interval'])?$attr['interval']:' '));
            else
                $query->where($column, is_array($compare)?$compare[$key]:$compare, $value[$key]);
        }
       //return $query->toSql();
        return $query->count();
    }
}
