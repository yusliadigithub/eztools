@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

<div class="box box-success">
    <!-- <div class="box-header with-border">
        <h3 class="box-title">{{ __('Merchant::merchant.detail') }}</h3>
    </div> -->
    @if($merchant->merchant_id != '')
    {!! Form::open(['action'=>['\App\Modules\Merchant\Controllers\MerchantController@update',$merchant->merchant_id], 'method'=>'put', 'class'=>'form-horizontal', 'id'=>'newdataform','files'=>true]) !!}
    @else
    {!! Form::open(['action'=>'\App\Modules\Merchant\Controllers\MerchantController@store', 'method'=>'post', 'class'=>'form-horizontal', 'id'=>'newdataform','files'=>true]) !!}
    @endif
    <input name="merchant_id" type="hidden" value="{{ $merchant->merchant_id }}">
        <div class="box-body">
            <div class="card">
              <div class="card-header d-flex p-0" style="border-bottom: 5px solid #337ab7">
                <ul class="nav nav-pills ml-auto p-2">
                  <li class="nav-item active"><a class="nav-link active" href="#tab_1" data-toggle="tab">{{ __('Merchant::merchant.personal_info') }}</a></li>
                  <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">{{ __('Merchant::merchant.job') }}</a></li>
                  <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">{{ __('Merchant::merchant.salary_details') }}</a></li>
                  <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">{{ __('Merchant::merchant.knowledge_details') }}</a></li>
                </ul>
              </div><!-- /.card-header -->
              <br/>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1" style="border-color: 2px solid #337ab7;"> <!--  tab 1  -->
                        <br/>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                            {{ __('Merchant::merchant.personal_info') }}
                            </div>
                            <br/>
                            <div class="panel-body">
                                <div class="form-group">
                                <label for="staff_ID" class="col-sm-3 control-label">{{ __('Merchant::merchant.staff_ID') }}</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" name="staff_ID" id="staff_ID" maxlength="100" type="text" value="{{ ($merchant->staff_ID != '') ? $merchant->staff_ID : old('staff_ID') }}" placeholder="{{ __('Merchant::merchant.staff_ID') }}" required>
                                        <small><a href="#">Available?</a></small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="first_name" class="col-sm-3 control-label">{{ __('Merchant::merchant.first_name') }}</label>
                                    <div class="col-sm-7">
                                        <input class="form-control datareadonly" name="first_name" id="first_name" maxlength="100" placeholder="First name" type="email" value="{{ ($merchant->first_name != '') ? $merchant->first_name : old('first_name') }}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="last_name" class="col-sm-3 control-label">{{ __('Merchant::merchant.last_name') }}</label>
                                    <div class="col-sm-7">
                                        <input class="form-control datareadonly" name="last_name" id="last_name" maxlength="100" placeholder="First name" type="email" value="{{ ($merchant->last_name != '') ? $merchant->last_name : old('last_name') }}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="staff_gender" class="col-sm-3 control-label">{{ __('Merchant::merchant.gender') }}</label>
                                    <div class="col-sm-7">

                                      <input type="radio" name="staff_gender" value="male"> Male<br>
                                      <input type="radio" name="staff_gender" value="female"> Female<br>
                                      <input type="radio" name="staff_gender" value="unknown"> Unknown

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="birth_date" class="col-sm-3 control-label">{{ __('Merchant::merchant.birth_date') }}</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" name="birth_date" id="birth_date" maxlength="100" placeholder="myemail@gmail.com" type="date" value="{{ ($merchant->birth_date != '') ? $merchant->birth_date : old('birth_date') }}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="profile_pic" class="col-sm-3 control-label">{{ __('Merchant::merchant.profile_pic') }}</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" name="profile_pic" id="profile_pic" maxlength="100" type="file" value="{{ ($merchant->profile_pic != '') ? $merchant->profile_pic : old('profile_pic') }}" placeholder="{{ __('Admin::user.username') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-heading">
                            {{ __('Merchant::merchant.nationality') }}
                            </div>
                            <br/>
                            <div class="form-group">
                                <label for="text" class="col-sm-3 control-label">{{ __('Merchant::merchant.nationality') }}</label>
                                <div class="col-sm-7">
                                    <input id="text" type="text" class="form-control" name="Nationality" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="passport_no" class="col-sm-3 control-label">{{ __('Merchant::merchant.passport_no') }}</label>
                                <div class="col-sm-7">
                                    <input id="passport_no" type="text" class="form-control" name="passport_no" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nric" class="col-sm-3 control-label">{{ __('Merchant::merchant.nric') }}</label>
                                <div class="col-sm-7">
                                    <input id="nric" type="text" class="form-control" name="nric" required>
                                    <small><a href="#">Available?</a></small>
                                </div>
                            </div>

                            <br/>
                            <div class="panel-heading">
                            {{ __('Merchant::merchant.additional_details') }}
                            </div>
                            <br/>
                            <div class="form-group">
                                <label for="etnicity" class="col-sm-3 control-label">{{ __('Merchant::merchant.etnicity') }}</label>
                                <div class="col-sm-7">
                                    <input id="etnicity" type="etnicity" class="form-control" name="etnicity" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="religion" class="col-sm-3 control-label">{{ __('Merchant::merchant.religion') }}</label>
                                <div class="col-sm-7">
                                    <input id="religion" type="religion" class="form-control" name="religion" required>
                                </div>
                            </div>
                            </div>
                        </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab_2" style="border-color: 2px solid #337ab7;"><!--  tab 1  -->
                        <br/>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                            {{ __('Merchant::merchant.employment_info') }}
                            </div><br/>
                        <div class="form-group">
                            <label for="date_joined" class="col-sm-3 control-label">{{ __('Merchant::merchant.date_joined') }}</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="date_joined" id="date_joined" maxlength="100" placeholder="{{ __('Merchant::merchant.date_joined') }}" type="text" value="{{ ($merchant->date_joined != '') ? $merchant->date_joined : old('date_joined') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="endofprobation" class="col-sm-3 control-label">{{ __('Merchant::merchant.endofprobation') }}</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="endofprobation" id="endofprobation" maxlength="20" placeholder="{{ __('Merchant::merchant.endofprobation') }}" type="text" value="{{ ($merchant->endofprobation != '') ? $merchant->endofprobation : old('endofprobation') }}" required>
                            </div>
                        </div>
                        <div class="panel-heading">
                            {{ __('Merchant::merchant.jobstatus') }}
                        </div>
                        <div class="panel-body">
                        <div class="form-group">
                            <label for="jobposition" class="col-sm-3 control-label">{{ __('Merchant::merchant.jobposition') }}</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="jobposition" id="jobposition" maxlength="20" placeholder="Job Position" type="text" value="{{ ($merchant->jobposition != '') ? $merchant->jobposition : old('jobposition') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="line_supervision" class="col-sm-3 control-label">Line Supervission</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="line_supervision" id="line_supervision" maxlength="20" placeholder="Line Supervission" type="text" value="{{ ($merchant->line_supervision != '') ? $merchant->line_supervision : old('line_supervision') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="panel-heading">
                        {{ __('Merchant::merchant.employementstatus') }}
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="jobtype" class="col-sm-3 control-label">{{ __('Merchant::merchant.jobtype') }}</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="jobtype" id="jobtype" maxlength="20" placeholder="Job Type" type="text" value="{{ ($merchant->jobtype != '') ? $merchant->jobtype : old('jobtype') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jobstatus" class="col-sm-3 control-label">{{ __('Merchant::merchant.jobstatus') }}</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="jobstatus" id="jobstatus" maxlength="100" type="text" placeholder="Job Status" value="{{ ($merchant->jobstatus != '') ? $merchant->jobstatus : old('jobstatus') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="WorkDays" class="col-sm-3 control-label">WorkDays</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="WorkDays" id="WorkDays" maxlength="100" type="text" placeholder="WorkDays" value="{{ ($merchant->WorkDays != '') ? $merchant->WorkDays : old('WorkDays') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="salary_effective_date" class="col-sm-3 control-label">Effective Date</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="salary_effective_date" id="salary_effective_date" maxlength="100" type="text" placeholder="EffectiveDate" value="{{ ($merchant->salary_effective_date != '') ? $merchant->salary_effective_date : old('salary_effective_date') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="panel-heading">Permit</div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="permit" class="col-sm-3 control-label">Permit</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="permit" id="permit" placeholder="Permit" maxlength="100" type="text" value="{{ ($merchant->permit != '') ? $merchant->permit : old('permit') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="permitEffectiveDate" class="col-sm-3 control-label">Effective Date</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="permitEffectiveDate" id="permitEffectiveDate" maxlength="100" placeholder="Effective Date" type="text" value="{{ ($merchant->permitEffectiveDate != '') ? $merchant->permitEffectiveDate : old('permitEffectiveDate') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="permitFrom" class="col-sm-3 control-label">Permit From</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="permitFrom" placeholder="Permit From" id="permitFrom" maxlength="100" type="text" value="{{ ($merchant->permitFrom != '') ? $merchant->permitFrom : old('permitFrom') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="permitTo" class="col-sm-3 control-label">Permit To</label>
                            <div class="col-sm-7">
                                <input class="form-control" placeholder="Permit To" name="permitTo" id="permitTo" maxlength="100" type="text" value="{{ ($merchant->permitTo != '') ? $merchant->permitTo : old('permitTo') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="panel-heading">Level</div>
                    <div class="panel-body">

                        <!-- <div class="form-group">
                            <label for="merchant_postcode" class="col-sm-4 control-label">Permit</label>
                            <div class="col-sm-4">
                                <input class="form-control" name="merchant_postcode" id="merchant_postcode" maxlength="5" type="text" value="{{ ($merchant->merchant_postcode != '') ? $merchant->merchant_postcode : old('merchant_postcode') }}" placeholder="eg: 43000" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="district_id" class="col-sm-4 control-label">{{ __('Merchant::merchant.district') }}</label>
                            <div class="col-sm-6">
                                <select name="district_id" id="district_id" class="form-control" required>
                                        <option value="" {{ (old('district_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                                    </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="state_id" class="col-sm-4 control-label">{{ __('Merchant::merchant.state') }}</label>
                            <div class="col-sm-6">
                                <select name="state_id" id="state_id" class="form-control" required>
                                        <option value="" {{ (old('state_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                                    </select>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label for="level_star" class="col-sm-3 control-label">Star</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="level_star" id="level_star" maxlength="20" placeholder="level_Star" type="text" value="{{ ($merchant->level_star != '') ? $merchant->level_star : old('level_star') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="level_Chief" class="col-sm-3 control-label">Chief</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="level_Chief" id="level_Chief" maxlength="20" placeholder="level_Chief" type="text" value="{{ ($merchant->level_Chief != '') ? $merchant->level_Chief : old('level_Chief') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="level_point" class="col-sm-3 control-label">Points</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="level_point" id="level_point" maxlength="20" placeholder="level_Points" type="text" value="{{ ($merchant->level_point != '') ? $merchant->level_point : old('level_point') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="level" class="col-sm-3 control-label">level</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="level" id="level" maxlength="20" placeholder="Level" type="text" value="{{ ($merchant->level != '') ? $merchant->level : old('level') }}">
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
                                <input class="form-control" name="salary" id="salary" maxlength="100" placeholder="Salary" type="text" value="{{ ($merchant->salary != '') ? $merchant->salary : old('salary') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="salary_nextdate" class="col-sm-3 control-label">Next Review Date</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="salary_nextdate" id="salary_nextdate" maxlength="20" placeholder="Next Review Date" type="text" value="{{ ($merchant->salary_nextdate != '') ? $merchant->salary_nextdate : old('salary_nextdate') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="salary_effective_date" class="col-sm-3 control-label">Effective Date</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="salary_effective_date" id="salary_effective_date" maxlength="20" placeholder="Effective Date" type="text" value="{{ ($merchant->salary_effective_date != '') ? $merchant->salary_effective_date : old('salary_effective_date') }}" required>
                            </div>
                        </div>

                        <div class="panel-heading">Payment Details
                        </div>
                        <div class="panel-body">
                        <div class="form-group">
                            <label for="payment_bank" class="col-sm-3 control-label">Bank</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="payment_bank" id="payment_bank" maxlength="20" placeholder="Bank" type="text" value="{{ ($merchant->payment_bank != '') ? $merchant->payment_bank : old('payment_bank') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="payment" class="col-sm-3 control-label">Payment</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="payment" id="payment" maxlength="20" placeholder="Line Supervission" type="text" value="{{ ($merchant->payment != '') ? $merchant->payment : old('payment') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="bank_accNo" class="col-sm-3 control-label">Bank Account No</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="bank_accNo" id="bank_accNo" maxlength="20" placeholder="Bank Account No" type="text" value="{{ ($merchant->bank_accNo != '') ? $merchant->bank_accNo : old('bank_accNo') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="payment_method" class="col-sm-3 control-label">Method</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="payment_method" id="payment_method" maxlength="20" placeholder="Bank Account No" type="text" value="{{ ($merchant->payment_method != '') ? $merchant->payment_method : old('payment_method') }}" required>
                            </div>
                        </div>

                    </div>
                    <div class="panel-heading">Statutory Details
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="employerEpfRate" class="col-sm-3 control-label">Employer Epf Rate</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="employerEpfRate" id="employerEpfRate" maxlength="20" placeholder="Employer Epf Rate" type="text" value="{{ ($merchant->employerEpfRate != '') ? $merchant->employerEpfRate : old('employerEpfRate') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="epfMembershipNo" class="col-sm-3 control-label">EPF Membership No</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="epfMembershipNo" id="epfMembershipNo" maxlength="20" placeholder="EPF Membership No" type="text" value="{{ ($merchant->epfMembershipNo != '') ? $merchant->epfMembershipNo : old('epfMembershipNo') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="taxReffNo" class="col-sm-3 control-label">Tax Referrance No</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="taxReffNo" id="taxReffNo" maxlength="100" type="text" placeholder="Tax Refferance Number" value="{{ ($merchant->taxReffNo != '') ? $merchant->taxReffNo : old('taxReffNo') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="childRelief" class="col-sm-3 control-label">Child Relief</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="childRelief" id="childRelief" maxlength="100" type="text" placeholder="Child Relief" value="{{ ($merchant->childRelief != '') ? $merchant->childRelief : old('childRelief') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="EisContribution" class="col-sm-3 control-label">EIS Contribution</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="EisContribution" id="EisContribution" maxlength="100" type="text" placeholder="EIS Contribution" value="{{ ($merchant->EisContribution != '') ? $merchant->EisContribution : old('EisContribution') }}" required>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="socsoCategory" class="col-sm-3 control-label">Socso Category</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="socsoCategory" id="socsoCategory" maxlength="100" type="text" placeholder="Socso Category" value="{{ ($merchant->socsoCategory != '') ? $merchant->socsoCategory : old('socsoCategory') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="taxStatus" class="col-sm-3 control-label">Tax Status</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="taxStatus" id="taxStatus" maxlength="100" type="text" placeholder="Child Relief" value="{{ ($merchant->taxStatus != '') ? $merchant->taxStatus : old('taxStatus') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="muslimZakatFund" class="col-sm-3 control-label">Muslim Zakat Fund</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="muslimZakatFund" id="muslimZakatFund" maxlength="100" type="text" placeholder="Muslim Zakat Fund" value="{{ ($merchant->muslimZakatFund != '') ? $merchant->muslimZakatFund : old('muslimZakatFund') }}">
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
                            <label for="skill" class="col-sm-3 control-label"></label>
                            <div class="col-sm-7">
                                <input name="skill" id="skill" type="checkbox" value="{{ ($merchant->skill != '') ? $merchant->skill : old('skill') }}">1. Machine
                                <input name="skill" id="skill" type="checkbox" value="{{ ($merchant->skill != '') ? $merchant->skill : old('skill') }}">2. Machine
                                <input name="skill" id="skill" type="checkbox" value="{{ ($merchant->skill != '') ? $merchant->skill : old('skill') }}">3. Machine
                                <input name="skill" id="skill" type="checkbox" value="{{ ($merchant->skill != '') ? $merchant->skill : old('skill') }}">4. Machine
                                <input name="skill" id="skill" type="checkbox" value="{{ ($merchant->skill != '') ? $merchant->skill : old('skill') }}">5. Machine<input name="skill" id="skill" type="checkbox" value="{{ ($merchant->skill != '') ? $merchant->skill : old('skill') }}">6. Machine
                                <input name="skill" id="skill" type="checkbox" value="{{ ($merchant->skill != '') ? $merchant->skill : old('skill') }}">7. Machine
                                <input name="skill" id="skill" type="checkbox" value="{{ ($merchant->skill != '') ? $merchant->skill : old('skill') }}">8. Machine
                            </div>
                        </div>
                      </div>
                      <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                  </div><!-- /.card-body -->
                </div>
    <div class="box-footer divfooter">
        <a href="{{ URL::to('merchant') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
        @if(Auth::user()->can('merchant.store'))
            @if($merchant->merchant_id!='')
            <button type="button" class="btn btn-sm btn-primary pull-right submitform"><i class="fa fa-save"></i> {{ __('Admin::base.update') }}</button>
            @else
            <button type="button" class="btn btn-sm btn-success pull-right submitform"><i class="fa fa-check-circle"></i> {{ __('Admin::base.submit') }}</button>
            @endif
        @endif
    </div>
    {!! Form::close() !!}
</div>

@stop

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">
<script type="text/javascript">

    $(document).ready(function() {

        @if($merchant->merchant_id != '') 
            @if($merchant->user->status_approve=='1')
                $('.datareadonly').attr('readonly','readonly');
            @endif
        @endif

        @if(Auth::user()->hasrole('merchant'))
            $('.hidepackage').hide();
        @endif

        @if($disabled==1)
            $('.divfooter').hide();
        @endif

        $(document).on('click', '.addmobile', function() {
            $('#mobilediv tr:last').after('<tr><td><input name="merchant_mobile_no[]" class="form-control" maxlength="16" type="text" placeholder=" 0123456789" required></td><td width="30%"><a class="btn btn-xs btn-danger pull-right removetr"><i class="fa fa-times-circle"></i></a></td></tr>');
        });

        $(document).on('click', '.addemail', function() {
            $('#emaildiv tr:last').after('<tr><td><input name="merchant_email_address[]" class="form-control" maxlength="100" type="text" placeholder=" myemail@gmail.com" required></td><td width="15%"><a class="btn btn-xs btn-danger pull-right removetr"><i class="fa fa-times-circle"></i></a></td></tr>');
        });

        $(document).on('click', '.removetr', function() {
            $(this).parent().parent().remove();
        });

        @if($merchant->merchant_type_id != '')
            $('#merchant_type_id').val('{{ $merchant->merchant_type_id }}');
        @else
            $('#merchant_type_id').val('{{ old("merchant_type_id") }}');
        @endif

        if($('#merchant_email').val()!=''){
            @if($merchant->merchant_email != '')
                $('#thisemail').val('{{ $merchant->merchant_email }}');
            @else
                $('#thisemail').val('{{ old("merchant_email") }}');
            @endif
        }

        $('#merchant_email').on('keyup', function(e){
            
            $('#thisemail').val('');
            $('#thisemail').val($(this).val());

        });

        $('.packagelistdiv').hide();

        //if($('#merchant_package_id').val()!=''){
            @if($merchant->merchant_package_id != '')
                getPackageList('{{ $merchant->merchant_package_id }}');
                $('#merchant_package_id').val('{{ $merchant->merchant_package_id }}');
            @else
                getPackageList('{{ old("merchant_package_id") }}');
            @endif
        //}

        $('#merchant_package_id').on('change', function(e){
            
            var id = $(this).val();
            getPackageList(id);

        });

        function getPackageList(id){
            
            $('.viewpackagelist').empty();
            //var id = $(this).val();

            if(id!=''){
                $.ajax({
                    url: '{{ URL::to("merchant/package/getSubPackageInfo") }}/'+id,
                    type: 'get',
                    dataType: 'json',
                    success:function(data) {

                        if(data!=null){
                            var list = '<table class="table"><tbody>';

                            list += data.html;

                            list += '<tr><td><i class="fa fa-dot-circle"></i>  {{ __("Merchant::package.maxproduct") }}: ';
                            if(data.package.merchant_package_max_product==0){
                                list += '{{ __("Admin::base.unlimited") }}';
                            }else{
                                list += data.package.merchant_package_max_product;
                            }
                            list += '</td></tr>';

                            list += '</tbody></table>';

                            $('.viewpackagelist').append(list);
                            $('.packagelistdiv').show();
                        }

                    }
                });
            }

        }
       
        //if($('#template_id').val()!=''){
            @if($merchant->template_id != '')
                $('#template_id').val('{{ $merchant->template_id }}');
                getTemplateInfo('{{ $merchant->template_id }}');
            @else
                //$('#template_id').val('{{ old("template_id") }}');
                getTemplateInfo('{{ old("template_id") }}');
            @endif
        //}

        $('#template_id').on('change', function(e){
            
            var id = $(this).val();
            getTemplateInfo(id);

        });

        function getTemplateInfo(id){
            
            $('.viewtemplatediv').empty();
            //var id = $(this).val();

            if(id!=''){
                $.ajax({
                    url: '{{ URL::to("merchant/template/getInfo") }}/'+id,
                    type: 'get',
                    dataType: 'json',
                    success:function(data) {

                        if(data!=null){
                            $('.viewtemplatediv').append('<a data-toggle="tooltip" title="{{ __("Merchant::template.viewtemplate") }}" class="btn btn-xs btn-success pull-right" target="_blank" href="'+data.template_url+'"><i class="fa fa-search"></i> View</a>');
                        }

                    }
                });
            }

        }

        //if($('#merchant_postcode').val()!=''){
            @if($merchant->merchant_postcode != '')
                getstatedistrict('{{ $merchant->merchant_postcode }}');
            @else
                getstatedistrict('{{ old("merchant_postcode") }}');
            @endif
        //}

        $('#merchant_postcode').on('keyup', function(e){
            
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

        $('.submitform').on('click', function() {
        
          var url = $(this).attr('value');

          swal({
            title: '{{ __("Admin::base.confirmsubmission") }}',
            //text: "{{ __('Admin::base.inadjustable') }}",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: '{{ __("Admin::base.cancel") }}',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("Admin::base.yes") }}',
          
            preConfirm: function() {
                return new Promise(function(resolve) {

                    $("#newdataform").submit();

                });
            },

            }).then(function () {
                swal(
                  '{{ __("Admin::base.success") }}!',
                  '',
                  'success'
                )
            });

        });

        /*
        $('#state_id').on('change', function(e){
            
            var id = $(this).val();
            $('#district_id').empty();
            
            if(id!=''){

                $.ajax({
                    url: '{{ URL::to("admin/getDistrict") }}/'+id,
                    type: 'get',
                    dataType: 'json',
                    success:function(data) {

                        if(data!=''){
                            $('#district_id').append('<option value="">{{ __('Admin::base.please_select') }}</option>');
                            $.each(data, function(key, value) {
                                $('#district_id').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                        }else{
                            $('#district_id').append('<option value="">-- No Record Found --</option>');
                        }

                    }
                });

            }else{
                $('#district_id').append('<option value="">-- Please Select State --</option>');
            }

        });*/

    });

</script>
@stop
          