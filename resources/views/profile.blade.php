@include('layouts.header')
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
							<form method="post" id="form" class="form" action="<?=route('profile.update',['user'=>auth()->id()])?>">
                                @csrf
                                @method('PUT')
							<div class="form-group">
					          		<div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">Username <span class="text-danger">*</span></label>
						            	<div class="col-xs-12 col-sm-9 pl-0">
										<input type="text" name="username"  class="form-control" value="<?= auth()->user()->username; ?>"  />
						            	</div>
						            </div>
					          	</div>
							<div class="form-group">
					          		<div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">Email <span class="text-danger">*</span></label>
						            	<div class="col-xs-12 col-sm-9 pl-0">
											<input type="email" name="email"  class="form-control" value="<?=auth()->user()->email; ?>"  />
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
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">Current Password </label>
						            	<div class="col-xs-12 col-sm-9 pl-0">
										<input type="password" name="current_password" id="old_password" class="form-control password"    data-parsley-trigger="on blur" />

						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">New Password </label>
						            	<div class="col-xs-12 col-sm-9 pl-0">
										<input type="password" name="password" id="user_new_password" class="form-control password"     data-parsley-trigger="on change" >
						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">Confirm Password </label>
						            	<div class="col-xs-12 col-sm-9 pl-0 ">
										<input type="password" name="password_confirmation" id="user_re_enter_password" class="form-control password" data-parsley-equalto="#user_new_password" data-parsley-trigger="on change" />
										<span id="error_password"></span>
						            	</div>
						            </div>
								  </div>
								  <div class="form-group">
								  <div class="row">
						            	<label class="col-xs-12 col-sm-3 text-left pl-0 pr-1 ">
											Profile Picture 
										</label>
						            	<div class="col-xs-12 col-sm-5 pl-0 ">
											<div class="row form-group">
												<input type="file" name="profile_image" id="upload_picture"  class=" ml-1 file_upload" 
													data-allowed_file='[<?php echo '"' . implode('","', config('app.allowed_images')) . '"'?>]'
													data-upload_time="later" 
													accept="<?="image/" . implode(", image/", config('app.allowed_images'));?>"/>				
											</div>
											<div class="row form-group text-center mt-3">
												<button type="submit" id="submit_button" class="btn btn-success">
													<i class="fas fa-lock"></i> Change
												</button>
											</div>	
										</div>
										 <div class="col-xs-12 col-sm-4">
										 <img src="{{auth()->user()->profile_image}}" class="m-2 p-3 profile_image" width="200"alt="" srcset="">
										 </div>
									</div>
					        
					          	
	            			</form>
	            		</div>

					</div>

				</div><!--card body end!-->

			</div>

		</div>

    </div>
	@include('page-footer',['company_name'=>$info->company_name])
    @include('layouts.footer_script')


