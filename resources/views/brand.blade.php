@include('config')
@include('layouts.header')

	<span class="position-absolute w-100 text-center" id="message"style="z-index:10"></span>
	<div class="row">
		<div class="col-lg-12">
			<div class="card card-secondary">
                <div class="card-header">
                	<div class="row">
                		<div class="col-md-6">
                			<h3 class="card-title">Brand List</h3>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="refresh" title="refresh" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></button>
                        </div>
                		<div class="col-md-2 text-right">
                			<button type="button" data-element='Brand' id="add_button" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
                		</div>
                	</div>
                </div>
                <div class="card-body">
                	<table id="table" class="table table-bordered table-striped">
                		<thead>
							<tr>
								<th class="brand_id">ID</th>
								<th class="category.category_name">Category</th>
								<th class="brand_name">Brand Name</th>
								<th class="brand_status">Status</th>
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
    		<form method="post" id="form" class="form" action="<?php echo route('brand')?>">
    			<div class="modal-content">
    				<div class="modal-header">
						<h4 class="modal-title"><i class="fa fa-plus"></i> Add Brand</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-loading d-none m-auto py-5 text-center">
						<i class="fa fa-spinner fa-pulse fa-5x text-secondary"></i><br> Loading...
						<span class="sr-only">Loading...</span>
					</div>
    				<div class="modal-body ">
					<span id="form_message"></span>

    					<div class="form-group">
						<label>Category</label>
    						<select name="category_id" id="category_id" class="form-control" required>
								<?php echo $category_list; ?>
							</select>
    					</div>
    					<div class="form-group">
							<label>Enter Brand Name</label>
							<input type="text" name="brand_name" id="brand_name" class="form-control" required />
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
<script>
    function update(data){
        $('#category_id').val(data.category_id);
        $('#brand_name').val(data.brand_name);
    }

</script>


