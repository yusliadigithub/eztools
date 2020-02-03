@extends('layouts.frontend.electro')

@section('header')
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{!! asset('adminLTE/plugins/datepicker/datepicker3.css') !!}">
@stop

@section('breadcrumb')
<h3 class="breadcrumb-header">{{ __('Frontend::frontend.my_account') }}</h3>
@stop

@section('content')
<div class="container">
	
	{{ Form::open(['action'=>'\App\Modules\Frontend\Controllers\FrontendController@updateMyaccount', 'method'=>'post', 'class'=>'form-hrizontal']) }}
	<div class="row">
		<div class="col-sm-12 col-md-2 col-lg-2">

			<h5>{{ __('Frontend::frontend.account_status') }}</h5>
			<ul class="list-unstyled">
				@if($guestinfo->guest_status == 0)
				<li><span class="badge badge-secondary">{{ __('Frontend::frontend.account_unverified') }}</span></li>
				@else
				<li><span class="badge badge-success">{{ __('Frontend::frontend.account_verified') }}</span></li>
				@endif
			</ul><br />

			<h5>{{ __('Frontend::frontend.manage_my_account') }}</h5>
			<ul class="list-unstyled">
				<li><a href="{{ route('frontend.account') }}">{{ __('Frontend::frontend.my_profile') }}</a></li>
				<li><a href="{{ route('frontend.manage.address') }}">{{ __('Frontend::frontend.manage_address') }}</a></li>
				<li><a href="{{ route('frontend.order') }}">{{ __('Frontend::frontend.order_list') }}</a></li>
			</ul><br />

		</div>
		<div class="col-sm-12 col-md-10 col-lg-10">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label class="">{{ __('Frontend::frontend.full_name') }}</label>
								<input type="text" class="form-control input-sm" name="fullname" placeholder="{{ __('Frontend::frontend.full_name') }}" value="{{ $guestinfo->guest_fullname }}">
							</div>
							<div class="form-group">
								<label class="">{{ __('Frontend::frontend.email_address') }}</label>
								<div>{{ $guestinfo->email }}</div>
							</div>
							<div class="form-group">
								<label class="">Twitter</label>
								<input type="text" class="form-control input-sm" name="twitter" placeholder="twitter" value="{{ $guestinfo->guest_twitter }}">
							</div>
							<div class="form-group">
								<label class="">Facebook</label>
								<input type="text" class="form-control input-sm" name="facebook" placeholder="facebook" value="{{ $guestinfo->guest_facebook }}">
							</div>
							<div class="form-group">
								<label class="">Google+</label>
								<input type="text" class="form-control input-sm" name="google" placeholder="google" value="{{ $guestinfo->guest_google }}">
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label>{{ __('Frontend::frontend.dob') }}</label>
								<input type="text" name="dob" id="datepicker" class="form-control input-sm" placeholder="N/A" value="{{ empty($guestinfo->guest_dob) ? '' : date('d F Y', strtotime($guestinfo->guest_dob)) }}">
							</div>
							<div class="form-group">
								<label>{{ __('Frontend::frontend.gender') }}</label>
								{{ Form::gender('gender', [''=>'-- '.__('Admin::base.please_select').' --'], $guestinfo->guest_gender, ['class'=>'form-control input-sm']) }}
							</div>
							<div class="form-group">
								<label>{{ __('Frontend::frontend.phones') }} | <a data-toggle="modal" data-backdrop="static" data-target="#phonesModal" class="text-info" href="javascript:;">{{ __('Admin::base.add') }}</a></label>
								<ol>
								@if(count($guestinfo->phones) > 0)
									@foreach($guestinfo->phones as $phone)
									<li class="list-phone">	
										{{ $phone->guest_phone_value }} 
										<a href="javascript:;" data-id="{{ $phone->guest_phone_id }}" style="display: none" class="phone-delete text-secondary"><i class="fa fa-times-circle"></i></a>
									</li>
									@endforeach
								@else 
									<span class="badge badge-secondary">N/A</span>
								@endif
								</ol>
							</div>

							<div class="form-group"><button class="btn btn-sm btn-success btn-block"><i class="fas fa-check-circle"></i> {{ __('Admin::base.save') }}</button></div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div> 
	{{ Form::close() }}

</div>

<!-- Modal -->
<div id="phonesModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

  	{{ Form::open(['action'=>'\App\Modules\Frontend\Controllers\FrontendController@insertPhone', 'method'=>'post', 'class'=>'form-hrizontal']) }}
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ __('Frontend::frontend.add_phone') }}</h4>
      </div>
      <div class="modal-body">
      	<span class="alertmsg"></span>
        <p class="text-warning"><i class="fas fa-info-circle"></i> {{ __('Frontend::frontend.add_phone_msg') }}</p>
        <div class="form-group">
	        <div class="row">
	        	<div class="col-sm-4">{{ Form::phone_type('phonetype',[''=>'-- '.__('Admin::base.please_select').' --'], '', ['required'=>'','class'=>'form-input input-sm btn-block']) }}</div>
	        	<div class="col-sm-8"><input required="" type="text" placeholder="{{ __('Frontend::frontend.phone_number') }}" name="phone" class="form-control phones input-sm"></div>
	        </div>
	    </div>
      </div>
      <div class="modal-footer">
      	<button type="submit" class="btn btn-success recoverybtn"><i class="fas fa-check-circle"></i> Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    {{ Form::close() }}

  </div>
</div>
@stop

@section('footer')
<script src="{!! asset('adminLTE/plugins/datepicker/bootstrap-datepicker.js') !!}"></script>
<script type="text/javascript">
	//Date picker
    $('#datepicker').datepicker({
    	format: 'dd M yyyy',
      	autoclose: true
    });

    // mouseover phone list
    $('li.list-phone').mouseover(function(){
    	$('.phone-delete', this).show();
    }).mouseleave(function(){
    	$('.phone-delete', this).hide();
    });

    // delete phone
    $('.phone-delete').click(function(){

    	swal({
			  // title: 'Are you sure you want to exit?',
			  width: 400,
			  text: "{{ __('Frontend::frontend.delete_phone_msg') }}",
			  type: 'warning',
			  allowOutsideClick: false,
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
       		  cancelButtonText: "{{ __('Admin::base.cancel') }}",
			}).then((result) => {
			  if (result) {
			    window.location = "{{ URL::to('frontend/delete/phone') }}/"+ $(this).data('id');
			  }
		});
    });

</script>
@stop