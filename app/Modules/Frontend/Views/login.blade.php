@extends('layouts.frontend.electro')

@section('content')

<div class="container">
	<div class="row">

		<!-- login panel -->
		<div class="col-sm-12 sol-md-6 col-lg-6">
			<div class="panel panel-warning">
				<div class="panel-heading"><b><i class="fas fa-lock"></i> Guest's Login</b></div>
			    <div class="panel-body">
			    	{{-- Form::open(['action'=>'\App\Modules\Frontend\Controllers\FrontendController@processlogin', 'method'=>'post', 'class'=>'form-hrizontal']) --}}
			    	<form class="" method="POST" action="{{ route('frontend.process.login') }}">
            		{{ csrf_field() }}
			    	<div class="form-group">
			    		<input type="email" name="email" placeholder="email address" class="form-control">
			    	</div>
			    	<div class="form-group">
			    		<input type="password" name="password" placeholder="password" class="form-control">
			    	</div>
			    	<div class="form-group">
			    		<button class="btn btn-md btn-info"><i class="fas fa-key"></i> Sign in</button>
			    		<a href="#" data-toggle="modal" data-backdrop="static" data-target="#lossPwdModal" class="pull-right lostpassword">I can't access my account</a>
			    	</div>

			    	<!-- url -->
			    	<input type="hidden" name="redirectTo" value="{{ Session::get('redirect_url') }}" />

			    	</form>
			    	{{-- Form::close() --}}
			    	<hr />
			    </div>
			</div>
		</div>

		<!-- registration panel -->
		<div class="col-sm-12 sol-md-6 col-lg-6">
			<div class="panel panel-info">
				<div class="panel-heading"><b><i class="fas fa-plus-circle"></i> Create New Account</b></div>
			    <div class="panel-body registerform">
			    	<span class="registeralertmsg"></span>
			    	<p class="alert alert-warning"><i class="fas fa-info-circle"></i> Please fill up the information below </p>
			    	<div class="form-group">
			    		<input type="text" name="fullname" placeholder="first and last name" class="form-control registerfullname">
			    	</div>
			    	<div class="form-group">
			    		<input type="email" name="email" placeholder="email address" class="form-control registeremail">
			    	</div>
			    	<div class="form-group">
			    		<input type="password" name="password" placeholder="password" class="form-control registerpassword">
			    	</div>
			    	<!--div class="form-group">
			    		<input type="password" name="verifypassword" placeholder="verify password" class="form-control">
			    	</div-->
			    	<div class="row">
			    		<div class="col-sm-12 col-md-8 col-lg-8"><p><small>When you <b>Register</b>, you are agree with our term and conditions</small></p></div>
			    		<div class="col-sm-12 col-md-4 col-lg-4"><button class="btn btn-md btn-success pull-right registerbtn"><i class="fas fa-check-circle"></i> Register</button></div>
			    	</div>

			    </div>
			</div>
		</div>

	</div>
</div>

<!-- Modal -->
<div id="lossPwdModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reset Password</h4>
      </div>
      <div class="modal-body">
      	<span class="alertmsg"></span>
        <p class="text-warning"><i class="fas fa-info-circle"></i> Please enter your valid email address to reset your password.</p>
        <div class="form-group">
        	<input type="email" name="recoveryemail" class="form-control recoveryemail" placeholder="email address">
        </div>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-success recoverybtn"><i class="fas fa-check-circle"></i> Reset</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

@stop

@section('footer')

	<script type="text/javascript">
		// $('.lostpassword').on('click', function() {

		// 	swal({
		// 	  // title: 'Are you sure you want to exit?',
		// 	  width: 400,
		// 	  text: "{{ __('Admin::base.asklogout') }}",
		// 	  // type: 'warning',
		// 	  allowOutsideClick: false,
		// 	  showCancelButton: true,
		// 	  confirmButtonColor: '#3085d6',
		// 	  cancelButtonColor: '#d33',
  //      		  cancelButtonText: "{{ __('Admin::base.cancel') }}",
		// 	}).then((result) => {
		// 	  if (result) {
		// 	    window.location = "{{ route('logout') }}";
		// 	  }
		// 	})

		// });

		$('#lossPwdModal').on('hidden.bs.modal', function () {
		    $('.recoveryemail').val('');
		});

		$('.registerbtn').on('click', function(){
			var fullname = $('.registerfullname').val();
			var email = $('.registeremail').val();
			var password = $('.registerpassword').val();

			$.ajax({
				type: "POST",
				url: '{{ URL::to("frontend/register") }}',
				// url: '{{-- route("frontend.regester") --}}',
				data: {fullname: fullname, email: email, password: password },
				dataType: "json",
				cache:false,
				success:
				function(data) {
					if(data.status == 'FAILED'){
						$('.registeralertmsg').html('<div class="alert alert-danger">'+data.errors+'</div>');
					} else {
						$('.registerform').html('<div class="text-success text-center"><h2><i class="fas fa-check-circle"></i> '+data.msg+'<h2><i class="fa fa-spinner fa-spin"></i></div>');

						setInterval(function () {
			                location.reload();
			            }, 1500);
					}
				}
			});
			return false;
		});

		$('.recoverybtn').on('click', function() {

			var email = $('.recoveryemail').val();

			$.ajax({
				type: "POST",
				url: '{{ URL::to("frontend/reset/password") }}',
				data: {email: email },
				dataType: "json",
				cache:false,
				success:
				function(data) {
					if(data.status == 'FAILED'){
						$('.alertmsg').html('<div class="alert alert-danger">'+data.errors+'</div>');
					} else {
						$('.alertmsg').html('<div class="text-success text-center"><i class="fas fa-check-circle"></i> '+data.msg+'</div>');

						setInterval(function () {
			                location.reload();
			            }, 1500);
					}
				}
			});
			return false;

		});

	</script>
@stop