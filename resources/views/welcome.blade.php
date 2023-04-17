@include('config')

@include('layouts.header')
	<body>
		<br />
		<div class="container container-fluid mt-3">
			<h2 class="text-center">Inventory Management System</h2>
			<br />
			<div class="card ">
				<div class="card-header text-center"><h4>Login Menu</h4></div>
                @include('components.message')
				<div class="card-body">
					<form method="post" id="form" class="form" action="{{ route('login') }}">

						<div class="form-group">
								<label>Username/Email </label>
								<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1"><i class="fa fa-user fa-md position-relative"></i></span>
								</div>
								<input type="text" name="email"  class="form-control" id="user_email"placeholder="Your Username" required>
								</div>
								</div>
								<div class="form-group">
								<label>Password</label>
								<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1"><i class="fa fa-lock fa-md position-relative"></i></span>
								</div>
								<input type="password" class="form-control "name="password" id="user_password" placeholder="Your Password" required>
								<div class="input-group-append">
								<span toggle="#password" class="input-group-text" ><i class="fa fa-fw fa-eye field-icon toggle-password"></i></span></div>
							</div>
							</div>

						<div class="form-group">
							<button type="submit" name="login" class="btn btn-info login">Login</button>
							<button type="button"  id="hint" class="btn btn-primary" >Login hint</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
        @include('page-footer')
</html>

<script type="text/javascript" src="<?php echo env('JS_URL').'toggle_password.js'?>"></script>
<link rel="stylesheet" href="<?php echo env('CSS_URL').'parsley.css'?>" >
<script type="text/javascript" src="<?php echo env('JS_URL').'parsley.min.js'?>"></script>
<script type="text/javascript" src="<?php echo env('JS_URL').'popper.min.js'?>"></script>
<div id="wrapper">
        <div class="blocker"></div>
        <div  class="bg-dark text-white text-center py-0 px-2 pb-0 mb-0" id="popup" style="border-radius:4px;font-size: 16px;">
            <p class="text-warning py-0 my-0">For user login
            <p class="py-0 my-0">username: prakhar
            <p class="py-0 my-0">password: philieep </p>
            <p  class="text-warning py-0 my-0 ">For user login
            <p class="py-0 my-0">username: gyawali
            <p class="py-0 my-0">password: 123456<p>
            <p class="text-warning py-0 my-0">For admin login
            <p class="py-0 my-0">username: puskar
            <p class="py-0 my-0">password: philieep</p>
        </div>
        <div class="blocker"></div>
</div>
<script>
        var ref = $('#hint');
        var popup = $('#popup');
        popup.hide();

        ref.click(function(){
            popup.show();
                var popper = new Popper(ref,popup,{
                        placement: 'end',
                        modifiers: {
                                flip: {
                                        behavior: ['left', 'right', 'top','bottom']
                                },
                                offset: {
                                        enabled: true,
                                        offset: '0,10'
                                }
                        }
                });
                setTimeout(function(){
                    $(popup).slideUp();
                }, 4000);
        });



</script>
@include('layouts.footer')
<script>
function update(data){
    $('#message').html('<div class="alert alert-success">Login success. Redirecting.......</div>');
    enableButton(true);
    window.location.assign('.'+data.response);
}
</script>
