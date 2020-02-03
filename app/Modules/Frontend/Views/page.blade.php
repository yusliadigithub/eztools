@extends('layouts.frontend.electro')

@section('breadcrumb')
<h3 class="breadcrumb-header">{{ $page->merchant_page_title }}</h3>
@stop()

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">{!! $page->merchant_page_content !!}</div>
	</div><br />
	<small>
		<ul class="list-inline">
			<li><span class="badge badge-secondary">Last update : {{ date('d M Y', strtotime($page->updated_at)) }}</span></li>
		</ul>
	</small>
	
</div>
@stop()