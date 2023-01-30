<?php
$page=Route::currentRouteName();
$dashboard_active=$report_active=$graph_active=$user_active=$supplier_active=$sales_active=$purchase_active=$product_active=$brand_active=$tax_active=$unit_active=$category_active='inactive_page';
${$page."_active"} = 'active_page';
$user_type= (!empty(auth()->user()->user_type))?auth()->user()->user_type:'';
$username=(!empty(auth()->user()->username))?ucwords(auth()->user()->username):'';
$user_id=(!empty(auth()->user()->id))?auth()->user()->id:'';
$profile_image=(!empty(auth()->user()->profile_image))?auth()->user()->profile_image:'user_profile.png';
?>
</head>
<body class="p-0" id="page-top">
<div class="container-fluid d-flex bg-dark d-inline p-0 " style="z-index:10;">
	<div class="col-12 text-center pl-0 ">
        <?php if(session()->has('setup')||isset($setuppage)):?>
                <nav class=" bg-dark topbar  text-center ">
                    <ul class="flex  text-center">
                        <h2 class=" text-center text-white text-uppercase">Please setup your page before continuing </h2>
                    </ul>
                </nav>
		<?php elseif(auth()->user()):?>
			<div class="d-flex flex-column" >
                <nav class="navbar navbar-light bg-dark  navbar-expand topbar static-top p-0 m-0">
					<ul class="nav navbar-nav  p-auto m-auto text-left ">
						<li ><a href="{{route('dashboard')}}"><span class="btn mr-1 <?php echo $dashboard_active ?> "> <i class="fas fa-tachometer-alt"></i> Dashboard</span></a></li>
						<li ><a  href="{{route('sales')}}"><span class="btn mr-1 <?php echo $sales_active ?>"><i class="fas fa-shopping-cart"></i> Sales</span></a></li>
						<li ><a  href="{{route('purchase')}}"><span class="btn mr-1 <?php echo $purchase_active ?>"><i class="fas fa-cart-arrow-down"></i> Purchase</span></a></li>
						</ul>
						<?php if(auth()->user()->isAdmin()):?>
						<ul class="nav navbar-nav  p-auto m-auto text-left">
						<li ><a href="{{route('report')}}"><span class="btn mr-1 <?php echo $report_active ?> "> <i class="fas fa-file-invoice"></i> Report</span></a></li>
						<li ><a href="{{route('graph')}}"><span class="btn mr-1 <?php echo $graph_active ?> "> <i class="fas fa-chart-line"></i> Graph</span></a></li>
						<li ><a  href="{{route('user')}}"><span class="btn mr-1 <?php echo $user_active ?>"><i class="fas fa-users"></i> Users</span></a></li>
						</ul>
						<ul class="nav navbar-nav  p-auto m-auto text-center">
						<li ><a  href="{{route('supplier')}}"><span class="btn mr-1 <?php echo $supplier_active ?>"><i class="fas fa-id-card"></i> Supplier</span></a></li>
						<li ><a  href="{{route('product')}}"><span class="btn mr-1 <?php echo $product_active ?>"><i class="fas fa-warehouse"></i> Product</span></a></li>
						<li ><a  href="{{route('category')}}"><span class="btn mr-1 <?php echo $category_active ?>"><i class="fas fa-sitemap"></i> Category</span></a></li>
						</ul>
						<ul class="nav navbar-nav  p-auto m-auto text-center">
						<li ><a  href="{{route('brand')}}"><span class="btn mr-1 <?php echo $brand_active ?>"><i class="fas fa-list"></i> Brand</span></a></li>
						<li ><a  href="{{route('tax')}}"><span class="btn mr-1 <?php echo $tax_active ?>"><i class="fas fa-hand-holding-usd"></i> Tax</span></a></li>
						<li ><a  href="{{route('unit')}}"><span class="btn mr-1 <?php echo $unit_active ?>"><i class="fas fa-percentage"></i> Unit</span></a></li>
						</ul>
						<?php endif	?>

						<ul class="nav navbar-nav  p-auto m-auto  text-right ">
						<li class="dropdown " role="presentation">
							<a data-toggle="dropdown" class="position-relative" aria-expanded="false"><span id="user_uploaded_image_small" class=" btn text-white ml-1 pl-2"><?php echo $username?> <img src="<?php echo IMAGES_URL.$profile_image?>" class="img-fluid rounded-circle" width="30" height="30"/></a></span>
								<div class="dropdown-menu shadow dropdown-menu-right animated--grow-in" role="menu">
									<a class="dropdown-item" role="presentation" href="<?php echo BASE_URL.'profile'?>"><i class="fas fa-user fa-sm mr-2 "></i>&nbsp;Profile</a>
									<?php if(auth()->user()->isAdmin()):?>
									<a class="dropdown-item" role="presentation" href="<?php echo BASE_URL.'settings'?>"><i class="fas fa-cog fa-sm mr-2 "></i>&nbsp;Settings</a>
									<?php endif?>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{route('logout')}}" method="post" class="logout_form">
                                    @csrf
                                    <button class="dropdown-item logout" role="presentation" type="submit"
                                    title="Clicking this button will log you out.">
                                        <i class="fas fa-sign-out-alt mr-2"></i>&nbsp;Logout</button></form></div>
								</div>
						</li>
					</ul>
                </nav>

		<?php else:?>
				<nav class=" bg-dark topbar  text-center ">
					<ul class="flex  text-center">
						<h2 class=" text-center text-white"> WELCOME TO <?php echo strtoupper($info->company_name)?></h2>
					</ul>
				</nav>
		<?php endif?>
	</div>
</div>
<link rel="stylesheet" href="<?php echo CSS_URL.'bootstrap_style.css'?>" >
<script src="<?php echo JS_URL.'confirmdefaults.js'?>"></script>
<script src="<?php echo JS_URL.'confirm.js'?>"></script>

