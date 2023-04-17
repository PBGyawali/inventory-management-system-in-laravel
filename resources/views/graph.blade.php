@include('layouts.header')
		<div class="row ">
			<div class="col">
				@include('chart',['month'=>$month,'single_value'=>$monthvalue,'element'=>'sale','fullmonth'=>$fullmonth,'full_value'=>$fullmonthvalue,'refreshurl'=>route('graph').'/edit'])
			</div>
        </div>
		<div class="row ">
			<div class="col">
				@include('chart',['month'=>$month,'single_value'=>$monthvalue_purchase,'count'=>'1','element'=>'purchase','fullmonth'=>$fullmonth,'full_value'=>$fullmonthvalue_purchase,'refreshurl'=>route('graph').'/edit'])
			</div>
		</div>
		<div class="row ">
			<div class="col">
				@include('chart',['month'=>$month,'single_value'=>$monthvalue_sale_revenue,'count'=>'2','element'=>'sale','unit'=>'revenue','fullmonth'=>$fullmonth,'full_value'=>$fullmonthvalue_sale_revenue,'refreshurl'=>route('graph').'/edit'])
			</div>
		</div>
		<div class="row ">
			<div class="col">
				@include('chart',['month'=>$month,'single_value'=>$monthvalue_purchase_revenue,'count'=>'3','element'=>'purchase','fullmonth'=>$fullmonth,'unit'=>'revenue','full_value'=>$fullmonthvalue_purchase_revenue,'refreshurl'=>route('graph').'/edit'])
			</div>
		</div>
        <div class="row ">
			<div class="col">
				@include('chart',['month'=>$category,'single_value'=>$categoryvalue,'count'=>'4','element'=>'product','fullmonth'=>$allcategory,'full_value'=>$allcategoryvalue,'refreshurl'=>route('graph').'/edit','type'=>'bar'])
			</div>
		</div>

		@include('page-footer',['company_name'=>$info->company_name])

<script type="text/javascript" src="<?php echo env('JS_URL')?>chart.min.js"></script>
<script type="text/javascript" src="<?php echo env('JS_URL')?>jquery.easing.min.js"></script>
<script type="text/javascript" src="<?php echo env('JS_URL')?>theme.js"></script>
@include('layouts.footer')
<script type="text/javascript" src="<?php echo env('JS_URL')?>graph.js"></script>
