@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

	<div class="box box-primary">
		<div class="box-header with-border">
			<div class="row">
				<div class="pull-left col-sm-12 col-md-6 col-lg-6">
					<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle"> Add</i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
		@if (session('flash_error'))
		    <div class="alert alert-block alert-error fade in">
		        <strong>{{ __('Admin::base.error') }}!</strong>
		        {!! session('flash_error') !!}
		        <!--span class="close" data-dismiss="alert">×</span-->
		    </div>
		@endif

		<div class="modal-body">
			<div class="table-responsive">
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings">
							<th class="column-title text-center" width="20%">Name</th>
							<th class="column-title text-center" width="30%">Status</th>
							<th class="column-title text-center" width="10%">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($settingjobposition as $job)
						<tr>
							<td align="center">{{ $job->name }}</td>
							<td align="center">{{ $job->type }}</td>
							<td align="center">
								<a href="{{ URL::to('setting/showjobposition', $job->pos_id) }}"><i class="fa fa-edit"></i></a>
								&nbsp;&nbsp;&nbsp;
								<a class="deletedata" value="{{ route('settingjobposition.delete', $job->pos_id) }}" class="btn btn-sm btn-primar" align="center"><i class="fa fa-trash "></i></a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>	</div>


@stop

@section('footer')


<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->

	{!! Form::open(['action'=>'\App\Modules\Setting\Controllers\SettingControllerJobPosition@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1', 'files'=>true]) !!}
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Star</h4>
        </div>
		<div class="modal-body">
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-12">Name</label>
				<div class="col-md-8 col-sm-9 col-xs-12">
					<input class="form-control modaldata" type="text" name="name" id="name" placeholder="Subject" />
				</div>					
			</div>
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-12">Type</label>
				<div class="col-md-8 col-sm-9 col-xs-12">
					<input class="form-control modaldata" type="text" name="type" id="type" placeholder="Subject" />
				</div>					
			</div>
		</div>
        <div class="modal-footer">
			<button type="submit" class="btn btn-sm btn-success pull-right">Submit</button>
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
	{{ Form::close() }}

    </div>
  </div>

<script>
  	$('.deletedata').on('click', function() {
  	
  		var url = $(this).attr('value');

	  	swal({
		  	title: '{{ __("Admin::base.areyousure") }}',
		  	text: "{{ __('Admin::base.norevert') }}",
		  	type: 'warning',
		  	showCancelButton: true,
		  	cancelButtonText: '{{ __("Admin::base.cancel") }}',
		  	confirmButtonColor: '#3085d6',
		  	cancelButtonColor: '#d33',
		  	confirmButtonText: '{{ __("Admin::base.yesdeleteit") }}',
		  
		  	preConfirm: function() {
     			return new Promise(function(resolve) {

     			 window.location.href = url;

     			});
      		},

		}).then(function () {
		  	swal(
			    '{{ __("Admin::base.deleted") }}!',
			    '{{ __("Admin::base.deletedtext") }}.',
			    'success'
		  	)
		});

  	});

</script>
@stop