@extends('layouts.frontend.electro')


@section('content')
	
	
	{!! $html !!}

@stop()

@section('footer')

@if($type == 'request')
<script type="text/javascript">
	window.onload = function(){
	  // document.forms['payment_gateway'].submit();
	}
</script>
@endif

@stop()