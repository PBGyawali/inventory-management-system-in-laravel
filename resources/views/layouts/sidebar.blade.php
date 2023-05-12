
</head>
<body class="p-0" id="page-top">
<div class="container-fluid d-flex bg-dark d-inline p-0 " style="z-index:10;">
	<div class="col-12 text-center pl-0 ">
        @if(session()->has('setup')||isset($setuppage))
                <nav class=" bg-dark topbar  text-center ">
                    <ul class="flex  text-center">
                        <h2 class=" text-center text-white text-uppercase">Please setup your page before continuing </h2>
                    </ul>
                </nav>
		@elseif(auth()->user())
			<div class="d-flex flex-column" >
                <nav class="navbar navbar-light bg-dark  justify-content-between navbar-expand-md topbar static-top p-0 m-0">
                        <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarNav">
                            <span class="fa fa-bars text-white"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="nav navbar-nav p-0 m-0 text-left flex-shrink-1 flex-grow-0">
						<li ><a href="{{route('dashboard')}}"><span class="btn mr-1 {{ $dashboard_active }} "> <i class="fas fa-tachometer-alt"></i> Dashboard</span></a></li>
						<li ><a  href="{{route('sales')}}"><span class="btn mr-1 {{ $sales_active }}"><i class="fas fa-shopping-cart"></i> Sales</span></a></li>
						<li ><a  href="{{route('purchase')}}"><span class="btn  mr-1 {{ $purchase_active }}"><i class="fas fa-cart-arrow-down"></i> Purchase</span></a></li>
						@if(auth()->user()->is_admin())
						<li ><a href="{{route('report')}}"><span class="btn mr-1  {{ $report_active }} "> <i class="fas fa-file-invoice"></i> Report</span></a></li>
						<li ><a href="{{route('graph')}}"><span class="btn {{ $graph_active }} "> <i class="fas fa-chart-line"></i> Graph</span></a></li>
						<li ><a  href="{{route('user')}}"><span class="btn {{ $user_active }}"><i class="fas fa-users"></i> Users</span></a></li>
						<li ><a  href="{{route('supplier')}}"><span class="btn mr-1 {{ $supplier_active }}"><i class="fas fa-id-card"></i> Supplier</span></a></li>
						<li ><a  href="{{route('product')}}"><span class="btn mr-1 {{ $product_active }}"><i class="fas fa-warehouse"></i> Product</span></a></li>
						<li ><a  href="{{route('category')}}"><span class="btn {{$category_active}} "><i class="fas fa-sitemap"></i> Category</span></a></li>
						<li ><a  href="{{route('brand')}}"><span class="btn {{$brand_active}} "><i class="fas fa-list"></i> Brand</span></a></li>
						<li ><a  href="{{route('tax')}}"><span class="btn {{$tax_active}} "><i class="fas fa-hand-holding-usd"></i> Tax</span></a></li>
						<li ><a  href="{{route('unit')}}"><span class="btn {{$unit_active}} "><i class="fas fa-percentage"></i> Unit</span></a></li>
						@endif
                        </ul>
                        <ul class="nav navbar-nav p-0 m-0 flex-shrink-0 flex-grow-1">
						<li class="dropdown ml-auto" role="presentation">
							<a data-toggle="dropdown" class="text-white" aria-expanded="false">
                                    <span id="user_uploaded_image">
                                        {{ auth()->user()->username}}
                                        <img src="<?= auth()->user()->profile_image?>"
                                        id="profile_image"
                                        class="img-fluid rounded-circle profile_image"
                                        width="30" height="30"/>

                                </span>
                            </a>
								<div class="dropdown-menu shadow dropdown-menu-right animated--grow-in" role="menu">
									<a class="dropdown-item" role="presentation" href="{{route('profile')}}">
                                        <i class="fas fa-user fa-sm mr-2 "></i>&nbsp;Profile
                                    </a>
									@if(auth()->user()->is_admin())
                                        <a class="dropdown-item" role="presentation" href="{{route('settings')}}">
                                            <i class="fas fa-cog fa-sm mr-2 "></i>&nbsp;Settings
                                        </a>
									@endif
                                    <div class="dropdown-divider"></div>
                                    <form action="{{route('logout')}}" method="post" class="logout_form">
                                            @csrf
                                            <button class="dropdown-item logout" role="presentation" type="submit"
                                            title="Clicking this button will log you out.">
                                                <i class="fas fa-sign-out-alt mr-2"></i>&nbsp;Logout
                                            </button>
                                    </form>
                                </div>
						</li>
					</ul>
                    </div>
                </nav>
			</div>
		@else
				<nav class=" bg-dark topbar  text-center ">
					<ul class="flex  text-center">
						<h2 class=" text-center text-white"> WELCOME TO {{ strtoupper($info->company_name)}}</h2>
					</ul>
				</nav>
		@endif
	</div>
</div>
<link rel="stylesheet" href="{{ env('CSS_URL')}}bootstrap_style.css" >
<script src="{{ env('JS_URL')}}confirmdefaults.js"></script>
<script src="{{ env('JS_URL')}}confirm.js"></script>

