@include('layouts.header')
<?php $currency=$info->currency_symbol;?>
	<br />
			   <div class="row ">
				   <div class="col mb-4">
					   @include('progressbar',['heading'=>'total sales target','value'=>$sales_target,'count'=>'1'])
					</div>
			   </div>
			   <div class="row ">
				   <div class="col mb-4">
					@include('progressbar',['heading'=>'Total revenue Target','value'=>$revenue_target,'count'=>'2'])
					</div>
			   </div>

		<div class="row ">
                <div class="col">
                    <div class="card shadow border-left-info py-2">
                        <div class="card-header text-center text-uppercase text-info">
                            <strong>Total sales transaction Value achieved by each User (As on <?= '<span class="text-lowercase">'.date('jS').'</span>' .', '.date('F').', '.date('Y'); ?>)</strong>
                        </div>
                        <div class="card-body text-center" >
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th class="text-center text-uppercase">Name</th>
                                        <th class="text-center text-uppercase">Total  Deals</th>
                                        <th class="text-center text-uppercase">Total Sale Value</th>
                                        <th class="text-center text-uppercase">Total Cash Sale</th>
                                        <th class="text-center text-uppercase">Total Credit Sale</th>
                                    </tr>
                                    <?php echo $user_wise_total_sales ?>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
		<div class="row ">
                <div class="col">
                    <div class="card shadow border-left-info py-2">
                        <div class="card-header text-center text-uppercase text-info">
                            <strong>Total purchase transaction Value achieved by each User (As on <?= '<span class="text-lowercase">'.date('jS').'</span>' .', '.date('F').', '.date('Y'); ?>)</strong>
                        </div>
                        <div class="card-body text-center" >
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th class="text-center text-uppercase">Name</th>
                                        <th class="text-center text-uppercase">Total Deals</th>
                                        <th class="text-center text-uppercase">Total purchase</th>
                                        <th class="text-center text-uppercase">Total Cash purchase</th>
                                        <th class="text-center text-uppercase">Total Credit purchase</th>
                                    </tr>
                                    <?php echo $user_wise_total_purchase ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		@include('page-footer',['company_name'=>$info->company_name])
<script type="text/javascript" src="<?php echo env('JS_URL')?>jquery.easing.min.js"></script>
<script type="text/javascript" src="<?php echo env('JS_URL')?>theme.js"></script>
@include('layouts.footer_script')
<script src= "<?php echo env('JS_URL') .'progressbar_bootstrap.js'?>"></script>
