@include('layouts.header')
@include('components.message')
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            @include('header_card',['element' =>'tax','name' =>'Management','noreport'=>true])
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                @include("table",['headers'=>['tax_id',"tax_name","tax_percentage","tax_status"]])
                            </div>
                        </div>
                    </div>



<div id="Modal" class="modal fade" data-backdrop="static">
  	<div class="modal-dialog">
    	<form method="post" id="form" class="form" action="<?php echo route('tax')?>">
            @csrf
            <div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add Data</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
		          		<label>Tax Name</label>
		          		<input type="text" name="tax_name" id="tax_name" class="form-control" required data-parsly-pattern="/^[a-zA-Z0-9 \s]+$/" data-parsley-trigger="keyup" />
		          	</div>
                    <div class="form-group">
                        <label>Tax Percentage</label>
                        <input type="text" name="tax_percentage" id="tax_percentage" class="form-control" required data-parsly-pattern="^[0-9]{1,2}\.[0-9]{2}$" data-parsley-trigger="keyup" />
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
@include('page-footer',['company_name'=>$info->company_name])
@include('layouts.footer_script')
