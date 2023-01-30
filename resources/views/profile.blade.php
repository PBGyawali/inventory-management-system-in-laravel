@include('config')
@include('layouts.header')

<?php
$name = $user->username;
$email = $user->email;
$user_id = $user->id;
?>
	        <div class="col-xs-12 col-sm-9 col-md-8 col-lg-6 pt-3 m-auto">
                @include('components.message')
	            <div class="card">
	            	<div class="card-header">
	            		<div class="row">
	            			<div class="col">
	            				<h2>Update Profile <i class="fas fa5x fa-user"></i></h2>
	            			</div>

	            		</div>
	            	</div>
	            	<div class="card-body pr-0">
	            		<div class="col-md-12">
							<form method="post" id="form" class="form" action="<?=route('profile')?>">
							<div class="form-group">
					          		<div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">Username <span class="text-danger">*</span></label>
						            	<div class="col-xs-12 col-sm-9 pl-0">
										<input type="text" name="username"  class="form-control" value="<?php echo $name; ?>"  />
						            	</div>
						            </div>
					          	</div>
							<div class="form-group">
					          		<div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">Email <span class="text-danger">*</span></label>
						            	<div class="col-xs-12 col-sm-9 pl-0">
											<input type="email" name="email"  class="form-control" value="<?php echo $email; ?>"  />
						            	</div>
						            </div>
								  </div>

					          		<div class="row">
						            	<div class="col-xs-12 col-sm-3 pl-0 pr-1">
										</div>
										<label class="col-xs-12 col-sm-9 text-left pl-0  ">Leave Password blank if you do not want to change <span class="text-danger invisible">*</span></label>
						            </div>

	            				<div class="form-group">
					          		<div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">Current Password <span class="text-danger invisible">*</span></label>
						            	<div class="col-xs-12 col-sm-9 pl-0">
										<input type="password" name="current_password" id="old_password" class="form-control password"    data-parsley-trigger="on blur" />

						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">New Password <span class="text-danger invisible">*</span></label>
						            	<div class="col-xs-12 col-sm-9 pl-0">
										<input type="password" name="password" id="user_new_password" class="form-control password"     data-parsley-trigger="on change" >
						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">Confirm Password <span class="text-danger invisible">*</span></label>
						            	<div class="col-xs-12 col-sm-9 pl-0 ">
										<input type="password" name="password_confirmation" id="user_re_enter_password" class="form-control password" data-parsley-equalto="#user_new_password" data-parsley-trigger="on change" />
										<span id="error_password"></span>
						            	</div>
						            </div>
								  </div>
								  <div class="form-group">
								  <div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">Profile Picture <span class="text-danger invisible">*</span></label>
						            	<div class="col-xs-12 col-sm-9 pl-0 ">
										<input type="file" name="profile_image" id="upload_picture"  class=" ml-1 file_upload" data-allowed_file='[<?php echo '"' . implode('","', ALLOWED_IMAGES) . '"'?>]' data-upload_time="later" accept="<?php echo "image/" . implode(", image/", ALLOWED_IMAGES);?>"/>
										</div>
									</div>
								</div>
					          	<br />
					          	<div class="form-group text-center">
										<input type="hidden" name="id" id="user_id" value="<?php echo $user_id ?>" />
					          		<button type="submit" name="update_profile" id="submit_button" class="btn btn-success"><i class="fas fa-lock"></i> Change</button>
					          	</div>
	            			</form>
	            		</div>

					</div>

				</div><!--card body end!-->

			</div>

		</div>

    </div>
    @include('layouts.footer')
<script>
datatable=''
method_type='/'+$('#user_id').val()+'/update'
</script>


