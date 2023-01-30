@include('config')
@include('layouts.header')
		<div class="row ">
			<div class="col">
				<div class="card shadow mb-4">
					<div class="card-header d-flex justify-content-between align-items-center">
					<h6 ></h6><!--needs to be kept as an element unless new element is added for css reasons-->
						<h6 class="text-primary font-weight-bold m-0"><span class="label" id="label_0">Sale</span> Overview (By <span id="type_0">number</span>)</h6>
						<div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><i class="fas fa-ellipsis-v "></i></button>
							<div class="dropdown-menu shadow dropdown-menu-right animated--fade-in"	role="menu">
                            <p class="text-center dropdown-header">Action:</p>
								<a role="presentation" id="permonth_0"  class="dropdown-item permonth"     data-id="0" data-month='[<?php echo $month;?>]' data-monthvalue='[<?php echo $monthvalue;?>]' style="display:none"> Get only past months value</a>
								<a role="presentation" id="fullmonths_0" class="dropdown-item fullmonths"  data-id="0" data-month='[<?php echo $fullmonth;?>]' data-monthvalue='[<?php echo $fullmonthvalue;?>]'> Get all month data</a>
								<a role="presentation" id="refresh_0" class="dropdown-item refresh"        data-id="0" data-url="{{route('graph').'/edit'}}" > Refresh</a>
								<div class="dropdown-divider"></div>
								<a role="presentation" id="bargraph_0" class="dropdown-item bargraph" 	 data-id="0" data-type="permonth" > Show bar graphs</a></div>
						</div>
					</div>
					<div class="card-body">
						<div class="chart-area" ><canvas id="graph_canvas_0" data-bs-chart="{&quot;type&quot;:&quot;line&quot;,&quot;data&quot;:{&quot;labels&quot;:[<?php echo $month?>],&quot;datasets&quot;:[{&quot;data&quot;:[<?php echo $monthvalue?>],&quot;label&quot;:&quot;Sales&quot;,&quot;fill&quot;:true,&quot;backgroundColor&quot;:&quot;rgba(78, 78, 78, 0.3)&quot;,&quot;borderColor&quot;:&quot;rgba(78, 115, 223, 1)&quot;}]},&quot;options&quot;:{&quot;responsive&quot;:true,&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{},&quot;scales&quot;:{&quot;xAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;],&quot;drawOnChartArea&quot;:false},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}],&quot;yAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;]},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}]}}}"></canvas></div>
					</div>
				</div>
			</div>
        </div>
		<div class="row ">
			<div class="col">
				<div class="card shadow mb-4">
					<div class="card-header d-flex justify-content-between align-items-center">
					<h6 ></h6><!--needs to be kept as an element unless new element is added for css reasons-->
						<h6 class="text-primary font-weight-bold m-0"><span class="label" id="label_1">Purchase</span> Overview (By <span id="type_1">number</span>)</h6>
						<div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><i class="fas fa-ellipsis-v "></i></button>
							<div class="dropdown-menu shadow dropdown-menu-right animated--fade-in"	role="menu">
								<p class="text-center dropdown-header">Action:</p>
								<a role="presentation" id="permonth_1"  class="dropdown-item permonth"     data-id="1" data-month='[<?php echo $month;?>]' data-monthvalue='[<?php echo $monthvalue_purchase;?>]' style="display:none"> Get only past months value</a>
								<a role="presentation" id="fullmonths_1" class="dropdown-item fullmonths"  data-id="1" data-month='[<?php echo $fullmonth;?>]' data-monthvalue='[<?php echo $fullmonthvalue_purchase;?>]'> Get all month data</a>
								<a role="presentation" id="refresh_1" class="dropdown-item refresh"        data-id="1" data-url="{{route('graph').'/edit'}}" > Refresh</a>
								<div class="dropdown-divider"></div>
								<a role="presentation" id="bargraph_1" class="dropdown-item bargraph" 	 data-id="1" data-type="permonth" > Show bar graphs</a></div>
						</div>
					</div>
					<div class="card-body">
						<div class="chart-area" ><canvas id="graph_canvas_1" data-bs-chart="{&quot;type&quot;:&quot;line&quot;,&quot;data&quot;:{&quot;labels&quot;:[<?php echo $month?>],&quot;datasets&quot;:[{&quot;data&quot;:[<?php echo $monthvalue_purchase?>],&quot;label&quot;:&quot;Sales&quot;,&quot;fill&quot;:true,&quot;backgroundColor&quot;:&quot;rgba(78, 78, 78, 0.3)&quot;,&quot;borderColor&quot;:&quot;rgba(78, 115, 223, 1)&quot;}]},&quot;options&quot;:{&quot;responsive&quot;:true,&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{},&quot;scales&quot;:{&quot;xAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;],&quot;drawOnChartArea&quot;:false},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}],&quot;yAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;]},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}]}}}"></canvas></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row ">
			<div class="col">
				<div class="card shadow mb-4">
					<div class="card-header d-flex justify-content-between align-items-center">
					<h6 ></h6><!--needs to be kept as an element unless new element is added for css reasons-->
						<h6 class="text-primary font-weight-bold m-0"><span class="label" id="label_2">Sale</span> Overview (By <span id="type_2">revenue</span>)</h6>
						<div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><i class="fas fa-ellipsis-v "></i></button>
							<div class="dropdown-menu shadow dropdown-menu-right animated--fade-in"	role="menu">
								<p class="text-center dropdown-header">Action:</p>
								<a role="presentation" id="permonth_2"  class="dropdown-item permonth"     data-id="2" data-month='[<?php echo $month;?>]' data-monthvalue='[<?php echo $monthvalue_sale_revenue;?>]' style="display:none"> Get only past months value</a>
								<a role="presentation" id="fullmonths_2" class="dropdown-item fullmonths"  data-id="2" data-month='[<?php echo $fullmonth;?>]' data-monthvalue='[<?php echo $fullmonthvalue_sale_revenue;?>]'> Get all month data</a>
								<a role="presentation" id="refresh_2" class="dropdown-item refresh"        data-id="2" data-url="{{route('graph').'/edit'}}" > Refresh</a>
								<div class="dropdown-divider"></div>
								<a role="presentation" id="bargraph_2" class="dropdown-item bargraph" 	 data-id="2" data-type="permonth" > Show bar graphs</a></div>
						</div>
					</div>
					<div class="card-body">
						<div class="chart-area" ><canvas id="graph_canvas_2" data-bs-chart="{&quot;type&quot;:&quot;line&quot;,&quot;data&quot;:{&quot;labels&quot;:[<?php echo $month?>],&quot;datasets&quot;:[{&quot;data&quot;:[<?php echo $monthvalue_sale_revenue?>],&quot;label&quot;:&quot;Sales&quot;,&quot;fill&quot;:true,&quot;backgroundColor&quot;:&quot;rgba(78, 78, 78, 0.3)&quot;,&quot;borderColor&quot;:&quot;rgba(78, 115, 223, 1)&quot;}]},&quot;options&quot;:{&quot;responsive&quot;:true,&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{},&quot;scales&quot;:{&quot;xAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;],&quot;drawOnChartArea&quot;:false},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}],&quot;yAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;]},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}]}}}"></canvas></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row ">
			<div class="col">
				<div class="card shadow mb-4">
					<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="test"></h6><!--needs to be kept as an element unless new element is added for css reasons-->
						<h6 class="text-primary font-weight-bold m-0"><span class="label" id="label_3">Purchase</span> Overview (By <span id="type_3">revenue</span>)</h6>
						<div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><i class="fas fa-ellipsis-v "></i></button>
							<div class="dropdown-menu shadow dropdown-menu-right animated--fade-in"	role="menu">
								<p class="text-center dropdown-header">Action:</p>
								<a role="presentation" id="permonth_3"  class="dropdown-item permonth"     data-id="3" data-month='[<?php echo $month;?>]' data-monthvalue='[<?php echo $monthvalue_purchase_revenue;?>]' style="display:none"> Get only past months value</a>
								<a role="presentation" id="fullmonths_3" class="dropdown-item fullmonths"  data-id="3" data-month='[<?php echo $fullmonth;?>]' data-monthvalue='[<?php echo $fullmonthvalue_purchase_revenue;?>]'> Get all month data</a>
								<a role="presentation" id="refresh_3" class="dropdown-item refresh"        data-id="3" data-url="{{route('graph').'/edit'}}" > Refresh</a>
								<div class="dropdown-divider"></div>
								<a role="presentation" id="bargraph_3" class="dropdown-item bargraph" 	 data-id="3" data-type="permonth" > Show bar graphs</a></div>
						</div>
					</div>
					<div class="card-body">
						<div class="chart-area" ><canvas id="graph_canvas_3" data-bs-chart="{&quot;type&quot;:&quot;line&quot;,&quot;data&quot;:{&quot;labels&quot;:[<?php echo $month?>],&quot;datasets&quot;:[{&quot;data&quot;:[<?php echo $monthvalue_purchase_revenue?>],&quot;label&quot;:&quot;Sales&quot;,&quot;fill&quot;:true,&quot;backgroundColor&quot;:&quot;rgba(78, 78, 78, 0.3)&quot;,&quot;borderColor&quot;:&quot;rgba(78, 115, 223, 1)&quot;}]},&quot;options&quot;:{&quot;responsive&quot;:true,&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{},&quot;scales&quot;:{&quot;xAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;],&quot;drawOnChartArea&quot;:false},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}],&quot;yAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;]},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}]}}}"></canvas></div>
					</div>
				</div>
			</div>
		</div>
        <div class="row ">
			<div class="col">
				<div class="card shadow mb-4">
					<div class="card-header d-flex justify-content-between align-items-center">
					<h6 ><br>

				</h6><!--needs to be kept as an element unless new element is added for css reasons-->
						<h6 class="text-primary font-weight-bold m-0"><span class="label" id="label_4">Product</span> Overview (By <span id="type_4">number</span>)</h6>
						<div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><i class="fas fa-ellipsis-v "></i></button>
							<div class="dropdown-menu shadow dropdown-menu-right animated--fade-in"	role="menu">
								<p class="text-center dropdown-header">Action:</p>
								<a role="presentation" id="permonth_4"  class="dropdown-item permonth"     data-id="4" data-month='[<?php echo $category;?>]' data-monthvalue='[<?php echo $categoryvalue;?>]' > Show only available product</a>
								<a role="presentation" id="fullmonths_4" class="dropdown-item fullmonths"  data-id="4" data-month='[<?php echo $allcategory;?>]' data-monthvalue='[<?php echo $allcategoryvalue;?>]'style="display:none"> Show all product</a>
								<a role="presentation" id="refresh_4" class="dropdown-item refresh"        data-id="4" data-url="{{route('graph').'/edit'}}" > Refresh</a>
								<div class="dropdown-divider"></div>
								<a role="presentation" id="bargraph_4" class="dropdown-item bargraph" 	 data-id="4" data-type="permonth" > Show bar graphs</a></div>
						</div>
					</div>
					<div class="card-body">
						<div class="chart-area" ><canvas id="graph_canvas_4" data-bs-chart="{&quot;type&quot;:&quot;bar&quot;,&quot;data&quot;:{&quot;labels&quot;:[<?php echo $allcategory?>],&quot;datasets&quot;:[{&quot;data&quot;:[<?php echo $allcategoryvalue?>],&quot;label&quot;:&quot;Product&quot;,&quot;fill&quot;:true,&quot;backgroundColor&quot;:&quot;rgba(78, 78, 78, 0.3)&quot;,&quot;borderColor&quot;:&quot;rgba(78, 115, 223, 1)&quot;}]},&quot;options&quot;:{&quot;responsive&quot;:true,&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{},&quot;scales&quot;:{&quot;xAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;],&quot;drawOnChartArea&quot;:false},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}],&quot;yAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;]},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}]}}}"></canvas></div>
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
<script type="text/javascript" src="<?php echo JS_URL?>graph.js"></script>
