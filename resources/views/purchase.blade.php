@include('config')
@include('layouts.header')

@include('components.message')
	<div class="row">
		<div class="col-lg-12">

			<div class="card card-secondary">
                <div class="card-header">
                	<div class="row">
                    	<div class="col-6 col-sm-4">
                            <h3 class="card-title">Purchase List</h3>
						</div>
						<div class="col-sm-4">
	            				<div class="row input-daterange">
	            					<div class="col-md-6">
		            					<input type="date" id="from_date" class="form-control form-control-sm"  />
		            				</div>
		            				<div class="col-md-6">
		            					<input type="date" id="to_date" class="form-control form-control-sm"   />
		            				</div>
		            			</div>
							</div>
							<div class="col-md-2">
	            				<button type="button" id="filter"class="btn btn-info btn-sm"><i class="fas fa-filter"></i></button>
	            				&nbsp;
                                <button type="button" id="refresh" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></button>
                                <button type="button" id="report" title="Get Report" data-url="{{route('report')}}"data-table="purchase" class="btn btn-info btn-sm"><i class="fas fa-print"></i></button>
	            			</div>
                        <div class="col-4 col-sm-2 text-right" >
                            <button type="button"  id="add_button" data-element='Purchase Order' class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</button>
                        </div>
                    </div>
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
                                <?php if(auth()->user()->is_admin()):?>
								<th class="user.username admininfo created">Created By</th>
								<?php endif?>
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
							<label>Enter Product Details</label>
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
    @include('layouts.footer')
    <script type="text/javascript">

			$.ajax({
			'type': "POST",
			'dataType': 'json',
			'url': listurl,
			'data':{status:'active'},
			'success': function(data){	list(data);	},
			});

		function add_row(count = '')
		{
			var html = '';
			html += '<span class="item_details" id="row'+count+'">';
			html += '<div class="row" id="item_details_row'+count+'">';
			html += '<div class="col-md-8">';
			html += '<select name="product_id[]" id="purchase_id'+count+'" class="form-control" data-live-search="true" required>';
			html += product_list;
			html += '</select>';
			html += '</div>';
			html += '<div class="col-md-3 px-0">';
			html += '<input type="number" name="quantity[]" class="form-control" required />';
			html += '</div>';
			html += '<div class="col-md-1 pl-0">';
			if(count == '')
				html += '<button type="button" name="add_more" id="add_more" class="btn btn-success">+</button>';
			else
				html += '<button type="button" name="remove" id="'+count+'" class="btn btn-danger remove">-</button>';
			html += '</div>';
			html += '</div></div></span>';
			$('#span_item_details').append(html);
		}
        function update(data){
            $('#purchase_name').val(data.purchase_name);
            $('#purchase_date').val(data.purchase_date);
            $('#purchase_address').val(data.purchase_address);
            $('#span_item_details').html(data.item_details);
            $('#purchase_id').val(data.product_id);
            $('#payment_status').val(data.payment_status);
        }
</script>
