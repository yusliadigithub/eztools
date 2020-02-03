@extends('layouts.adminLTE.master')

@section('header')
<script src="https://cdn.ckeditor.com/4.9.2/standard-all/ckeditor.js"></script>
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('Merchant::merchant.websitepage') }}</h3>
            </div>
            {!! Form::open(['action'=>'\App\Modules\Merchant\Controllers\MerchantController@updatepage', 'method'=>'post', 'role'=>'form', 'id'=>'pageform' ]) !!}
            <input type="hidden" name="merchant_page_id" id="merchant_page_id" value="{!! $page->merchant_page_id !!}">
            <input type="hidden" name="merchant_id" id="merchant_id" value="{!! ($page->merchant_id != '') ? $page->merchant_id : Crypt::decrypt($merchantid) !!}">
            <div class="box-body">
                
                <div class="form-group">
                    <label for="merchant_page_title">{{ __('Merchant::merchant.title') }}</label>
                    <input class="form-control" type="text" name="merchant_page_title" id="merchant_page_title" value="{{ ($page->merchant_page_title != '') ? $page->merchant_page_title : old('merchant_page_title') }}" required />
                </div>
                <div class="form-group">
                    <label for="merchant_page_content">{{ __('Merchant::merchant.pagecontent') }}</label>
                    <textarea cols="80" id="merchant_page_content" name="merchant_page_content" rows="10" >{{ ($page->merchant_page_content != '') ? $page->merchant_page_content : old('merchant_page_content') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="merchant_page_order">{{ __('Admin::base.order') }}</label>
                    <input class="form-control" type="number" name="merchant_page_order" id="merchant_page_order" value="{{ ($page->merchant_page_order != '') ? $page->merchant_page_order : old('merchant_page_order') }}" required />
                </div>
                <div class="form-group">
                    <label for="complaint_message">{{ __('Merchant::merchant.publishstatus') }}</label>
                    <div class="checkbox checbox-switch switch-primary">
                        <label>
                            <input type="checkbox" class="form-control" name="merchant_page_status" id="merchant_page_status" value="1" {{ ($page->merchant_page_status=='1') ? 'checked' : '' }} />
                            <span></span>
                        </label>
                    </div>
                </div>
                @if($page->merchant_page_status=='1')
                <div class="form-group">
                    <label for="complaint_message">{{ __('Merchant::merchant.publishdate') }}</label>
                    <input class="form-control" type="text" value="{{ date( 'd F Y', strtotime($page->merchant_page_date)) }}" disabled />
                </div>
                @endif

            </div>
            <div class="box-footer">
                <div class="form-group pull-right">
                    <a href="{{ URL::to('merchant/pageindex') }}/{!! ($page->merchant_id != '') ? Crypt::encrypt($page->merchant_id) : $merchantid !!}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
                    @if(Auth::user()->can('merchant.createpage'))
                    <button type="button" class="btn btn-sm btn-primary submitform"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
                    @endif
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@section('footer')

<script type="text/javascript">

$(document).ready(function() {

    
});

    CKEDITOR.config.devtools_styles =
        '#cke_tooltip { line-height: 20px; font-size: 12px; padding: 5px; border: 2px solid #333; background: #ffffff }' +
        '#cke_tooltip h2 { font-size: 14px; border-bottom: 1px solid; margin: 0; padding: 1px; }' +
        '#cke_tooltip ul { padding: 0pt; list-style-type: none; }';

    CKEDITOR.replace( 'merchant_page_content', {
        height: 200,
        extraPlugins: 'devtools'
    });

    $('.submitform').on('click', function() {

        if($("#merchant_page_title").val()=='' || $("#merchant_page_order").val()==''){
            swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
            return false;
        }

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

                $('#pageform').submit();

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

    $('.enabledata').on('click', function() {
    
        var url = $(this).attr('value');
        var askmsg = $(this).data('askmsg');

        swal({
            title: askmsg,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: '{{ __("Admin::base.cancel") }}',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("Admin::base.yes") }}',
          
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