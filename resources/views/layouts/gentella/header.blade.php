<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{!! !empty($pageHeaderTitle) ? $pageHeaderTitle : ''  !!}</title>

    <!-- Bootstrap -->
    <link href="{!! asset('gentella/vendors/bootstrap/dist/css/bootstrap.min.css') !!}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{!! asset('gentella/vendors/font-awesome/css/font-awesome.min.css') !!}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{!! asset('gentella/vendors/nprogress/nprogress.css') !!}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="{!! asset('gentella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') !!}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{!! asset('gentella/vendors/jqvmap/dist/jqvmap.min.css') !!}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{!! asset('gentella/vendors/bootstrap-daterangepicker/daterangepicker.css') !!}" rel="stylesheet">
    <!-- sweetalert -->
    <link href="{!! asset('gentella/vendors/sweetalert2/dist/sweetalert2.css') !!}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{!! asset('gentella/build/css/custom.min.css') !!}" rel="stylesheet">

    <link href="{!! asset('css/mjmz.css') !!}" rel="stylesheet">

    @section('header')
    @show
    
  </head>