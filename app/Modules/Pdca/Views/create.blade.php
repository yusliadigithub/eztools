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
		{!! Form::open(['action'=>'\App\Modules\Pdca\Controllers\PdcaController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1', 'files'=>true]) !!}
		<form class="form-horizontal form-label-left">
	        <div class="box-body">
	            <div class="card">
	              <div class="card-header d-flex p-0" style="border-bottom: 5px solid #337ab7">
	                <ul class="nav nav-pills ml-auto p-2">
	                  <li class="nav-item active"><a class="nav-link active" href="#tab_1" data-toggle="tab">Plan</a></li>
	                  <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Do</a></li>
	                  <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Check</a></li>
	                  <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Action</a></li>
	                </ul>
	              </div><!-- /.card-header -->
	              <br/>
	                <div class="tab-content">
	                  <div class="tab-pane active" id="tab_1" style="border-color: 2px solid #337ab7;"> <!--  tab 1  -->
	                        <br/>
	                        <div class="panel panel-primary">
	                            <div class="panel-heading">Plan
	                            </div>
	                            <br/>
	                            <div class="panel-body">
	                            	<div class="row">
										<div class="form-group">
											<div class="col-md-1"></div>
											<div class="col-md-5 col-xs-12">
												<label><b>Current Problem</b></label>
												<textarea name="p_current_problem" id="p_current_problem" class="form-control" rows="5" placeholder="Current Problem"></textarea>
											</div>
											<div class="col-md-5 col-xs-12">
												<label><b>Cause problem</b></label>
												<textarea name="p_cause_problem" id="p_cause_problem" class="form-control" rows="5" placeholder="Cause problem"></textarea>
											</div>
										</div>
									</div>

	                            	<div class="row">
										<div class="form-group">
											<div class="col-md-1"></div>
											<div class="col-md-5 col-xs-12">
												<label><b>Solution</b></label>
												<textarea name="p_solution" id="p_solution" class="form-control" rows="5" placeholder="Solution"></textarea>
											</div>
											<div class="col-md-5 col-xs-12">
												<label><b>Investment</b></label>
												<textarea name="p_investment" id="p_investment" class="form-control" rows="5" placeholder="Investment"></textarea>
											</div>
										</div>
									</div>

	                            	<div class="row">
										<div class="form-group">
											<div class="col-md-1"></div>
											<div class="col-md-5 col-xs-12">
												<label><b>Benefit</b></label>
												<textarea name="p_benefit" id="p_benefit" class="form-control" rows="5" placeholder="Benefit"></textarea>
											</div>
											<div class="col-md-5 col-xs-12">
												<label><b>Goal</b></label>
												<textarea name="p_goal" id="p_goal" class="form-control" rows="5" placeholder="Goal"></textarea>
											</div>
										</div>
									</div>

	                            </div>
	                        </div>
	                    </div>
	                      <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2" style="border-color: 2px solid #337ab7;"><!--  tab 1  -->
                        <br/>
	                        <div class="panel panel-primary">
	                            <div class="panel-heading">Do</div>
	                            <div class="panel-body">

	                            	<div class="row">
										<div class="form-group">
											<div class="col-md-1"></div>
											<div class="col-md-5 col-xs-12">
												<label><b>Before</b></label>
												<textarea name="d_before" id="d_before" class="form-control" rows="5" placeholder="Before"></textarea>
											</div>
											<div class="col-md-5 col-xs-12">
												<label><b>After</b></label>
												<textarea name="d_after" id="d_after" class="form-control" rows="5" placeholder="After"></textarea>
											</div>
										</div>
									</div>

			                    </div>
			                </div>

	                    </div>
                        <div class="tab-pane" id="tab_3" style="border-color: 2px solid #337ab7;"><!--  tab 1  -->
                        <br/>

		                	<div class="panel panel-primary">
		                    	<div class="panel-heading">Check</div>
	                			<div class="panel-body">

	                            	<div class="row">
										<div class="form-group">
											<div class="col-md-1"></div>
											<div class="col-md-5 col-xs-12">
												<label><b>Follow Up</b></label>
												<textarea name="c_follow_up" id="c_follow_up" class="form-control" rows="5" placeholder="Follow Up"></textarea>
											</div>
											<div class="col-md-5 col-xs-12">
												<label><b>Effect Confirmation</b></label>
												<textarea name="c_effect_confirmation" id="c_effect_confirmation" class="form-control" rows="5" placeholder="Effect Confirmation"></textarea>
											</div>
										</div>
									</div>

		                    	</div>
	                        </div>
	                    </div>
	                      <!-- /.tab-pane -->
	                    <div class="tab-pane" id="tab_4" style="border-color: 2px solid #337ab7;"><!--  tab 1  -->
                        <br/>
	                        <div class="panel panel-primary">
	                            <div class="panel-heading">Action</div>
	                            <div class="panel-body">

	                            	<div class="row">
										<div class="form-group">
											<div class="col-md-1"></div>
											<div class="col-md-5 col-xs-12">
												<label><b>Rebuild</b></label>
												<textarea name="a_rebuild" id="a_rebuild" class="form-control" rows="5" placeholder="Rebuild"></textarea>
											</div>
											<div class="col-md-5 col-xs-12">
												<label><b>Sustain Method</b></label>
												<textarea name="a_sustain_method" id="a_sustain_method" class="form-control" rows="5" placeholder="Effect Confirmation"></textarea>
											</div>
										</div>
									</div>

			                    </div>
			                </div>
			            </div>
					    <div class="box-footer divfooter">
						@hasanyrole('admin','superadmin')
            			<div class="form-group">
            				<label class="control-label col-md-1 col-sm-1 col-xs-6">Created To</label>
            				<div class="col-md-5 col-sm-5 col-xs-6">
            					{{ Form::users('created_to', ['--Please Select--'], old('users'), ['class'=>'form-control','required'=>'required']) }}
            				</div>				
            			</div>
						@endhasanyrole
						<input type="hidden" name="name" value="{{ Auth::user()->name }}" />
						<input type="hidden" name="userid" value="{{ Auth::user()->id }}" />
					        <a href="{{ URL::to('User') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
			  				<button type="submit" class="btn btn-sm btn-success pull-right">Submit</button>
					    </div>
					</div>
				</div>
			</div>
		{{ Form::close() }}


@stop

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

@stop