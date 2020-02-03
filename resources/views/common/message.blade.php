@if (session('flash_success'))
    <div class="alert alert-block alert-success fade in alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>{{ __('Admin::base.success') }}!</strong>
        {!! session('flash_success') !!}
        <!--span class="close" data-dismiss="alert">×</span-->
    </div>
@endif

@if (session('flash_error'))
    <div class="alert alert-block alert-error alert-danger fade in alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>{{ __('Admin::base.error') }}!</strong>
        {!! session('flash_error') !!}
        <!--span class="close" data-dismiss="alert">×</span-->
    </div>
@endif

@if ( isset($errors) && count( $errors ) > 0 )
    <div class="alert alert-danger alert-dismissable alert-condensed">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="icon-cross"></i></button>
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-exclamation-circle append-icon"></i><strong>Opps!</strong> {{ $error }}</p>
            @endforeach
    </div>
@endif