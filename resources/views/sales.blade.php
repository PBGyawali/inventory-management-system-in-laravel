@include('layouts.header')

@include('components.message')
	<div class="row">
		<div class="col-lg-12">
			<div class="card card-secondary">
                <div class="card-header">
					@include('header_card',['element' =>'sales','extratext'=>'order',
                    'reporturl'=>auth()->user()->is_admin()?route('report.order',['table'=>'sale','from_date'=>':from_date','to_date'=>':to_date']):null,
                    'exporturl'=>auth()->user()->is_admin()?route('report.csv',['table'=>'sale','from_date'=>':from_date','to_date'=>':to_date']):null,

                    ])
                </div>
				<div class="card-body">
                    @include('table',['headers'=>['sale_id','sale_name order'=>'Customer_Name',
                    'total'=>'Total_Amount_('.$info->company_currency.')',
                                'payment_status'=>'Payment_Method','sale_status','sale_date',
                                auth()->user()->is_admin()?'user.username admininfo created':''=>'created_by']])

				</div>
            </div>
        </div>
    </div>
    <form action="{{ route('sales.upload')}}" class="text-center mt-3" method="post" enctype="multipart/form-data">
        @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label  class="col font-weight-bold"for="csv">Upload CSV</label>
                        <input type="file" name="csv" id="csv">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                        <button type="submit" class="btn btn-primary">submit</button>
                </div>
            </div>
    </form>

    <div id="Modal" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
    		<form method="post" id="form" class="form" data-url="<?php echo route('sales')?>" action="<?php echo route('sales')?>">
                @csrf
                <div class="modal-content">
    				<div class="modal-header">
					<h4 class="modal-title"><i class="fa fa-plus"></i> Create Sales Order</h4>
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
    				</div>
    				<div class="modal-body">
    					<div class="row">
							<div class="col-6">
								<div class="form-group">
									<label>Enter Receiver Name</label>
									<input type="text" name="sale_name" id="sale_name" class="form-control" autocomplete="off" required />
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									<label>Date</label>
									<input type="date" name="sale_date" id="sale_date"  data-parsley-type="date" class="form-control datepicker" autocomplete="off" required />
								</div>
							</div>
                        </div>

						<div class="form-group">
							<label for="sale_address">Enter Receiver Address</label>
							<textarea name="sale_address" id="sale_address" class="form-control" required></textarea>
						</div>
						<div class="form-group">
							<label class="col-md-7 px-0 ">Product Details</label>
                            <label class="col-md-2 px-0">Discount</label>
							<label class="col-md-2 px-0 ">Unit</label>
							<span id="span_item_details">
								@include('productlist-select',['select_menu'=>$product_list])
							</span>
						</div>
						<div class="form-group">
							<label>Select Payment Method</label>
							<select name="payment_status" id="payment_status" class="form-control">
								<option value="cash">Cash</option>
								<option value="credit">Credit</option>
							</select>
						</div>
    				</div>
    				<div class="modal-footer">
						<button type="submit" id="submit_button" class="btn btn-success">Add</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    				</div>
    			</div>
    		</form>
    	</div>
    </div>
	@include('page-footer',['company_name'=>$info->company_name])
    @include('layouts.footer_script')
