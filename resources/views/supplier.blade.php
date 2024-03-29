@include('layouts.header')
@include('components.message')
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-secondary">
                    <div class="card-header">
						@include('header_card',['element' =>'supplier','noreport'=>true,'buttonicon'=>'fa-user-plus'])
                        <div class="clear:both"></div>
                   	</div>
                   	<div class="card-body">
                   		<div class="row">
							<div class="col-sm-12 table-responsive">
                                @include("table",['headers'=>['supplier_id',"supplier_name order asc","supplier_email","supplier_contact_no","supplier_status"]])
                   		</div>
                   	</div>
               	</div>
           	</div>
        </div>
        <div id="Modal" class="modal fade" data-backdrop="static">
        	<div class="modal-dialog">
        		<form method="post" id="form" class="form"  action="<?php echo route('supplier')?>">
        			@csrf
                    <div class="modal-content">
        			<div class="modal-header">
						<h4 class="modal-title" id="modal_title">Add supplier</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        			</div>
        			<div class="modal-body">
        				<div class="form-group">
							<label>Enter Supplier Name</label>
							<input type="text" name="supplier_name" id="supplier_name" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Enter Supplier Email</label>
							<input type="email" name="supplier_email" id="supplier_email" class="form-control"  />
						</div>
						<div class="form-group">
							<label>Enter Supplier Contact no</label>
							<input type="text" name="supplier_contact_no" id="supplier_contact_no" class="form-control"  />
						</div>
						<div class="form-group">
							<label>Enter Supplier address</label>
							<input type="text" name="supplier_address" id="supplier_address" class="form-control" />
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
	</div>
		@include('page-footer',['company_name'=>$info->company_name])
    @include('layouts.footer_script')

