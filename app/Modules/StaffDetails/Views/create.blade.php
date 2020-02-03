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
		{!! Form::open(['action'=>'\App\Modules\StaffDetails\Controllers\StaffDetailsController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1', 'files'=>true]) !!}
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
	                                        <input class="form-control" name="staff_ID" id="staff_ID" maxlength="100" type="text" placeholder="Staff ID">
	                                        <span id="message_check"></span>
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <label for="first_name" class="col-sm-3 control-label">Firtst name</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" name="name" id="name" maxlength="100" placeholder="First name" type="text">
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <label for="last_name" class="col-sm-3 control-label">Email</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" name="staff_email" id="staff_email" maxlength="100" placeholder="your_email@email.com" type="email">
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <label for="username" class="col-sm-3 control-label">Username</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" name="username" id="username" maxlength="100" placeholder="Username" type="text">
	                                    </div>
	                                </div>

			                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
			                            <label for="password" class="col-sm-3 control-label">Password</label>
                                        <div class="col-sm-7">
			                                <input id="password" type="password" class="form-control" name="password" required>

			                                @if ($errors->has('password'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('password') }}</strong>
			                                    </span>
			                                @endif
		                                </div>
			                        </div>

	                                <div class="form-group">
	                                    <label for="last_name" class="col-sm-3 control-label">Mobile No</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" name="mobile_no" id="mobile_no" maxlength="100" placeholder="0123456789" type="text">
	                                    </div>
	                                </div>
	                                
	                                <div class="form-group">
	                                    <label for="staff_gender" class="col-sm-3 control-label">Gender</label>
	                                    <div class="col-sm-7">
	                                      <input type="radio" name="staff_gender" value="male"> Male<br>
	                                      <input type="radio" name="staff_gender" value="female"> Female<br>
	                                      <input type="radio" name="staff_gender" value="unknown"> Unknown
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <label for="birth_date" class="col-sm-3 control-label">Birth Date</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" name="birth_date" id="birth_date" maxlength="100" placeholder="Birth Date" type="date">
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <label for="profile_pic" class="col-sm-3 control-label">Profile Pic</label>
	                                    <div class="col-sm-7">
	                                        <input class="form-control" name="profile_pic" id="profile_pic" maxlength="100" type="file" placeholder="{{ __('Admin::user.username') }}">
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="panel-heading">Nationality
	                            </div>
	                            <br/>
	                            <div class="form-group">
	                                <label for="text" class="col-sm-3 control-label">Nationality</label>
	                                <div class="col-sm-7">
	                                    <input id="text" type="text" class="form-control" name="Nationality" placeholder="Nationality">
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label for="passport_no" class="col-sm-3 control-label">Passport No</label>
	                                <div class="col-sm-7">
	                                    <input id="passport_no" type="text" class="form-control" name="passport_no" placeholder="Passport No">
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label for="nric" class="col-sm-3 control-label">NRIC</label>
	                                <div class="col-sm-7">
	                                    <input id="nric" type="text" class="form-control" name="nric" placeholder="NRIC">
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
	                                    <input id="etnicity" type="etnicity" class="form-control" name="etnicity" placeholder="Etnicity">
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label for="religion" class="col-sm-3 control-label">Religion</label>
	                                <div class="col-sm-7">
	                                    <input id="religion" type="religion" class="form-control" name="religion" placeholder="Religion">
	                                </div>
	                            </div>
	                        </div>
	                      </div>
	                      <!-- /.tab-pane -->
	                      <div class="tab-pane" id="tab_2" style="border-color: 2px solid #337ab7;"><!--  tab 1  -->
	                        <br/>
	                        <div class="panel panel-primary">
	                            <div class="panel-heading">Employemnt Info
	                            </div><br/>
	                        <div class="form-group">
	                            <label for="date_joined" class="col-sm-3 control-label">Date Joined</label>
	                            <div class="col-sm-6">
	                                <input class="form-control" name="date_joined" id="date_joined" maxlength="100" placeholder="Date Joined" type="date">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="endofprobation" class="col-sm-3 control-label">End Of Probation</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="endofprobation" id="endofprobation" maxlength="20" placeholder="End OF Probation" type="date">
	                            </div>
	                        </div>
	                        <div class="panel-heading">Job Status
	                        </div>
	                        <div class="panel-body">
	                        <div class="form-group">
	                            <label for="jobposition" class="col-sm-3 control-label">Job Position</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="jobposition" id="jobposition" maxlength="20" placeholder="Job Position" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="line_supervision" class="col-sm-3 control-label">Line Supervission</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="line_supervision" id="line_supervision" maxlength="20" placeholder="Line Supervission" type="text">
	                            </div>
	                        </div>
	                    </div>

	                    <div class="panel-heading">Employment Status
	                    </div>
	                    <div class="panel-body">

	                        <div class="form-group">
	                            <label for="jobtype" class="col-sm-3 control-label">Job Type</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="jobtype" id="jobtype" maxlength="20" placeholder="Job Type" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="jobstatus" class="col-sm-3 control-label">Job Status</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="jobstatus" id="jobstatus" maxlength="100" type="text" placeholder="Job Status">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="WorkDays" class="col-sm-3 control-label">WorkDays</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="WorkDays" id="WorkDays" maxlength="100" type="text" placeholder="WorkDays">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="salary_effective_date" class="col-sm-3 control-label">Effective Date</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="salary_effective_date" id="salary_effective_date" maxlength="100" type="date" placeholder="EffectiveDate">
	                            </div>
	                        </div>
	                    </div>

	                    <div class="panel-heading">Permit</div>
	                    <div class="panel-body">

	                        <div class="form-group">
	                            <label for="permit" class="col-sm-3 control-label">Permit</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="permit" id="permit" placeholder="Permit" maxlength="100" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="permitEffectiveDate" class="col-sm-3 control-label">Effective Date</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="permitEffectiveDate" id="permitEffectiveDate" maxlength="100" placeholder="Effective Date" type="date">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="permitFrom" class="col-sm-3 control-label">Permit From</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="permitFrom" placeholder="Permit From" id="permitFrom" maxlength="100" type="date">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="permitTo" class="col-sm-3 control-label">Permit To</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" placeholder="Permit To" name="permitTo" id="permitTo" maxlength="100" type="date">
	                            </div>
	                        </div>
	                    </div>

	                    <div class="panel-heading">Level</div>
	                    <div class="panel-body">

	                        <div class="form-group">
	                            <label for="level_star" class="col-sm-3 control-label">Star</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="level_star" id="level_star" maxlength="20" placeholder="Star" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="level_Chief" class="col-sm-3 control-label">Chief</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="level_Chief" id="level_Chief" maxlength="20" placeholder="Chief" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="level_point" class="col-sm-3 control-label">Points</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="level_point" id="level_point" maxlength="20" placeholder="Points" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="level" class="col-sm-3 control-label">level</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="level" id="level" maxlength="20" placeholder="Level" type="text">
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
	                                <input class="form-control" name="salary" id="salary" maxlength="100" placeholder="Salary" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="salary_nextdate" class="col-sm-3 control-label">Next Review Date</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="salary_nextdate" id="salary_nextdate" maxlength="20" placeholder="Next Review Date" type="date">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="salary_effective_date" class="col-sm-3 control-label">Effective Date</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="salary_effective_date" id="salary_effective_date" maxlength="20" placeholder="Effective Date" type="date">
	                            </div>
	                        </div>

	                        <div class="panel-heading">Payment Details
	                        </div>
	                        <div class="panel-body">
	                        <div class="form-group">
	                            <label for="payment_bank" class="col-sm-3 control-label">Bank</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="payment_bank" id="payment_bank" maxlength="20" placeholder="Bank" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="payment" class="col-sm-3 control-label">Payment</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="payment" id="payment" maxlength="20" placeholder="Line Supervission" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="bank_accNo" class="col-sm-3 control-label">Bank Account No</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="bank_accNo" id="bank_accNo" maxlength="20" placeholder="Bank Account No" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="payment_method" class="col-sm-3 control-label">Method</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="payment_method" id="payment_method" maxlength="20" placeholder="Bank Account No" type="text">
	                            </div>
	                        </div>

	                    </div>
	                    <div class="panel-heading">Statutory Details
	                    </div>
	                    <div class="panel-body">

	                        <div class="form-group">
	                            <label for="employerEpfRate" class="col-sm-3 control-label">Employer Epf Rate</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="employerEpfRate" id="employerEpfRate" maxlength="20" placeholder="Employer Epf Rate" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="epfMembershipNo" class="col-sm-3 control-label">EPF Membership No</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="epfMembershipNo" id="epfMembershipNo" maxlength="20" placeholder="EPF Membership No" type="text">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="taxReffNo" class="col-sm-3 control-label">Tax Referrance No</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="taxReffNo" id="taxReffNo" maxlength="100" type="text" placeholder="Tax Refferance Number">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="childRelief" class="col-sm-3 control-label">Child Relief</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="childRelief" id="childRelief" maxlength="100" type="text" placeholder="Child Relief">
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="EisContribution" class="col-sm-3 control-label">EIS Contribution</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="EisContribution" id="EisContribution" maxlength="100" type="text" placeholder="EIS Contribution">
	                            </div>
	                        </div>


	                        <div class="form-group">
	                            <label for="socsoCategory" class="col-sm-3 control-label">Socso Category</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="socsoCategory" id="socsoCategory" maxlength="100" type="text" placeholder="Socso Category" >
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="taxStatus" class="col-sm-3 control-label">Tax Status</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="taxStatus" id="taxStatus" maxlength="100" type="text" placeholder="Child Relief" >
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label for="muslimZakatFund" class="col-sm-3 control-label">Muslim Zakat Fund</label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="muslimZakatFund" id="muslimZakatFund" maxlength="100" type="text" placeholder="Muslim Zakat Fund" >
	                            </div>
	                        </div>
	                        </div>
	                        </div>
	                      </div>
	                      <div class="tab-pane" id="tab_4" style="border-color: 2px solid #337ab7;"><!--  tab 1  -->
                            <input class="form-control" name="status_approved" id="status_approved" maxlength="100" type="hidden" value="1" >
                            <input class="form-control" name="status" id="status" maxlength="100" type="hidden" value="1" >
	                        <br/>
	                        <div class="panel panel-primary">
	                            <div class="panel-heading">Knowledge Details
	                            </div><br/>
	                        <div class="form-group">
	                            <label for="skill" class="col-sm-3 control-label"></label>
	                            <div class="col-sm-7">
	                                <input class="form-control" name="skill" id="skill" maxlength="100" type="text" placeholder="Muslim Zakat Fund" >
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
		  				<button type="submit" class="btn btn-sm btn-success pull-right">Submit</button>
				    </div>
			{{ Form::close() }}
	</div>


@stop

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

	$(document).ready(function() {

		 $('#staff_ID').on('focusout', function(e){
            
        	var code = $(this).val();
            checkStaffId(code);

        });

        function checkStaffId(code){

            $.ajax({
                url: '{{ URL::to("staffdetails/checkStaffId") }}/'+code,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                    if(data.message!=null){

	                    console.log(data);
	                    var messages = data.message;

                        $('#message_check').append('<span>'+messages+'</span>');
                    }else{
                        swal('Please Try Again','ID not Available','error');
                    }
                }
            });
        }
   	});

	$(document).ready(function() {

		 $('#staff_email').on('focusout', function(e){
            
        	var code = $(this).val();
            checkStaffEmail(code);

        });

        function checkStaffEmail(code){

            $.ajax({
                url: '{{ URL::to("staffdetails/checkStaffEmail") }}/'+code,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                    if(data.message!=null){

	                    console.log(data);
	                    var messages = data.message;

                        $('#message_check').append('<span>'+messages+'</span>');
                    }else{
                        swal('Please Try Again','Email not Available','error');
                    }

                }
            });

        }

   	});


</script>
@stop