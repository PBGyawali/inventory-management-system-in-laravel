@include('config')
@include('layouts.header')
<?php $currency=$info->currency_symbol;?>
	<br />
			   <div class="row ">
				   <div class="col mb-4">
					   <div class="card shadow border-left-info py-2">
						   <div class="card-body">
							   <div class="row align-items-center no-gutters">
								   <div class="col mr-2">
									   <div class="text-uppercase text-info font-weight-bold text-xs mb-1"><span>Total sales Target</span></div>
									   <div class="row no-gutters align-items-center">
										   <div class="col value-indicator">
											   <div class="progress progress-sm">
												   <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" id ="progressbar_1" aria-valuenow="<?php echo $sales_target;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $sales_target;?>%;"><span class="sr-only"><?php echo $sales_target;?>%</span></div>
											   </div>
										   </div>
										   <div class="col-auto value-indicator-text">
											   <div class="text-dark text-right font-weight-bold h5 mb-0 ml-3"><span class="progress-value"><?php echo $sales_target;?>%</span></div>
										   </div>
									   </div>
								   </div>
								   <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
							   </div>
						   </div>
                       </div></div>

			   </div>
			   <div class="row ">
				   <div class="col mb-4">
					   <div class="card shadow border-left-info py-2">
						   <div class="card-body">
							   <div class="row align-items-center no-gutters">
								   <div class="col mr-2">
									   <div class="text-uppercase text-info font-weight-bold text-xs mb-1"><span>Total revenue Target</span></div>
									   <div class="row no-gutters align-items-center">
										   <div class="col value-indicator">
											   <div class="progress progress-sm">
												   <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" id ="progressbar_1" aria-valuenow="<?php echo $revenue_target;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $revenue_target;?>%;"><span class="sr-only"><?php echo $revenue_target;?>%</span></div>
											   </div>
										   </div>
										   <div class="col-auto value-indicator-text">
											   <div class="text-dark text-right font-weight-bold h5 mb-0 ml-3"><span class="progress-value"><?php echo $revenue_target;?>%</span></div>
										   </div>
									   </div>
								   </div>
								   <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
							   </div>
						   </div>
					   </div></div>
			   </div>

		<div class="row ">
                <div class="col">
                    <div class="card shadow border-left-info py-2">
                        <div class="card-header text-center text-uppercase text-info"><strong>Total sales transaction Value achieved by each User (As on <?= '<span class="text-lowercase">'.date('jS').'</span>' .', '.date('F').', '.date('Y'); ?>)</strong></div>
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

		<div class="row ">
                <div class="col">
                    <div class="card shadow border-left-info py-2">
                    <div class="card-header text-center text-uppercase text-info"><strong>Total purchase transaction Value achieved by each User (As on <?= '<span class="text-lowercase">'.date('jS').'</span>' .', '.date('F').', '.date('Y'); ?>)</strong></div>
                        <div class="card-body text-center" >
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th class="text-center text-uppercase">Name</th>
                                        <th class="text-center text-uppercase">Total  Deals</th>
                                        <th class="text-center text-uppercase">Total purchase</th>
                                        <th class="text-center text-uppercase">Total Cash purchase</th>
                                        <th class="text-center text-uppercase">Total Credit purchase</th>
                                    </tr>
                                    <?php echo $user_wise_total_purchase ?>
                        </div>
                    </div>
                </div>
		</div>

		<footer class="bg-white sticky-footer mt-3">
			<div class="container my-auto">
				<div class="text-center my-auto copyright">
					<span>Copyright © <?php echo $info->company_name?>
						<script>
							document.write(new Date().getFullYear())
						</script>
					</span>
				</div>
				<a class="no-border fixed-bottom text-right size-30 scroll-to-top" data-href="#page-top"><i class="fas  fa-4x fa-angle-up"></i></a>
			</div>
        </footer>

<script type="text/javascript" src="<?php echo JS_URL?>chart.min.js"></script>
<script type="text/javascript" src="<?php echo JS_URL?>jquery.easing.min.js"></script>
<script type="text/javascript" src="<?php echo JS_URL?>theme.js"></script>
@include('layouts.footer')
<script src= "<?php echo JS_URL .'progressbar_bootstrap.js'?>"></script>
