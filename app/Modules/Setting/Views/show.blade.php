@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')


	<div class="box">
		<div class="box-body">

		@if (session('flash_error'))
		    <div class="alert alert-block alert-error fade in">
		        <strong>{{ __('Admin::base.error') }}!</strong>
		        {!! session('flash_error') !!}
		        <!--span class="close" data-dismiss="alert">×</span-->
		    </div>
		@endif

		<div class="modal-body">
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-12">{{ $setting->type }}</label>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<input class="form-control modaldata" type="text" name="announcement_subject" id="announcement_subject" placeholder="Subject" />
				</div>					
			</div>
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-12"></label>
				<div class="col-md-8 col-sm-8 col-xs-12">
		  			<button type="submit" class="btn btn-sm btn-success pull-right">Submit</button>
				</div>					
			</div>
		</div>
	</div>


@stop

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

@stop