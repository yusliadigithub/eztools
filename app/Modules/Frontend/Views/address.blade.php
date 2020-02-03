@extends('layouts.frontend.electro')

@section('breadcrumb')
<h3 class="breadcrumb-header">{{ __('Frontend::frontend.my_address') }}</h3>
@stop

@section('content')
<div class="container">
	
	{{ Form::open(['action'=>'\App\Modules\Frontend\Controllers\FrontendController@insertMyAddress', 'method'=>'post', 'class'=>'form-hrizontal']) }}
	
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
						<div class="col-sm-12">
							<div class="form-group">
								<label class="radio-inline">
							      <input required="" type="radio" name="addresstype" value="SHIPPING">SHIPPING
							    </label>
							    <label class="radio-inline">
							      <input required="" type="radio" name="addresstype" value="BILLING">BILLING
							    </label>
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-sm-6">
							<div class="form-group">
								<label>{{ __('Frontend::frontend.full_name') }}</label>
								<input required="" type="text" value="{{ old('name') }}" name="name" class="form-control input-sm">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>{{ __('Frontend::frontend.phones') }}</label>
								<input required="" type="text" value="{{ old('phone') }}" name="phone" class="form-control input-sm">
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label>{{ __('Merchant::merchant.address') }}</label>
								<input required="" type="text" value="{{ old('address') }}" name="address" class="form-control input-sm">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label>{{ __('Merchant::merchant.address2') }}</label>
								<input type="text" value="{{ old('address2') }}" name="address2" class="form-control input-sm">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label>{{ __('Merchant::merchant.address3') }}</label>
								<input type="text" value="{{ old('address3') }}" name="address3" class="form-control input-sm">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label>{{ __('Merchant::merchant.postcode') }}</label>
								<input required="" value="{{ old('postcode') }}" type="text" id="postcode" name="postcode" class="form-control input-sm">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label>{{ __('Merchant::merchant.district') }}</label>
								<select name="district_id" id="district_id" class="form-control input-sm" required="">
                                    <option value="" {{ (old('district_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                                </select>
							</div>
						</div><div class="col-sm-4">
							<div class="form-group">
								<label>{{ __('Merchant::merchant.state') }}</label>
								<select name="state_id" id="state_id" class="form-control input-sm" required="">
                                    <option value="" {{ (old('state_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                                </select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
						</div>
					</div>
				</div>
			</div>

			@if(count($guestinfo->shippingAddresses) > 0)
			
			<table class="table table-hover table-responsive">
				<thead>
					<tr>
						<th>{{ __('Frontend::frontend.address_type') }}</th>
						<th>{{ __('Merchant::merchant.address') }}</th>
						<th>{{ __('Merchant::merchant.postcode') }}</th>
						<th>{{ __('Frontend::frontend.address_default') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($guestinfo->shippingAddresses as $address)
						<tr class="list-address">
							<td>{{ $address->guest_address_type }}
								<div style="margin-top:13px;">
									@if(!$address->guest_address_default == 1) <!-- if address is not default, show delete button -->
									<a style="display: none;" href="javascript:;" data-id="{{ $address->guest_address_id }}" class="text-secondary address-delete"><i class="fas fa-trash"></i></a>
									@endif
								</div>
							</td>
							<td><div><small>{{ strtoupper($address->guest_address_name) }}</small></div>
								<small>{{ strtoupper($address->guest_address_one) }} {{ strtoupper($address->guest_address_two) }} {{ strtoupper($address->guest_address_three) }}</small>
								<div><small>{{ $address->guest_address_phone }}</small></div>
							</td>
							<td>{{ $address->state->state_desc }} - {{ $address->district->district_desc }} - {{ $address->guest_address_postcode }}</td>
							<td>
								<?php $checked = ($address->guest_address_default == 1) ? 'checked=""' : '' ?>
								<div class="checkbox checbox-switch switch-dark default_add" data-type="{{ $address->guest_address_type }}" data-id="{{ encrypt($address->guest_address_id) }}"><label><input type="radio" name="{{ $address->guest_address_type }}" value="{{ $address->guest_address_default }}" {{ $checked }} ><span></span></label></div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			
			@endif

			@if(count($guestinfo->billingAddresses) > 0)
			
			<table class="table table-hover table-responsive">
				<thead>
					<tr>
						<th>{{ __('Frontend::frontend.address_type') }}</th>
						<th>{{ __('Merchant::merchant.address') }}</th>
						<th>{{ __('Merchant::merchant.postcode') }}</th>
						<th>{{ __('Frontend::frontend.address_default') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($guestinfo->billingAddresses as $address)
						<tr class="list-address">
							<td>{{ $address->guest_address_type }}
								<div style="margin-top:13px;">
									@if(!$address->guest_address_default == 1) <!-- if address is not default, show delete button -->
									<a style="display: none;" href="javascript:;" data-id="{{ $address->guest_address_id }}" class="text-secondary address-delete"><i class="fas fa-trash"></i></a>
									@endif
								</div>
							</td>
							<td><div><small>{{ strtoupper($address->guest_address_name) }}</small></div>
								<small>{{ strtoupper($address->guest_address_one) }} {{ strtoupper($address->guest_address_two) }} {{ strtoupper($address->guest_address_three) }}</small>
								<div><small>{{ $address->guest_address_phone }}</small></div>
							</td>
							<td>{{ $address->state->state_desc }} - {{ $address->district->district_desc }} - {{ $address->guest_address_postcode }}</td>
							<td>
								<?php $checked = ($address->guest_address_default == 1) ? 'checked=""' : '' ?>
								<div class="checkbox checbox-switch switch-dark default_add" data-type="{{ $address->guest_address_type }}" data-id="{{ encrypt($address->guest_address_id) }}"><label><input type="radio" name="{{ $address->guest_address_type }}" value="{{ $address->guest_address_default }}" {{ $checked }} ><span></span></label></div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			
			@endif
		</div>
	</div>
	{{ Form::close() }}

</div>


@stop

@section('footer')
<script type="text/javascript">


	$(document).ready(function() {

        if($('#postcode').val()!=''){
            getstatedistrict('{{ old("postcode") }}');
        }

        $('#postcode').on('keyup', function(e){
            
            var code = $(this).val();
            getstatedistrict(code);

        });

        function getstatedistrict(code){

            $('#district_id').empty();
            $('#state_id').empty();
            
            if(code.length>4){

                $.ajax({
                    url: '{{ URL::to("admin/getStateDistrict") }}/'+code,
                    type: 'get',
                    dataType: 'json',
                    success:function(data) {

                        if(data.states!=null){
                            console.log(data);
                            var district = data.districts;
                            var state = data.states;

                            $('#district_id').append('<option value="'+district.district_id+'">'+district.district_desc+'</option>');
                            $('#state_id').append('<option value="'+state.state_id+'">'+state.state_desc+'</option>');
                        }else{
                            swal('{{ __("Admin::base.norecordfound") }}','{{ __("Admin::base.chooseother") }}','error');
                            $('#district_id').append('<option value="">-- No Record Found --</option>');
                            $('#state_id').append('<option value="">-- No Record Found --</option>');
                        }

                    }
                });

            }else{
                $('#district_id').append('<option value="">{{ __("Admin::base.please_type").' '.__("Admin::base.postcode") }}</option>');
                $('#state_id').append('<option value="">{{ __("Admin::base.please_type").' '.__("Admin::base.postcode") }}</option>');
            }

        }

    });
	
    // mouseover phone list
    $('tr.list-address').mouseover(function(){
    	$('.address-delete', this).show();
    }).mouseleave(function(){
    	$('.address-delete', this).hide();
    });

    // delete phone
    $('.address-delete').click(function(){

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
			    window.location = "{{ URL::to('frontend/delete/address') }}/"+ $(this).data('id');
			  }
		});
    });


    // set default address
    $('.default_add').on('click', function() {

    	$.ajax({

    		type: "GET",
			url : '{{ URL::to("frontend/setdefault/address") }}',
			data: {aid: $(this).data('id'), atype: $(this).data('type') },
			dataType: "json",
			cache:false,
			success:
			function(data) {
				// if(data.status == 'OK') {
				// 	window.location.reload();
				// }
			}

    	});

    });

</script>
@stop