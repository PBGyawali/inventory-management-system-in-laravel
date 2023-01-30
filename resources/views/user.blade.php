@include('config')
@include('layouts.header')
@include('components.message')
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-secondary">
                    <div class="card-header">
                    	<div class="row">
                        	<div class="col-6">
                            	<h3 class="card-title">User List</h3>
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="refresh" title="refresh" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></button>
                            </div>
                            <div class="col-lg-2  text-right">
                            	<button type="button" id="add_button" data-element='User' data-toggle="modal" data-target="#Modal" class="btn btn-success btn-sm"><i class="fas fa-user-plus"></i> Add</button>
                        	</div>
                        </div>

                        <div class="clear:both"></div>
                   	</div>
                   	<div class="card-body">
                   		<div class="row"><div class="col-sm-12 table-responsive">
                   			<table id="table" class="table table-bordered table-striped">
                   				<thead>
									<tr>
										<th class="id">ID</th>
										<th class="username">Username</th>
										<th class="email">Email</th>
										<th class="user_status">Status</th>
										<th class="action">Action</th>
									</tr>
								</thead>
                   			</table>
                   		</div>
                   	</div>
               	</div>
           	</div>
        </div>
        <div id="Modal" class="modal fade" data-backdrop="static" >
        	<div class="modal-dialog">
        		<form method="post" id="form" class="form"  action="<?php echo route('user');?>">
        			<div class="modal-content">
        			<div class="modal-header">
						<h4 class="modal-title" id="modal_title">Add User</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        			</div>
        			<div class="modal-body">
        				<div class="form-group">
							<label>Enter User Name</label>
							<input type="text" name="username" id="user_name" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Enter User Email</label>
							<input type="email" name="email" id="user_email" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Enter User Password</label>
							<input type="password" name="password" id="user_password" class="form-control" required />
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
				$('#user_name').val(data.username);
				$('#user_email').val(data.email);
			}
</script>

