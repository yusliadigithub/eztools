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

		{!! Form::open(['action'=>['\App\Modules\StaffDetails\Controllers\StaffDetailsController@update',$staffdetails->id], 'method'=>'put', 'class'=>'form-horizontal form-label-left','id'=>'form1', 'files'=>true]) !!}
	        <div class="box-body">
	            <div class="card">
	              <div class="card-header d-flex p-0" style="border-bottom: 5px solid #337ab7">
	                <ul class="nav nav-pills ml-auto p-2">
	                  <li class="nav-item active"><a class="nav-link active" href="#tab_1" data-toggle="tab">Personal Info</a></li>
	                  <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Job</a></li>
	                  <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Salary</a></li>
	                  <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Skill</a></li>
	                </ul>
	              </div><!-- /.card-header -->
	              <br/>
	                <div class="tab-content">
	                  <div class="tab-pane active" id="tab_1" style="border-color: 2px solid #337ab7;"> <!--  tab 1  -->
	                        <br/>
	                        <div class="panel panel-primary">
	                            <div class="panel-heading">Personal Info
	                            </div>
	                            <br/>
	                            <div class="panel-body">
	                                <div class="form-group">
	                                <label for="staff_ID" class="col-sm-3 control-label">Staff ID</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" name="staff_ID" id="staff_ID" maxlength="100" type="text" value="{{ ($staffdetails->staff_id != '') ? $staffdetails->staff_id : old('staff_id') }}" placeholder="Staff ID">
	                                        <small><a href="#">Available?</a></small>
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <label for="first_name" class="col-sm-3 control-label">Full Name</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" name="first_name" id="first_name" readonly maxlength="100" placeholder="First name" type="text" value="{{ ($staffdetails->staff_name != '') ? $staffdetails->staff_name : old('staff_name') }}">
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <label for="first_name" class="col-sm-3 control-label">Mobile Number</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" name="mobile_no" id="mobile_no" maxlength="100" placeholder="First name" type="text" value="{{ ($staffdetails->staff_mobileno != '') ? $staffdetails->staff_mobileno : old('staff_mobileno') }}">
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <label for="first_name" class="col-sm-3 control-label">Address</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" name="address" id="address" maxlength="100" placeholder="Address" type="text" value="{{ ($staffdetails->address != '') ? $staffdetails->address : old('address') }}">
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <label for="last_name" class="col-sm-3 control-label">Email</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" value="{{ ($staffdetails->staff_email != '') ? $staffdetails->staff_email : old('staff_email') }}" name="staff_email" id="staff_email" maxlength="100" placeholder="your_email@email.com" type="email">
	                                    </div>
	                                </div>

	                                <div class="form-group hidden">
	                                    <label for="staff_gender" class="col-sm-3 control-label">Gender</label>
	                                    <div class="col-sm-7">
	                                      <input class="form-control" type="text" value="{{ ($staffdetails->gender != '') ? $staffdetails->gender : old('gender') }}" name="staff_gender"> 
	                                    </div>
	                                </div>
	                                
	                                <div class="form-group">
	                                    <label for="staff_gender" class="col-sm-3 control-label">Gender</label>
	                                    <div class="col-sm-7">
        									@if($staffdetails->gender == 'male')
                                                <input type="radio" name="staff_gender" checked value="male"> Male<br>
        									@else
                                                <input type="radio" name="staff_gender" value="male"> Male<br>
        									@endif
        									@if($staffdetails->gender == 'female')
                                                <input type="radio" name="staff_gender" checked value="female">Female<br>
        									@else
                                                <input type="radio" name="staff_gender" value="female">Female<br>
        									@endif
        									@if($staffdetails->gender == 'unkown')
                                                <input type="radio" name="staff_gender" checked value="unkown">Unkown<br>
        									@else
                                                <input type="radio" name="staff_gender" value="unkown">Unkown<br>
        									@endif
	                                    </div>
	                                </div>
	                                

	                                <div class="form-group">
	                                    <label for="birth_date" class="col-sm-3 control-label">Birth Date</label>
	                                    <div class="col-sm-7">
        									@if($staffdetails->birth_date != '')
	                                        	<input class="form-control" readonly value="{{ ($staffdetails->birth_date != '') ? $staffdetails->birth_date : old('birth_date') }}" name="birth_date" id="birth_date" maxlength="100" placeholder="Birth Date" type="text">
        									@else
	                                        	<input class="form-control" value="{{ ($staffdetails->birth_date != '') ? $staffdetails->birth_date : old('birth_date') }}" name="birth_date" id="birth_date" maxlength="100" placeholder="Birth Date" type="date">
        									@endif
	                                    </div>
	                                </div>

	                                <div class="form-group hidden">
	                                    <label for="birth_date" class="col-sm-3 control-label">Status Approve</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" value="1" name="status_approve" id="status_approve" type="text">
	                                    </div>
	                                </div>

	                                <div class="form-group hidden">
	                                    <label for="birth_date" class="col-sm-3 control-label">Status</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" value="1" name="status" id="status" type="text">
	                                    </div>
	                                </div>

	                            </div>

	                            <div class="panel-heading">Nationality
	                            </div>
	                            <br/>
	                            <div class="form-group">
	                                <label for="text" class="col-sm-3 control-label">Nationality</label>
	                                <div class="col-sm-7">
	                                    <input id="text" type="text" value="{{ ($staffdetails->nationality != '') ? $staffdetails->nationality : old('nationality') }}" class="form-control" name="Nationality" placeholder="Nationality">
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label for="passport_no" class="col-sm-3 control-label">Passport No</label>
	                                <div class="col-sm-7">
	                                    <input id="passport_no" type="text" value="{{ ($staffdetails->passport_no != '') ? $staffdetails->passport_no : old('passport_no') }}" class="form-control" name="passport_no" placeholder="Passport No">
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label for="nric" class="col-sm-3 control-label">NRIC</label>
	                                <div class="col-sm-7">
	                                    <input id="nric" type="text" value="{{ ($staffdetails->nric != '') ? $staffdetails->nric : old('nric') }}" class="form-control" name="nric" placeholder="NRIC">
	                                    <small><a href="#">Available?</a></small>
	                                </div>
	                            </div>

	                            <br/>
	                            <div class="panel-heading">Additional Details
	                            </div>
	                            <br/>
	                            <div class="form-group">
	                                <label for="etnicity" class="col-sm-3 control-label">Etnicity</label>
	                                <div class="col-sm-7">
	                                    <input id="etnicity" type="etnicity" value="{{ ($staffdetails->etnicity != '') ? $staffdetails->etnicity : old('etnicity') }}" class="form-control" name="etnicity" placeholder="Etnicity">
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label for="religion" class="col-sm-3 control-label">Religion</label>
	                                <div class="col-sm-7">
	                                    <input id="religion" type="religion" value="{{ ($staffdetails->religion != '') ? $staffdetails->religion : old('religion') }}" class="form-control" name="religion" placeholder="Religion">
	                                </div>
	                            </div>
	                            </div>
	                        </div>
	                      <!-- /.tab-pane -->
	                      <div class="tab-pane" id="tab_2" style="border-color: 2px solid #337ab7;"><!--  tab 1  -->
	                        <br/>
	                        <div class="panel panel-primary">
	                            <div class="panel-heading">Employment Info
	                            </div><br/>
	                        <div class="form-group">
	                            <label for="date_joined" class="col-sm-3 control-label">Date Joined</label>
	                            <div class="col-sm-6">

									@if($staffdetails->date_joined != '')
		                                <input class="form-control" name="date_joined" readonly value="{{ ($staffdetails->date_joined != '') ? $staffdetails->date_joined : old('date_joined') }}" id="date_joined" maxlength="100" placeholder="Date Joined" type="text">
									@else
	                                	<input class="form-control" name="date_joined" value="{{ ($staffdetails->date_joined != '') ? $staffdetails->date_joined : old('date_joined') }}" id="date_joined" maxlength="100" placeholder="Date Joined" type="date">
									@endif

	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="endofprobation" class="col-sm-3 control-label">End Of Probation</label>
	                            <div class="col-sm-7">
									@if($staffdetails->end_of_probation != '')
	                                	<input class="form-control" name="endofprobation" readonly value="{{ ($staffdetails->end_of_probation != '') ? $staffdetails->end_of_probation : old('end_of_probation') }}" id="endofprobation" maxlength="20" placeholder="End OF Probation" type="text">
									@else
	                                	<input class="form-control" name="endofprobation" value="{{ ($staffdetails->end_of_probation != '') ? $staffdetails->end_of_probation : old('end_of_probation') }}" id="endofprobation" maxlength="20" placeholder="End OF Probation" type="date">
									@endif
	                            </div>
	                        </div>
	                        <div class="panel-heading">Job Status
	                        </div>
	                        <div class="panel-body">
	                        <div class="form-group">
	                            <label for="jobposition" class="col-sm-3 control-label">Job Position</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="jobposition" value="{{ ($staffdetails->job_pos != '') ? $staffdetails->job_pos : old('job_pos') }}" id="jobposition" maxlength="20" placeholder="Job Position" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="line_supervision" class="col-sm-3 control-label">Line Supervission</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="line_supervision" value="{{ ($staffdetails->line_supervision != '') ? $staffdetails->line_supervision : old('line_supervision') }}" id="line_supervision" maxlength="20" placeholder="Line Supervission" type="text">
	                            </div>
	                        </div>
	                    </div>

	                    <div class="panel-heading">Employment Status
	                    </div>
	                    <div class="panel-body">

	                        <div class="form-group">
	                            <label for="jobtype" class="col-sm-3 control-label">Job Type</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="jobtype" value="{{ ($staffdetails->job_type != '') ? $staffdetails->job_type : old('job_type') }}" id="jobtype" maxlength="20" placeholder="Job Type" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="jobstatus" class="col-sm-3 control-label">Job Status</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="jobstatus" value="{{ ($staffdetails->job_status != '') ? $staffdetails->job_status : old('job_status') }}" id="jobstatus" maxlength="100" type="text" placeholder="Job Status">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="WorkDays" class="col-sm-3 control-label">WorkDays</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="WorkDays" value="{{ ($staffdetails->workdays != '') ? $staffdetails->workdays : old('workdays') }}" id="WorkDays" maxlength="100" type="text" placeholder="WorkDays">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="salary_effective_date" class="col-sm-3 control-label">Effective Date</label>
	                            <div class="col-sm-7">
									@if($staffdetails->salary_effective_date != '')
	                                	<input class="form-control" name="salary_effective_date" readonly value="{{ ($staffdetails->salary_effective_date != '') ? $staffdetails->salary_effective_date : old('salary_effective_date') }}" id="salary_effective_date" maxlength="100" type="text" placeholder="EffectiveDate">
									@else
	                                	<input class="form-control" name="salary_effective_date" value="{{ ($staffdetails->salary_effective_date != '') ? $staffdetails->salary_effective_date : old('salary_effective_date') }}" id="salary_effective_date" maxlength="100" type="date" placeholder="EffectiveDate">
									@endif
	                            </div>
	                        </div>
	                    </div>

	                    <div class="panel-heading">Permit</div>
	                    <div class="panel-body">

	                        <div class="form-group">
	                            <label for="permit" class="col-sm-3 control-label">Permit</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="permit" value="{{ ($staffdetails->permitt != '') ? $staffdetails->permitt : old('permitt') }}" id="permit" placeholder="Permit" maxlength="100" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="permitEffectiveDate" class="col-sm-3 control-label">Effective Date</label>
	                            <div class="col-sm-7">
									@if($staffdetails->salary_effective_date != '')
	                                <input class="form-control" name="permitEffectiveDate" readonly value="{{ ($staffdetails->salary_effective_date != '') ? $staffdetails->salary_effective_date : old('salary_effective_date') }}" id="permitEffectiveDate" maxlength="100" placeholder="Effective Date" type="text">
									@else
	                                <input class="form-control" name="permitEffectiveDate" value="{{ ($staffdetails->salary_effective_date != '') ? $staffdetails->salary_effective_date : old('salary_effective_date') }}" id="permitEffectiveDate" maxlength="100" placeholder="Effective Date" type="date">
									@endif
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="permitFrom" class="col-sm-3 control-label">Permit From</label>
	                            <div class="col-sm-7">
									@if($staffdetails->permit_from != '')
	                                <input class="form-control" name="permitFrom" readonly value="{{ ($staffdetails->permit_from != '') ? $staffdetails->permit_from : old('permit_from') }}" placeholder="Permit From" id="permitFrom" maxlength="100" type="text">
									@else
	                                <input class="form-control" name="permitFrom" value="{{ ($staffdetails->permit_from != '') ? $staffdetails->permit_from : old('permit_from') }}" placeholder="Permit From" id="permitFrom" maxlength="100" type="date">
									@endif
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="permitTo" class="col-sm-3 control-label">Permit To</label>
	                            <div class="col-sm-7">
									@if($staffdetails->permit_to != '')
	                                <input class="form-control" placeholder="Permit To" readonly value="{{ ($staffdetails->permit_to != '') ? $staffdetails->permit_to : old('permit_to') }}" name="permitTo" id="permitTo" maxlength="100" type="text">
									@else
	                                <input class="form-control" placeholder="Permit To" value="{{ ($staffdetails->permit_to != '') ? $staffdetails->permit_to : old('permit_to') }}" name="permitTo" id="permitTo" maxlength="100" type="date">
									@endif
	                            </div>
	                        </div>
	                    </div>

	                    <div class="panel-heading">Level</div>
	                    <div class="panel-body">

	                        <div class="form-group">
	                            <label for="level_star" class="col-sm-3 control-label">Star</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="level_star" value="{{ ($staffdetails->level_star != '') ? $staffdetails->level_star : old('level_star') }}" id="level_star" maxlength="20" placeholder="Star" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="level_Chief" class="col-sm-3 control-label">Chief</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="level_Chief" value="{{ ($staffdetails->level_chief != '') ? $staffdetails->level_chief : old('level_chief') }}" id="level_Chief" maxlength="20" placeholder="Chief" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="level_point" class="col-sm-3 control-label">Points</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="level_point" value="{{ ($staffdetails->level_point != '') ? $staffdetails->level_point : old('level_point') }}" id="level_point" maxlength="20" placeholder="Points" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="level" class="col-sm-3 control-label">level</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="level" value="{{ ($staffdetails->level != '') ? $staffdetails->level : old('level') }}" id="level" maxlength="20" placeholder="Level" type="text">
	                            </div>
	                        </div>

	                        </div>
	                        </div>
	                      </div>
	                      <!-- /.tab-pane -->
	                      <div class="tab-pane" id="tab_3" style="border-color: 2px solid #337ab7;"><!--  tab 1  -->
	                        <br/>
	                        <div class="panel panel-primary">
	                            <div class="panel-heading">Salary
	                            </div><br/>
	                        <div class="form-group">
	                            <label for="salary" class="col-sm-3 control-label">Salary</label>
	                            <div class="col-sm-6">
	                                <input class="form-control" name="salary" value="{{ ($staffdetails->salary != '') ? $staffdetails->salary : old('salary') }}" id="salary" maxlength="100" placeholder="Salary" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="salary_nextdate" class="col-sm-3 control-label">Next Review Date</label>
	                            <div class="col-sm-7">
									@if($staffdetails->salary_next_date != '')
	                                <input class="form-control" name="salary_nextdate" value="{{ ($staffdetails->salary_next_date != '') ? $staffdetails->salary_next_date : old('salary_next_date') }}" id="salary_nextdate" maxlength="20" placeholder="Next Review Date" type="text">
									@else
	                                <input class="form-control" name="salary_nextdate" value="{{ ($staffdetails->salary_next_date != '') ? $staffdetails->salary_next_date : old('salary_next_date') }}" id="salary_nextdate" maxlength="20" placeholder="Next Review Date" type="date">
									@endif
	                            </div>
	                        </div>

	                        <div class="panel-heading">Payment Details
	                        </div>
	                        <div class="panel-body">
	                        <div class="form-group">
	                            <label for="payment_bank" class="col-sm-3 control-label">Bank</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="payment_bank" value="{{ ($staffdetails->payment_bank != '') ? $staffdetails->payment_bank : old('payment_bank') }}" id="payment_bank" maxlength="20" placeholder="Bank" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="payment" class="col-sm-3 control-label">Payment</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="payment" value="{{ ($staffdetails->payment != '') ? $staffdetails->payment : old('payment') }}" id="payment" maxlength="20" placeholder="Payment" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="bank_accNo" class="col-sm-3 control-label">Bank Account No</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="bank_accNo" value="{{ ($staffdetails->bank_acc_no != '') ? $staffdetails->bank_acc_no : old('bank_acc_no') }}" id="bank_accNo" maxlength="20" placeholder="Bank Account No" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="payment_method" class="col-sm-3 control-label">Method</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="payment_method" value="{{ ($staffdetails->payment_method != '') ? $staffdetails->payment_method : old('payment_method') }}" id="payment_method" maxlength="20" placeholder="Method" type="text">
	                            </div>
	                        </div>

	                    </div>
	                    <div class="panel-heading">Statutory Details
	                    </div>
	                    <div class="panel-body">

	                        <div class="form-group">
	                            <label for="employerEpfRate" class="col-sm-3 control-label">Employer Epf Rate</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="employerEpfRate" value="{{ ($staffdetails->employer_epf_rate != '') ? $staffdetails->employer_epf_rate : old('employer_epf_rate') }}" id="employerEpfRate" maxlength="20" placeholder="Employer Epf Rate" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="epfMembershipNo" class="col-sm-3 control-label">EPF Membership No</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="epfMembershipNo" value="{{ ($staffdetails->epf_membership_no != '') ? $staffdetails->epf_membership_no : old('epf_membership_no') }}" id="epfMembershipNo" maxlength="20" placeholder="EPF Membership No" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="taxReffNo" class="col-sm-3 control-label">Tax Referrance No</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="taxReffNo" value="{{ ($staffdetails->tax_reff_no != '') ? $staffdetails->tax_reff_no : old('tax_reff_no') }}" id="taxReffNo" maxlength="100" type="text" placeholder="Tax Refferance Number">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="childRelief" class="col-sm-3 control-label">Child Relief</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="childRelief" value="{{ ($staffdetails->child_relief != '') ? $staffdetails->child_relief : old('child_relief') }}" id="childRelief" maxlength="100" type="text" placeholder="Child Relief">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="EisContribution" class="col-sm-3 control-label">EIS Contribution</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" value="{{ ($staffdetails->eis_contribution != '') ? $staffdetails->eis_contribution : old('eis_contribution') }}" name="EisContribution" id="EisContribution" maxlength="100" type="text" placeholder="EIS Contribution">
	                            </div>
	                        </div>


	                        <div class="form-group">
	                            <label for="socsoCategory" class="col-sm-3 control-label">Socso Category</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" value="{{ ($staffdetails->socso_category != '') ? $staffdetails->socso_category : old('socso_category') }}" name="socsoCategory" id="socsoCategory" maxlength="100" type="text" placeholder="Socso Category" >
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="taxStatus" class="col-sm-3 control-label">Tax Status</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" value="{{ ($staffdetails->tax_status != '') ? $staffdetails->tax_status : old('tax_status') }}" name="taxStatus" id="taxStatus" maxlength="100" type="text" placeholder="Child Relief" >
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="muslimZakatFund" class="col-sm-3 control-label">Muslim Zakat Fund</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" value="{{ ($staffdetails->muslim_zakat != '') ? $staffdetails->muslim_zakat : old('muslim_zakat') }}" name="muslimZakatFund" id="muslimZakatFund" maxlength="100" type="text" placeholder="Muslim Zakat Fund" >
	                            </div>
	                        </div>
	                        </div>
	                        </div>
	                      </div>
	                      <div class="tab-pane" id="tab_4" style="border-color: 2px solid #337ab7;"><!--  tab 1  -->
	                        <br/>
	                        <div class="panel panel-primary">
	                            <div class="panel-heading">Knowledge Details
	                            </div><br/>
	                        <div class="form-group">
	                            <label for="skill" class="col-sm-3 control-label">Skill</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" value="{{ ($staffdetails->skill != '') ? $staffdetails->skill : old('skill') }}" name="skill" id="skill" maxlength="100" type="text" placeholder="SKill" >
	                            </div>
	                        </div>
	                      </div>
	                      <!-- /.tab-pane -->
	                    </div>
	                    <!-- /.tab-content -->
	                  </div><!-- /.card-body -->
	                </div>
				    <div class="box-footer divfooter">
				        <a href="{{ URL::to('User') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
				            <button type="submit" class="btn btn-sm btn-success pull-right submitform"><i class="fa fa-check-circle"></i> {{ __('Admin::base.submit') }}</button>
				    </div>
			{{ Form::close() }}
	</div>


@stop

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

@stop