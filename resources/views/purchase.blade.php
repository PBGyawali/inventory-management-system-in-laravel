@include('layouts.header')

@include('components.message')
	<div class="row">
		<div class="col-lg-12">

			<div class="card card-secondary">
                <div class="card-header">
					@include('header_card',['element' =>'purchase','extratext'=>'order',
                   'reporturl'=>route('report.order',['table'=>'purchase','from_date'=>':from_date','to_date'=>':to_date']),
                    'exporturl'=>route('report.csv',['table'=>'purchase','from_date'=>':from_date','to_date'=>':to_date']),
                    ])
                </div>
                <div class="card-body">
                	<table id="table" class="table table-bordered table-striped">
                		<thead>
							<tr>
								<th class="purchase_id">Purchase ID</th>
								<th class="purchase_name">Supplier Name</th>
								<th class="purchase_sub_total">Total Amount (<?php	echo $info->company_currency;	?>)</th>
								<th class="payment_status">Payment Method</th>
								<th class="purchase_status">Purchase Status</th>
                                <th class="purchase_date">Purchase Date</th>
                                @if(auth()->user()->is_admin())
								<th class="user.username admininfo created">Created By</th>
								@endif
                                <th class="action">Action</th>
							</tr>
						</thead>
                	</table>
                </div>
            </div>
        </div>
    </div>

    <div id="Modal" class="modal fade" data-backdrop="static">
    	<div class="modal-dialog">
    		<form method="post" id="form" class="form" action="<?php echo route('purchase')?>">
    			<div class="modal-content">
    				<div class="modal-header">
					<h4 class="modal-title"><i class="fa fa-plus"></i> Create Purchase order</h4>
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
    				</div>
    				<div class="modal-body">
    					<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Enter Supplier Name</label>
									<input type="text" name="purchase_name" id="purchase_name" list="purchase_name_list"   autocomplete="off" class="form-control" required />
									<datalist id="purchase_name_list">
									<?php echo $supplier_list?>
									</datalist>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Date</label>
									<input type="date" name="purchase_date" id="purchase_date" class="form-control" autocomplete="off" required />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Enter Supplier Address</label>
							<textarea name="purchase_address" id="purchase_address" class="form-control"></textarea>
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
						<button type="submit"  id="submit_button" class="btn btn-success">Add</button>
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
            $('#purchase_name').val(data.purchase_name);
            $('#purchase_date').val(data.purchase_date);
            $('#purchase_address').val(data.purchase_address);
            $('#span_item_details').html(data.item_details);
            $('#purchase_id').val(data.product_id);
            $('#payment_status').val(data.payment_status);
        }
</script>
