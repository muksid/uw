<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('blade.title') | @lang('blade.webEdo')</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    <link href="{{ asset("/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css">

    <!-- Font Awesome -->
    <link href="{{ asset("/admin-lte/bootstrap/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css">

    <!-- DataTables -->
    <link href="{{ asset("/admin-lte/plugins/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" type="text/css">

    <!-- Theme style -->
    <link href="{{ asset("/admin-lte/dist/css/AdminLTE.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css">

    <!-- AdminLTE Skins. Choose a skin from the css/skins-->
    <link href="{{ asset("/admin-lte/dist/css/skins/_all-skins.min.css") }}" rel="stylesheet">

    {{--<script src="{{ asset('js/treejquery.js') }}"></script>--}}
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

<body class="skin-blue">

<!-- Header -->
@include('layouts.uw.header')

<!-- Sidebar -->
@include('layouts.uw.sidebar')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @yield('content')
</div>
<!-- /.content-wrapper -->

@include('layouts.uw.footer')


<!-- DataTables -->
<script src="{{ asset("/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset("/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

<!-- Bootstrap 3.3.6 button logOut -->
<script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>

<!-- iCheck -->
<script src="{{ asset("/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>

