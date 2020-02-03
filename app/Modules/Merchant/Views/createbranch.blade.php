@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Merchant::branch.detail') }}</h3>
    </div>
    @if($branch->merchant_branch_id != '')
    {!! Form::open(['action'=>['\App\Modules\Merchant\Controllers\BranchController@update',$branch->merchant_branch_id], 'method'=>'put', 'class'=>'form-horizontal', 'id'=>'newdataform','files'=>true]) !!}
    @else
    {!! Form::open(['action'=>'\App\Modules\Merchant\Controllers\BranchController@store', 'method'=>'post', 'class'=>'form-horizontal', 'id'=>'newdataform','files'=>true]) !!}
    @endif
    <input name="merchant_branch_id" type="hidden" value="{{ $branch->merchant_branch_id }}">
        <div class="box-body">
            <div class="col-md-6">

                <div class="form-group">
                    <label for="merchant_id" class="col-sm-4 control-label">{{ __('Merchant::merchant.merchant') }}</label>
                    <div class="col-sm-6">
                        <input type="hidden" name="merchant_id" id="merchant_id" value="{{ $merchants->merchant_id }}" >
                        <input class="form-control" type="text" value="{{ $merchants->merchant_name }} ({{ $merchants->merchant_ssmno }})" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_person_incharge" class="col-sm-4 control-label">{{ __('Merchant::merchant.contactperson') }}</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_branch_person_incharge" id="merchant_branch_person_incharge" maxlength="100" type="text" value="{{ ($branch->merchant_branch_person_incharge != '') ? $branch->merchant_branch_person_incharge : old('merchant_branch_person_incharge') }}" placeholder="{{ __('Merchant::merchant.contactperson') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_email" class="col-sm-4 control-label">{{ __('Merchant::branch.email') }}</label>
                    <div class="col-sm-6">
                        <input class="form-control datareadonly" name="merchant_branch_email" id="merchant_branch_email" maxlength="100" placeholder="myemail@gmail.com" type="email" value="{{ ($branch->merchant_branch_email != '') ? $branch->merchant_branch_email : old('merchant_branch_email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_username" class="col-sm-4 control-label">{{ __('Admin::user.username') }}</label>
                    <div class="col-sm-6">
                        <input class="form-control datareadonly" name="merchant_branch_username" id="merchant_branch_username" maxlength="100" type="text" value="{{ ($branch->merchant_branch_username != '') ? $branch->merchant_branch_username : old('merchant_branch_username') }}" placeholder="{{ __('Admin::user.username') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">{{ __('Admin::user.password') }}</label>
                    <div class="col-sm-6">
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="col-sm-4 control-label">{{ __('Admin::user.confirmpassword') }}</label>
                    <div class="col-sm-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <hr>
                <!--div class="form-group">
                    <label for="merchant_branch_name" class="col-sm-4 control-label">{{ __('Merchant::branch.companyname') }}</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_branch_name" id="merchant_branch_name" maxlength="100" placeholder="{{ __('Merchant::branch.companyname') }}" type="text" value="{{ ($branch->merchant_branch_name != '') ? $branch->merchant_branch_name : old('merchant_branch_name') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_ssmno" class="col-sm-4 control-label">{{ __('Merchant::branch.ssmno') }}</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="merchant_branch_ssmno" id="merchant_branch_ssmno" maxlength="20" placeholder="{{ __('Merchant::branch.ssmno') }}" type="text" value="{{ ($branch->merchant_branch_ssmno != '') ? $branch->merchant_branch_ssmno : old('merchant_branch_ssmno') }}">
                    </div>
                </div-->

                <div class="form-group">
                    <label for="merchant_branch_address1" class="col-sm-4 control-label">{{ __('Merchant::branch.address') }}</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_branch_address1" id="merchant_branch_address1" maxlength="100" type="text" value="{{ ($branch->merchant_branch_address1 != '') ? $branch->merchant_branch_address1 : old('merchant_branch_address1') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_address2" class="col-sm-4 control-label">&nbsp;</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_branch_address2" id="merchant_branch_address2" maxlength="100" type="text" value="{{ ($branch->merchant_branch_address2 != '') ? $branch->merchant_branch_address2 : old('merchant_branch_address2') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_address3" class="col-sm-4 control-label">&nbsp;</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_branch_address3" id="merchant_branch_address3" maxlength="100" type="text" value="{{ ($branch->merchant_branch_address1 != '') ? $branch->merchant_branch_address1 : old('merchant_branch_address1') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_postcode" class="col-sm-4 control-label">{{ __('Merchant::branch.postcode') }}</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="merchant_branch_postcode" id="merchant_branch_postcode" maxlength="5" type="text" value="{{ ($branch->merchant_branch_postcode != '') ? $branch->merchant_branch_postcode : old('merchant_branch_postcode') }}" placeholder="eg: 43000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="district_id" class="col-sm-4 control-label">{{ __('Merchant::branch.district') }}</label>
                    <div class="col-sm-6">
                        <select name="district_id" id="district_id" class="form-control" required>
                            <option value="" {{ (old('district_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="state_id" class="col-sm-4 control-label">{{ __('Merchant::branch.state') }}</label>
                    <div class="col-sm-6">
                        <select name="state_id" id="state_id" class="form-control" required>
                            <option value="" {{ (old('state_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                        </select>
                    </div>
                </div>
                
            </div>

            <div class="col-md-6">

                <div class="form-group">
                    <label for="merchant_branch_latitude" class="col-sm-4 control-label">{{ __('Merchant::branch.coordinate') }}</label>
                    <div class="col-sm-3">
                        <input class="form-control" name="merchant_branch_latitude" id="merchant_branch_latitude" maxlength="20" placeholder="Latitude" type="text" value="{{ ($branch->merchant_branch_latitude != '') ? $branch->merchant_branch_latitude : old('merchant_branch_latitude') }}">
                    </div>
                    <div class="col-sm-3">
                        <input class="form-control" name="merchant_branch_longitude" id="merchant_branch_longitude" maxlength="20" placeholder="Longitude" type="text" value="{{ ($branch->merchant_branch_longitude != '') ? $branch->merchant_branch_longitude : old('merchant_branch_longitude') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_mobileno" class="col-sm-4 control-label">{{ __('Merchant::branch.mobileno') }}</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="merchant_branch_mobileno" id="merchant_branch_mobileno" maxlength="16" type="text" placeholder="0330003000" value="{{ ($branch->merchant_branch_mobileno != '') ? $branch->merchant_branch_mobileno : old('merchant_branch_mobileno') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_officeno" class="col-sm-4 control-label">{{ __('Merchant::branch.officeno') }}</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="merchant_branch_officeno" id="merchant_branch_officeno" maxlength="16" type="text" placeholder="0330003000" value="{{ ($branch->merchant_branch_officeno != '') ? $branch->merchant_branch_officeno : old('merchant_branch_officeno') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_faxno" class="col-sm-4 control-label">{{ __('Merchant::branch.faxno') }}</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="merchant_branch_faxno" id="merchant_branch_faxno" maxlength="16" type="text" placeholder="0330003000" value="{{ ($branch->merchant_branch_faxno != '') ? $branch->merchant_branch_faxno : old('merchant_branch_faxno') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_branch_faxno" class="col-sm-4 control-label">Store Front</label>
                    <div class="col-sm-6">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput">
                                <img src="{!! (!empty($branch->storefront)) ? asset($branch->storefront->upload_path.$branch->storefront->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 170px; max-height: 170px"></div>
                            <div>
                                <span class="btn btn-primary btn-sm btn-file">
                                    <span class="fileinput-new">{{ __('Admin::base.selectphoto') }}</span>
                                    <span class="fileinput-exists">{{ __('Admin::base.change') }}</span>
                                    <input type="file" name="img_storefront" id="img_storefront" accept="image/*" multiple="" required/>
                                </span>
                                <!--a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a-->
                            </div>
                        </div>
                </div>

            </div>

        </div>
        
    </div>
    <div class="box-footer">
        <a href="{{ route('merchant.branch.index',Crypt::encrypt($merchants->merchant_id)) }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
        @if(Auth::user()->can('merchant.branch.store'))
        <button type="button" class="btn btn-sm btn-success pull-right submitform"><i class="fa fa-check-circle"></i> {{ __('Admin::base.submit') }}</button>
        @endif
    </div>
    {!! Form::close() !!}
</div>

@stop

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">
<script type="text/javascript">

    $(document).ready(function() {

        @if($branch->merchant_branch_id != '') 
            @if($branch->user->status_approve=='1')
                $('.datareadonly').attr('readonly','readonly');
            @endif
        @endif

        @if($branch->merchant_id != '')
            $('#merchant_id').val('{{ $branch->merchant_id }}');
        @else
            //$('#merchant_id').val('{{ old("merchant_id") }}');
        @endif

        //if($('#merchant_branch_postcode').val()!=''){
            @if($branch->merchant_branch_postcode != '')
                getstatedistrict('{{ $branch->merchant_branch_postcode }}');
            @else
                getstatedistrict('{{ old("merchant_branch_postcode") }}');
            @endif
        //}

        $('#merchant_branch_postcode').on('keyup', function(e){
            
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

    });

</script>
@stop
          