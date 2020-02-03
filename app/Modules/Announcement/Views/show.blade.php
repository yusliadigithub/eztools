@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

  <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
	<div class="box">
		<div class="box-body">

		@if (session('flash_error'))
		    <div class="alert alert-block alert-error fade in">
		        <strong>{{ __('Admin::base.error') }}!</strong>
		        {!! session('flash_error') !!}
		        <!--span class="close" data-dismiss="alert">×</span-->
		    </div>
		@endif

		{!! Form::open(['action'=>['\App\Modules\Announcement\Controllers\AnnouncementController@update',$announce->announcement_ID], 'method'=>'put', 'class'=>'form-horizontal form-label-left','id'=>'form1', 'files'=>true]) !!}

		<div class="modal-body">
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-12">To</label>
				<div class="col-md-8 col-sm-8 col-xs-12">
					{{ Form::users('announcement_to[]', [], old('users'), ['class'=>'form-control select2','id'=>'selectChartType', 'multiple' => 'multiple']) }}
                <input type="button" id="checkAll" value="check all">
                <input type="button" id="unCheckAll" value="Un-check all">
				</div>				
			</div>
			
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-12">Subject</label>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<input class="form-control modaldata" value="{{ ($announce->subject != '') ? $announce->subject : old('subject') }}" type="text" name="announcement_subject" id="announcement_subject" placeholder="Subject" />
				</div>					
			</div>
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-12">Message</label>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<input class="form-control modaldata" value="{{ ($announce->message != '') ? $announce->message : old('message') }}" type="text" name="announcement_message" id="announcement_message" placeholder="Subject" />
				</div>					
			</div>
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-12">Status</label>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<select class="form-control modaldata" value="{{ ($announce->status != '') ? $announce->status : old('status') }}" type="hidden" name="announcement_status" id="announcement_status" value="active" />
						<option value="active">Active</option>
						<option value="pending">Pending</option>
						<option value="postponed">Postponed</option>
						<option value="expired">Expired</option>
					</select>
				</div>					
			</div>
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-12"></label>
				<div class="col-md-8 col-sm-8 col-xs-12">
		  			<button type="submit" class="btn btn-sm btn-success pull-right">Submit</button>
				</div>					
			</div>
		</div>
		{{ Form::close() }}
	</div>
<script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
@stop

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
  })
  
  $(document).ready(function(){

    $("#checkAll").click(function(){

        $('#selectChartType option').prop('selected', true);
    });
    $("#unCheckAll").click(function(){

        $('#selectChartType option').prop('selected', false);

     });
    });
</script>
@stop