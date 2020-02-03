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
		{!! Form::open(['action'=>['\App\Modules\Pdca\Controllers\PdcaController@update', $pdca->pdca_id], 'method'=>'put', 'class'=>'form-horizontal form-label-left','id'=>'form1', 'files'=>true]) !!}
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
										<div class="col-md-1 col-xs-1"></div>
										<div class="form-group">
											<div class="col-md-5 col-xs-10">

            								@hasanyrole('admin','superadmin')
												<label><b>Current Problem</b></label>
												<textarea name="p_current_problem" id="p_current_problem" readonly class="form-control" rows="5" placeholder="Current Problem">{{ ($pdca->p_current_problem != '') ? $pdca->p_current_problem : old('p_current_problem') }}</textarea>

												<label><b>Admin Remark</b></label>
												<textarea name="p_current_problem_remark" id="p_current_problem_remark" class="form-control" rows="2" placeholder="Current Problem Remark">{{ ($pdca->p_current_problem_remark != '') ? $pdca->p_current_problem_remark : old('p_current_problem_remark') }}</textarea>
											@endhasanyrole
											@hasanyrole('agent')
												<label><b>Current Problem</b></label>
												<textarea name="p_current_problem" id="p_current_problem" class="form-control" rows="5" placeholder="Current Problem">{{ ($pdca->p_current_problem != '') ? $pdca->p_current_problem : old('p_current_problem') }}</textarea>

												<label><b>Admin Remark</b></label>
												<textarea name="p_current_problem_remark" id="p_current_problem_remark" readonly class="form-control" rows="2" placeholder="Current Problem Remark">{{ ($pdca->p_current_problem_remark != '') ? $pdca->p_current_problem_remark : old('p_current_problem_remark') }}</textarea>
											@endhasanyrole

											</div>
											<div class="col-md-5 col-xs-10">

            								@hasanyrole('admin','superadmin')
												<label><b>Cause Problem</b></label>
												<textarea name="p_cause_problem" id="p_cause_problem" class="form-control" readonly rows="5" placeholder="Cause problem">{{ ($pdca->p_cause_problem != '') ? $pdca->p_cause_problem : old('p_cause_problem') }}</textarea>

												<label><b>Admin Remark</b></label>
												<textarea name="p_cause_problem_remark" id="p_cause_problem_remark" class="form-control" rows="2" placeholder="Cause problem Remark">{{ ($pdca->p_cause_problem_remark != '') ? $pdca->p_cause_problem_remark : old('p_cause_problem_remark') }}</textarea>
											@endhasanyrole

            								@hasanyrole('agent')
												<label><b>Cause Problem</b></label>
												<textarea name="p_cause_problem" id="p_cause_problem" class="form-control" rows="5" placeholder="Cause problem">{{ ($pdca->p_cause_problem != '') ? $pdca->p_cause_problem : old('p_cause_problem') }}</textarea>

												<label><b>Admin Remark</b></label>
												<textarea name="p_cause_problem_remark" id="p_cause_problem_remark" readonly class="form-control" rows="2" placeholder="Cause problem Remark">{{ ($pdca->p_cause_problem_remark != '') ? $pdca->p_cause_problem_remark : old('p_cause_problem_remark') }}</textarea>
											@endhasanyrole

											</div>
										</div>
									</div>
									<hr>
	                            	<div class="row">
										<div class="col-md-1 col-xs-1"></div>
										<div class="form-group">
											<div class="col-md-5 col-xs-10">

        									@hasanyrole('admin','superadmin')
												<label><b>Solution</b></label>
												<textarea name="p_solution" id="p_solution" class="form-control" readonly rows="5" placeholder="Solution">{{ ($pdca->p_solution != '') ? $pdca->p_solution : old('p_solution') }}</textarea>

												<label><b>Solution Remark</b></label>
												<textarea name="p_solution_remark" id="p_solution_remark" class="form-control" rows="2" placeholder="Solution Remark">{{ ($pdca->p_solution_remark != '') ? $pdca->p_solution_remark : old('p_solution_remark') }}</textarea>
											@endhasanyrole

        									@hasanyrole('agent')
												<label><b>Solution</b></label>
												<textarea name="p_solution" id="p_solution" class="form-control" rows="5" placeholder="Solution">{{ ($pdca->p_solution != '') ? $pdca->p_solution : old('p_solution') }}</textarea>

												<label><b>Solution Remark</b></label>
												<textarea name="p_solution_remark" id="p_solution_remark" readonly class="form-control" rows="2" placeholder="Solution Remark">{{ ($pdca->p_solution_remark != '') ? $pdca->p_solution_remark : old('p_solution_remark') }}</textarea>
											@endhasanyrole

											</div>
											<div class="col-md-5 col-xs-10">

        									@hasanyrole('admin','superadmin')
												<label><b>Investment</b></label>
												<textarea name="p_investment" id="p_investment" class="form-control" readonly rows="5" placeholder="Investment">{{ ($pdca->p_investment != '') ? $pdca->p_investment : old('p_investment') }}</textarea>

												<label><b>Investment Remark</b></label>
												<textarea name="p_investment_remark" id="p_investment_remark" class="form-control" rows="2" placeholder="Investment Remark">{{ ($pdca->p_investment_remark != '') ? $pdca->p_investment_remark : old('p_investment_remark') }}</textarea>
											@endhasanyrole

        									@hasanyrole('agent')
												<label><b>Investment</b></label>
												<textarea name="p_investment" id="p_investment" class="form-control" rows="5" placeholder="Investment">{{ ($pdca->p_investment != '') ? $pdca->p_investment : old('p_investment') }}</textarea>

												<label><b>Investment Remark</b></label>
												<textarea name="p_investment_remark" id="p_investment_remark" readonly class="form-control" rows="2" placeholder="Investment Remark">{{ ($pdca->p_investment_remark != '') ? $pdca->p_investment_remark : old('p_investment_remark') }}</textarea>
											@endhasanyrole

											</div>
										</div>
									</div>
									<hr>
	                            	<div class="row">
										<div class="col-md-1 col-xs-1"></div>
										<div class="form-group">
											<div class="col-md-5 col-xs-10">

        									@hasanyrole('admin','superadmin')
												<label><b>Benefit</b></label>
												<textarea name="p_benefit" id="p_benefit" class="form-control" readonly rows="5" placeholder="Benefit">{{ ($pdca->p_benefit != '') ? $pdca->p_benefit : old('p_benefit') }}</textarea>

												<label><b>Benefit remark</b></label>
												<textarea name="p_benefit_remark" id="p_benefit_remark" class="form-control"  rows="2" placeholder="Benefit">{{ ($pdca->p_benefit_remark != '') ? $pdca->p_benefit_remark : old('p_benefit_remark') }}</textarea>
											@endhasanyrole

        									@hasanyrole('agent')
												<label><b>Benefit</b></label>
												<textarea name="p_benefit" id="p_benefit" class="form-control" rows="5" placeholder="Benefit">{{ ($pdca->p_benefit != '') ? $pdca->p_benefit : old('p_benefit') }}</textarea>

												<label><b>Benefit remark</b></label>
												<textarea name="p_benefit_remark" id="p_benefit_remark" class="form-control" readonly rows="2" placeholder="Benefit">{{ ($pdca->p_benefit_remark != '') ? $pdca->p_benefit_remark : old('p_benefit_remark') }}</textarea>
											@endhasanyrole

											</div>
											<div class="col-md-5 col-xs-10">

        									@hasanyrole('admin','superadmin')
												<label><b>Goal</b></label>
												<textarea name="p_goal" id="p_goal" class="form-control" rows="5" readonly placeholder="Goal">{{ ($pdca->p_goal != '') ? $pdca->p_goal : old('p_goal') }}</textarea>

												<label><b>Goal remark</b></label>
												<textarea name="p_goal_remark" id="p_goal_remark" class="form-control" rows="2" placeholder="Goal Remark">{{ ($pdca->p_goal_remark != '') ? $pdca->p_goal_remark : old('p_goal_remark') }}</textarea>
											@endhasanyrole

        									@hasanyrole('agent')
												<label><b>Goal</b></label>
												<textarea name="p_goal" id="p_goal" class="form-control" rows="5" placeholder="Goal">{{ ($pdca->p_goal != '') ? $pdca->p_goal : old('p_goal') }}</textarea>

												<label><b>Goal remark</b></label>
												<textarea name="p_goal_remark" id="p_goal_remark" class="form-control" readonly rows="2" placeholder="Goal Remark">{{ ($pdca->p_goal_remark != '') ? $pdca->p_goal_remark : old('p_goal_remark') }}</textarea>
											@endhasanyrole

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
										<div class="col-md-1 col-xs-1"></div>
										<div class="form-group">
											<div class="col-md-5 col-xs-10">

        									@hasanyrole('admin','superadmin')
												<label><b>Before</b></label>
												<textarea name="d_before" id="d_before" class="form-control" readonly rows="5" placeholder="Before">{{ ($pdca->d_before != '') ? $pdca->d_before : old('d_before') }}</textarea>

												<label><b>Before Remark</b></label>
												<textarea name="d_before_remark" id="d_before_remark" class="form-control" rows="2" placeholder="Before Remark">{{ ($pdca->d_before_remark != '') ? $pdca->d_before_remark : old('d_before_remark') }}</textarea>
											@endhasanyrole
        									@hasanyrole('agent')
												<label><b>Before</b></label>
												<textarea name="d_before" id="d_before" class="form-control" rows="5" placeholder="Before">{{ ($pdca->d_before != '') ? $pdca->d_before : old('d_before') }}</textarea>

												<label><b>Before Remark</b></label>
												<textarea name="d_before_remark" id="d_before_remark" readonly class="form-control" rows="2" placeholder="Before Remark">{{ ($pdca->d_before_remark != '') ? $pdca->d_before_remark : old('d_before_remark') }}</textarea>
											@endhasanyrole

											</div>
											<div class="col-md-5 col-xs-10">

        									@hasanyrole('admin','superadmin')
												<label><b>After</b></label>
												<textarea name="d_after" id="d_after" class="form-control" readonly rows="5" placeholder="After">{{ ($pdca->d_after != '') ? $pdca->d_after : old('d_after') }}</textarea>

												<label><b>After Remark</b></label>
												<textarea name="d_after_remark" id="d_after_remark" class="form-control" rows="2" placeholder="After Remark">{{ ($pdca->d_after_remark != '') ? $pdca->d_after_remark : old('d_after_remark') }}</textarea>
											@endhasanyrole
        									@hasanyrole('agent')
												<label><b>After</b></label>
												<textarea name="d_after" id="d_after" class="form-control" rows="5" placeholder="After">{{ ($pdca->d_after != '') ? $pdca->d_after : old('d_after') }}</textarea>

												<label><b>After Remark</b></label>
												<textarea name="d_after_remark" id="d_after_remark" readonly class="form-control" rows="2" placeholder="After Remark">{{ ($pdca->d_after_remark != '') ? $pdca->d_after_remark : old('d_after_remark') }}</textarea>
											@endhasanyrole

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
										<div class="col-md-1 col-xs-1"></div>
										<div class="form-group">
											<div class="col-md-5 col-xs-10">

        									@hasanyrole('admin','superadmin')
												<label><b>Follow Up</b></label>
												<textarea name="c_follow_up" id="c_follow_up" class="form-control" readonly rows="5" placeholder="Follow Up">{{ ($pdca->c_follow_up != '') ? $pdca->c_follow_up : old('c_follow_up') }}</textarea>

												<label><b>Follow Up Remark</b></label>
												<textarea name="c_follow_up_remark" id="c_follow_up_remark" class="form-control" rows="2" placeholder="Follow Up Remark">{{ ($pdca->c_follow_up_remark != '') ? $pdca->c_follow_up_remark : old('c_follow_up_remark') }}</textarea>
											@endhasanyrole
        									@hasanyrole('agent')
												<label><b>Follow Up</b></label>
												<textarea name="c_follow_up" id="c_follow_up" class="form-control" rows="5" placeholder="Follow Up">{{ ($pdca->c_follow_up != '') ? $pdca->c_follow_up : old('c_follow_up') }}</textarea>

												<label><b>Follow Up Remark</b></label>
												<textarea name="c_follow_up_remark" id="c_follow_up_remark" readonly class="form-control" rows="2" placeholder="Follow Up Remark">{{ ($pdca->c_follow_up_remark != '') ? $pdca->c_follow_up_remark : old('c_follow_up_remark') }}</textarea>
											@endhasanyrole

											</div>
											<div class="col-md-5 col-xs-10">

        									@hasanyrole('admin','superadmin')
												<label><b>Effect Confirmation</b></label>
												<textarea name="c_effect_confirmation" id="c_effect_confirmation" readonly class="form-control" rows="5" placeholder="Effect Confirmation">{{ ($pdca->c_effect_confirmation != '') ? $pdca->c_effect_confirmation : old('c_effect_confirmation') }}</textarea>

												<label><b>Effect Confirmation Remark</b></label>
												<textarea name="c_effect_confirmation_Remark" id="c_effect_confirmation_Remark" class="form-control" rows="2" placeholder="Effect Confirmation Remark">{{ ($pdca->c_effect_confirmation_Remark != '') ? $pdca->c_effect_confirmation_Remark : old('c_effect_confirmation_Remark') }}</textarea>
											@endhasanyrole
        									@hasanyrole('agent')
												<label><b>Effect Confirmation</b></label>
												<textarea name="c_effect_confirmation" id="c_effect_confirmation" class="form-control" rows="5" placeholder="Effect Confirmation">{{ ($pdca->c_effect_confirmation != '') ? $pdca->c_effect_confirmation : old('c_effect_confirmation') }}</textarea>

												<label><b>Effect Confirmation Remark</b></label>
												<textarea name="c_effect_confirmation_Remark" id="c_effect_confirmation_Remark" readonly class="form-control" rows="2" placeholder="Effect Confirmation Remark">{{ ($pdca->c_effect_confirmation_Remark != '') ? $pdca->c_effect_confirmation_Remark : old('c_effect_confirmation_Remark') }}</textarea>
											@endhasanyrole

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
										<div class="col-md-1 col-xs-1"></div>
										<div class="form-group">
											<div class="col-md-5 col-xs-10">

        									@hasanyrole('admin','superadmin')
												<label><b>Rebuild</b></label>
												<textarea name="a_rebuild" id="a_rebuild" class="form-control" readonly rows="5" placeholder="Rebuild">{{ ($pdca->a_rebuild != '') ? $pdca->a_rebuild : old('a_rebuild') }}</textarea>

												<label><b>Rebuild Remark</b></label>
												<textarea name="a_rebuild_remark" id="a_rebuild_remark" class="form-control" rows="2" placeholder="Rebuild Remark">{{ ($pdca->a_rebuild_remark != '') ? $pdca->a_rebuild_remark : old('a_rebuild_remark') }}</textarea>
											@endhasanyrole
        									@hasanyrole('agent')
												<label><b>Rebuild</b></label>
												<textarea name="a_rebuild" id="a_rebuild" class="form-control" rows="5" placeholder="Rebuild">{{ ($pdca->a_rebuild != '') ? $pdca->a_rebuild : old('a_rebuild') }}</textarea>

												<label><b>Rebuild Remark</b></label>
												<textarea name="a_rebuild_remark" id="a_rebuild_remark" readonly class="form-control" rows="2" placeholder="Rebuild Remark">{{ ($pdca->a_rebuild_remark != '') ? $pdca->a_rebuild_remark : old('a_rebuild_remark') }}</textarea>
											@endhasanyrole

											</div>
											<div class="col-md-5 col-xs-10">

        									@hasanyrole('admin','superadmin')
												<label><b>Sustain Method</b></label>
												<textarea name="a_sustain_method" id="a_sustain_method" readonly class="form-control" rows="5" placeholder="Effect Confirmation">{{ ($pdca->a_sustain_method != '') ? $pdca->a_sustain_method : old('a_sustain_method') }}</textarea>

												<label><b>Sustain Method Remark</b></label>
												<textarea name="a_sustain_method_remark" id="a_sustain_method_remark" class="form-control" rows="2" placeholder="Effect Confirmation Remark">{{ ($pdca->a_sustain_method_remark != '') ? $pdca->a_sustain_method_remark : old('a_sustain_method_remark') }}</textarea>
											@endhasanyrole
        									@hasanyrole('agent')
												<label><b>Sustain Method</b></label>
												<textarea name="a_sustain_method" id="a_sustain_method" class="form-control" rows="5" placeholder="Effect Confirmation">{{ ($pdca->a_sustain_method != '') ? $pdca->a_sustain_method : old('a_sustain_method') }}</textarea>

												<label><b>Sustain Method Remark</b></label>
												<textarea name="a_sustain_method_remark" id="a_sustain_method_remark" readonly class="form-control" rows="2" placeholder="Effect Confirmation Remark">{{ ($pdca->a_sustain_method_remark != '') ? $pdca->a_sustain_method_remark : old('a_sustain_method_remark') }}</textarea>
											@endhasanyrole

											</div>
										</div>
									</div>

			                    </div>
			                </div>
			            </div>
						<input type="hidden" name="user_id" value="" />
					    <div class="box-footer divfooter">

							@hasanyrole('admin','superadmin')
							<div class="form-group">
								<div class="col-md-4 col-xs-12">
				                <label>Status</label>
				                <select class="form-control" name="status" value="{{ ($pdca->status != '') ? $pdca->status : old('status') }}" id="status" tabindex="-1" aria-hidden="true">
				                  @if($pdca->status == 'pending')
				                  <option value="approved">Approve</option>
				                  <option selected="selected" value="pending">Pending</option>
				                  @else
				                  <option selected="selected" value="approved">Approve</option>
				                  <option value="pending">Pending</option>
				                  @endif
				                </select>
				              </div>
				          	</div>
							@endhasanyrole

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