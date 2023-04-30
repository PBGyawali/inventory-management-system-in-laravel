@include('layouts.header')

@include('components.message')
	<div class="row">
		<div class="col-lg-12">
			<div class="card card-secondary">
                <div class="card-header">
					@include('header_card',['element' =>'sales','extratext'=>'order',
                    'reporturl'=>route('report.order',['table'=>'sale','from_date'=>':from_date','to_date'=>':to_date']),
                    'exporturl'=>route('report.csv',['table'=>'sale','from_date'=>':from_date','to_date'=>':to_date']),

                    ])
                </div>
				<div class="card-body">
					<table id="table" class="table table-bordered table-striped ">
						<thead>
							<tr>
								<th class="sale_id">Sales ID</th>
								<th class="sale_name">Customer Name</th>
								<th class="total">Total Amount (<?php	echo $info->company_currency;?>)</th>
								<th class="payment_status">Payment Method</th>
								<th class="sale_status">Sales Status</th>
								<th class="sale_date">Sales Date</th>
								@if(auth()->user()->is_admin())
								<th class="user.username admininfo">Created By</th>
								@endif
								<th class="action">Action</th>
							</tr>
						</thead>
					</table>
				</div>
            </div>
        </div>
    </div>

    <div id="Modal" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
    		<form method="post" id="form" class="form" data-url="<?php echo route('sales')?>" action="<?php echo route('sales')?>">
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
							<label class="col-md-8 px-0">Enter Product Details</label>
							<label class="col-md-3 px-0">Enter Unit</label>
							<span id="span_item_details"></span>
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
    @include('layouts.footer')
    <script type="text/javascript">
        function update(data){
            $('#sale_name').val(data.sale_name);
            $('#sale_date').val(data.sale_date);
            $('#sale_address').val(data.sale_address);
            $('#span_item_details').html(data.item_details);
            $('#payment_status').val(data.payment_status);
        }
</script>
