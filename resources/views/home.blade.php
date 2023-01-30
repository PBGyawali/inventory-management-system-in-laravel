@include('config')
@include('layouts.header')
@php $currency=$info->currency_symbol @endphp
@include('components.message')
  <div class="row pt-5">

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Sales Transaction (Today)</div>
                                            <div class="h5 mb-0 font-weight-bold text-center"><?= $today_sales; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                 Sales Transaction (Yesterday)</div>
                                            <div class="h5 mb-0 font-weight-bold text-center"><?= $yesterday_sales ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sales Transaction (Last 7 Day)
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-center"><?= $last_seven_day_sales ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                All Time Sales Transaction</div>
                                            <div class="h5 mb-0 font-weight-bold text-center"><?= $total_sales?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


    <?php if(auth()->user()->is_admin()):?>
    <div class="row">
	<div class="col-md-3 mb-4">
		<div class="card shadow border-left-info py-2">
			<div class="card-header text-center text-uppercase font-weight-bold text-primary"><strong>Total Active Users</strong></div>
			<div class="card-body text-center" >
				<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $total_user ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
				</div>
			</div>

		</div>
	</div>
	<div class="col-md-3 mb-4">
		<div class="card shadow border-left-info py-2">
			<div class="card-header text-center text-uppercase font-weight-bold text-success"><strong>Total Active Category</strong></div>
			<div class="card-body text-center" >

				<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $total_category ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-sitemap fa-2x text-gray-300"></i></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3 mb-4">
		<div class="card shadow border-left-info py-2">
			<div class="card-header text-center text-uppercase font-weight-bold text-success"><strong>Total Active Brands</strong></div>
			<div class="card-body text-center" >

				<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $total_brand ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-list fa-2x text-gray-300"></i></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3 mb-4">
		<div class="card shadow border-left-info py-2">
			<div class="card-header text-center text-uppercase font-weight-bold text-warning"><strong>Total Item in Stock</strong></div>
			<div class="card-body text-center" >

				<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $total_product ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-warehouse fa-2x text-gray-300"></i></div>
				</div>
			</div>
		</div>
    </div>
    <hr />
    </div>

    <div class="row">
	<div class="col mb-4">
		<div class="card shadow border-left-info py-2">
			<div class="card-header text-center text-uppercase font-weight-bold text-primary"><strong>Total Active Supplier</strong></div>
			<div class="card-body text-center" >
				<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $total_supplier ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
				</div>
			</div>

		</div>
	</div>
	<div class="col mb-4">
		<div class="card shadow border-left-info py-2">
			<div class="card-header text-center text-uppercase font-weight-bold text-info"><strong>Total Active tax type</strong></div>
			<div class="card-body text-center" >

				<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $total_tax ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col mb-4">
		<div class="card shadow border-left-info py-2">
			<div class="card-header text-center text-uppercase font-weight-bold text-info"><strong>Total active Unit type</strong></div>
			<div class="card-body text-center" >

				<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $total_unit ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-percentage fa-2x text-gray-300"></i></div>
				</div>
			</div>
		</div>
	</div>
    <hr />
    </div>
    <?php endif?>
    <div class="row">
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-info"><strong>Total Sales Value</strong></div>
				<div class="card-body text-center" >

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_sales_value ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-success"></i></div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-success"><strong>Total Cash Sales Value</strong></div>
				<div class="card-body text-center">

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_cash_sales_value ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-money-bill fa-2x text-success"></i></div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-danger"><strong>Total Credit Sales Value</strong></div>
				<div class="card-body text-center" >

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_credit_sales_value ?></h1>
					</div>
					<div class="col-auto"><i class="fa fa-credit-card fa-2x text-danger"></i></div>
				</div>
				</div>
			</div>
		</div>
		<hr />
        </div>
        <div class="row">
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-info"><strong>Total Revenue to collect</strong></div>
				<div class="card-body text-center" >

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_revenue_value ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-success"></i></div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-success"><strong>Total uncollected Cash value</strong></div>
				<div class="card-body text-center">

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_cash_revenue_value ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-coins fa-2x text-success"></i></div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-danger"><strong>Total Uncollected credit Value</strong></div>
				<div class="card-body text-center" >

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_credit_revenue_value ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-question fa-2x text-danger"></i></div>
				</div>
				</div>
			</div>
		</div>
		<hr />
        </div>
        <div class="row">
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-info"><strong>Total purchase Value</strong></div>
				<div class="card-body text-center" >
					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_purchase_value ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-cart-arrow-down fa-2x text-success"></i></div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-success"><strong>Total Cash purchase Value</strong></div>
				<div class="card-body text-center">

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_cash_purchase_value ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-money-bill fa-2x text-success"></i></div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-danger"><strong>Total Credit purchase Value</strong></div>
				<div class="card-body text-center" >

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_credit_purchase_value ?></h1>
					</div>
					<div class="col-auto"><i class="fa fa-credit-card fa-2x text-danger"></i></div>
				</div>
				</div>
			</div>
		</div>
		<hr />
        </div>
        <div class="row">
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-info"><strong>Total Expense to pay</strong></div>
				<div class="card-body text-center" >

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_expense_value ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-success"></i></div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-success"><strong>Total Unpaid Cash value</strong></div>
				<div class="card-body text-center">

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_cash_expense_value ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-coins fa-2x text-success"></i></div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card shadow border-left-info py-2">
				<div class="card-header text-center text-uppercase font-weight-bold text-danger"><strong>Total Unpaid credit Value</strong></div>
				<div class="card-body text-center" >

					<div class="row align-items-center no-gutters">
					<div class="col mr-2">
					<h1><?= $currency.' '.$total_credit_expense_value ?></h1>
					</div>
					<div class="col-auto"><i class="fas fa-question fa-2x text-danger"></i></div>
				</div>
				</div>
			</div>
		</div>
		<hr />
        </div>
		<footer class="bg-white sticky-footer mt-3">
			<div class="container my-auto">
				<div class="text-center my-auto copyright">
					<span>Copyright © <?= $info->company_name?>
						<script>
							document.write(new Date().getFullYear())
						</script>
					</span>
				</div>
				<a class="no-border fixed-bottom text-right size-30 scroll-to-top" data-href="#page-top"><i class="fas  fa-4x fa-angle-up"></i></a>
			</div>
        </footer>
<script type="text/javascript" src="<?= JS_URL?>jquery.easing.min.js"></script>
<script type="text/javascript" src="<?= JS_URL?>theme.js"></script>
@include('layouts.footer')
