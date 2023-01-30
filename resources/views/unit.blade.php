@include('config')
@include('layouts.header')
@include('components.message')
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-secondary">
                    <div class="card-header">
                    	<div class="row">
                        	<div class="col-6">
                            	<h3 class="card-title">Unit List</h3>
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="refresh" title="refresh" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></button>
                            </div>
                            <div class="col-2 text-right">
                            	<button type="button" id="add_button" data-element='Unit' data-toggle="modal" data-target="#Modal" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add</button>
                        	</div>
                        </div>

                        <div class="clear:both"></div>
                   	</div>
                <div class="card-body">
                    <div class="row">
                    	<div class="col-sm-12 table-responsive">
                    		<table id="table" class="table table-bordered table-striped">
                    			<thead><tr>
									<th class="unit_id">ID</th>
									<th class="unit_name">Unit Name</th>
									<th class="unit_status">Status</th>
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
    	<form method="post" id="form" class="form"  action="<?php echo route('unit')?>">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title"><i class="fa fa-plus"></i>Add unit</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-3 text-left px-0 pl-1">Enter unit</label>
			            	<div class="col-md-9 px-0 pr-1">
			            		<input type="text" name="unit_name" id="unit_name" class="form-control"  data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-trigger="keyup" />
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
@include('layouts.footer')
<script>
	function update(data){
        $('#unit_name').val(data.unit_name);
    }
</script>



