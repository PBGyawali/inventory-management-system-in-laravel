@include('config')
@include('layouts.header')
@include('components.message')
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-secondary">
                    <div class="card-header">
                    	<div class="row">
                            <div class="col-lg-6 ">
                                <h3 class="card-title">Product List</h3>

                            </div>
                            <div class="col-md-4">
                                <button type="button" id="refresh" title="refresh" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></button>
                            </div>

                            <div class="col-lg-2 text-right">
                                <button type="button" data-element='Product' id="add_button" class="btn btn-success btn-sm"> <i class="fas fa-plus"></i> Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row"><div class="col-sm-12 table-responsive">
                            <table id="table" class="table table-bordered table-striped">
                                <thead><tr>
                                    <th class="product_id">ID</th>
                                    <th class="category.category_name">Category</th>
                                    <th class="brand.brand_name">Brand</th>
                                    <th class="product_name">Product Name</th>
                                    <th class="available_product_quantity">Available Quantity</th>
                                    <th class="product_status">Status</th>
                                    <?php if(auth()->user()->is_admin()):?>
								<th class="user.username created">Created By</th>
								<?php endif?>
								<th class="action">Action</th>
                                </tr></thead>
                            </table>
                        </div></div>
                    </div>
                </div>
			</div>
		</div>

        <div id="Modal" class="modal fade" data-backdrop="static">
            <div class="modal-dialog">
                <form method="post" id="form" class="form" action="<?php echo route('product')?>">
                    <div class="modal-content">
                        <div class="modal-header py-0 pt-1">
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Product</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <div class="modal-body pb-0 my-0">
                            <div class="form-group">
                                <label>Select Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <?php echo $category_list;?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select Brand</label>
                                <select name="brand_id" id="brand_id" class="form-control" required>
                                    <option value="">Select Category First</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Enter Product Name</label>
                                <input type="text" name="product_name" id="product_name" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label>Enter Product Description</label>
                                <textarea name="product_description" id="product_description" class="form-control" rows="auto" ></textarea>
                            </div>
                            <div class="form-group">
                                <label>Enter Product Opening Stock Quantity</label>
                                <div class="input-group">
                                    <input type="number" name="opening_stock" id="product_quantity" class="form-control" required  />
                                    <span class="input-group-addon ">
                                        <select name="product_unit" id="product_unit" class="form-control" required>
                                        <?php echo $unit_list?>
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Enter Product Base Price</label>
                                <input type="text" name="product_base_price" id="product_base_price" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                            </div>
                            <div class="form-group">
                                <label>Enter Product Tax (%)</label>
                                <input type="text" name="product_tax" id="product_tax" class="form-control" style="display:none" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                                <span id="span_item_details"></span>
                            </div>
                        </div>
                        <div class="modal-footer py-0 my-0">
                            <button type="submit"  id="submit_button" class="btn btn-success">Add</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="detailsModal" class="modal fade" data-backdrop="static">
            <div class="modal-dialog">
                <form method="post" id="product_detail_form">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-eye"></i> Product Details</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <div class="modal-body">
                            <div id="product_details"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @include('layouts.footer')
<script>

    $.ajax({
            'type': "POST",
            'dataType': 'json',
            'url': listurl,
            'success': function(data){
                calllist(data.taxlist,data.brands);
            },
        });


            function add_row(count = '')
		{
			var html = '';
			html += '<span id="item_details_row'+count+'" class="item_details"><div class="row">';
			html += '<div class="col-md-11 pr-0">';
			html += '<select name="tax[]" id="tax'+count+'" class="form-control" data-live-search="true" required>';
			html += tax_list;
			html += '</select><input type="hidden" name="hidden_tax[]" id="hidden_product_id'+count+'" />';
			html += '</div>';
			html += '<div class="col-md-1 pl-0">';
			if(count == '')
				html += '<button type="button"  id="add_more" class="btn btn-success">+</button>';
			else
				html += '<button type="button" id="'+count+'" class="btn btn-danger remove">-</button>';
			html += '</div>';
			html += '</span>';
			$('#span_item_details').append(html);
		}

     $('#add_button').click(function(){
        add_row();
    });


    function update(data){
        $('#category_id').val(data.category_id);
        select=brand(data.category_id)
        $('#brand_id').html(select);
        $('#brand_id').val(data.brand_id);
        $('#product_name').val(data.product_name);
        $('#product_description').val(data.product_description);
        $('#product_quantity').val(data.opening_stock);
        $('#product_product').val(data.product_product);
        $('#product_base_price').val(data.product_base_price);
        $('#product_tax').val(data.product_tax);
        $('#product_unit').val(data.product_unit);
    }
</script>

