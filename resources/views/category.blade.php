@include('layouts.header')
@include('components.message')
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-secondary">
                    <div class="card-header">
						@include('header_card',['element' =>'category','noreport'=>true])
                   	</div>
                <div class="card-body">
                    <div class="row">
                    	<div class="col-sm-12 table-responsive">
                    		<table id="table" class="table table-bordered table-striped">
                    			<thead><tr>
									<th class="category_id">ID</th>
									<th class="category_name">Category Name</th>
									<th class="category_status">Status</th>
									<th class="action">Action</th>
								</tr></thead>
                    		</table>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>



	<div id="Modal" class="modal fade" data-backdrop="static">
  	<div class="modal-dialog">
    	<form method="post" id="form" class="form"  action="<?php echo route('category')?>">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title"><i class="fa fa-plus"></i>Add category</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-3 text-left px-0 pl-1">Enter Category</label>
			            	<div class="col-md-9 px-0 pr-1">
			            		<input type="text" name="category_name" id="category_name" class="form-control"  data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>

        		<div class="modal-footer">
    					<button type="submit"  id="submit_button" class="btn btn-success">Add </button>
    					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>
</div>
@include('page-footer',['company_name'=>$info->company_name])
@include('layouts.footer')
<script>
        function update(data){
            $('#category_name').val(data.category_name);
        }
</script>



