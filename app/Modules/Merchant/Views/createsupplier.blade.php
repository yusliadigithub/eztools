@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Merchant::supplier.detail') }}</h3>
    </div>
    @if($supplier->merchant_supplier_id != '')
    {!! Form::open(['action'=>['\App\Modules\Merchant\Controllers\SupplierController@update',$supplier->merchant_supplier_id], 'method'=>'put', 'class'=>'form-horizontal', 'id'=>'newdataform']) !!}
    @else
    {!! Form::open(['action'=>'\App\Modules\Merchant\Controllers\SupplierController@store', 'method'=>'post', 'class'=>'form-horizontal', 'id'=>'newdataform']) !!}
    @endif
    <input name="merchant_id" type="hidden" value="{{ Crypt::decrypt($merchantid) }}">
    <input name="merchant_supplier_id" type="hidden" value="{{ $supplier->merchant_supplier_id }}">
        <div class="box-body">
            <div class="col-md-6">

                <div class="form-group">
                    <label for="merchant_supplier_name" class="col-sm-4 control-label">{{ __('Merchant::supplier.companyname') }}</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_supplier_name" id="merchant_supplier_name" maxlength="100" placeholder="{{ __('Merchant::supplier.companyname') }}" type="text" value="{{ ($supplier->merchant_supplier_name != '') ? $supplier->merchant_supplier_name : old('merchant_supplier_name') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_supplier_ssmno" class="col-sm-4 control-label">{{ __('Merchant::supplier.ssmno') }}</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="merchant_supplier_ssmno" id="merchant_supplier_ssmno" maxlength="20" placeholder="{{ __('Merchant::supplier.ssmno') }}" type="text" value="{{ ($supplier->merchant_supplier_ssmno != '') ? $supplier->merchant_supplier_ssmno : old('merchant_supplier_ssmno') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_supplier_address1" class="col-sm-4 control-label">{{ __('Merchant::supplier.address') }}</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_supplier_address1" id="merchant_supplier_address1" maxlength="100" type="text" value="{{ ($supplier->merchant_supplier_address1 != '') ? $supplier->merchant_supplier_address1 : old('merchant_supplier_address1') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_supplier_address2" class="col-sm-4 control-label">&nbsp;</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_supplier_address2" id="merchant_supplier_address2" maxlength="100" type="text" value="{{ ($supplier->merchant_supplier_address2 != '') ? $supplier->merchant_supplier_address2 : old('merchant_supplier_address2') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_supplier_address3" class="col-sm-4 control-label">&nbsp;</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_supplier_address3" id="merchant_supplier_address3" maxlength="100" type="text" value="{{ ($supplier->merchant_supplier_address1 != '') ? $supplier->merchant_supplier_address1 : old('merchant_supplier_address1') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_supplier_postcode" class="col-sm-4 control-label">{{ __('Merchant::supplier.postcode') }}</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="merchant_supplier_postcode" id="merchant_supplier_postcode" maxlength="5" type="text" value="{{ ($supplier->merchant_supplier_postcode != '') ? $supplier->merchant_supplier_postcode : old('merchant_supplier_postcode') }}" placeholder="eg: 43000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="district_id" class="col-sm-4 control-label">{{ __('Merchant::supplier.district') }}</label>
                    <div class="col-sm-6">
                        <select name="district_id" id="district_id" class="form-control" required>
                            <option value="" {{ (old('district_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="state_id" class="col-sm-4 control-label">{{ __('Merchant::supplier.state') }}</label>
                    <div class="col-sm-6">
                        <select name="state_id" id="state_id" class="form-control" required>
                            <option value="" {{ (old('state_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                        </select>
                    </div>
                </div>
                
            </div>

            <div class="col-md-6">

                <div class="form-group">
                    <label for="merchant_supplier_person_incharge" class="col-sm-4 control-label">{{ __('Merchant::merchant.contactperson') }}</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_supplier_person_incharge" id="merchant_supplier_person_incharge" maxlength="100" type="text" value="{{ ($supplier->merchant_supplier_person_incharge != '') ? $supplier->merchant_supplier_person_incharge : old('merchant_supplier_person_incharge') }}" placeholder="{{ __('Merchant::supplier.personincharge') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_supplier_email" class="col-sm-4 control-label">{{ __('Merchant::supplier.email') }}</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="merchant_supplier_email" id="merchant_supplier_email" maxlength="100" placeholder="myemail@gmail.com" type="email" value="{{ ($supplier->merchant_supplier_email != '') ? $supplier->merchant_supplier_email : old('merchant_supplier_email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_supplier_mobileno" class="col-sm-4 control-label">{{ __('Merchant::supplier.mobileno') }}</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="merchant_supplier_mobileno" id="merchant_supplier_mobileno" maxlength="16" type="text" placeholder="0330003000" value="{{ ($supplier->merchant_supplier_mobileno != '') ? $supplier->merchant_supplier_mobileno : old('merchant_supplier_mobileno') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_supplier_officeno" class="col-sm-4 control-label">{{ __('Merchant::supplier.officeno') }}</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="merchant_supplier_officeno" id="merchant_supplier_officeno" maxlength="16" type="text" placeholder="0330003000" value="{{ ($supplier->merchant_supplier_officeno != '') ? $supplier->merchant_supplier_officeno : old('merchant_supplier_officeno') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="merchant_supplier_faxno" class="col-sm-4 control-label">{{ __('Merchant::supplier.faxno') }}</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="merchant_supplier_faxno" id="merchant_supplier_faxno" maxlength="16" type="text" placeholder="0330003000" value="{{ ($supplier->merchant_supplier_faxno != '') ? $supplier->merchant_supplier_faxno : old('merchant_supplier_faxno') }}">
                    </div>
                </div>

            </div>

        </div>
        <div class="box-footer">
            <a href="{{ URL::to('merchant/supplier/'.$merchantid.'/index') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
            <button type="button" class="btn btn-sm btn-success pull-right submitform"><i class="fa fa-check-circle"></i> {{ __('Admin::base.submit') }}</button>
        </div>
        
    </div>
    
    {!! Form::close() !!}
</div>

@stop

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">
<script type="text/javascript">

    $(document).ready(function() {

        //if($('#merchant_supplier_postcode').val()!=''){
            @if($supplier->merchant_supplier_postcode != '')
                getstatedistrict('{{ $supplier->merchant_supplier_postcode }}');
            @else
                getstatedistrict('{{ old("merchant_supplier_postcode") }}');
            @endif
        //}

        $('#merchant_supplier_postcode').on('keyup', function(e){
            
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
          