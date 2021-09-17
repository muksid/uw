<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('blade.title') | @lang('blade.webUw')</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    <link href="{{ asset("/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css">

    <!-- Font Awesome -->
    <link href="{{ asset("/admin-lte/bootstrap/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css">

    <!-- DataTables -->
    <link href="{{ asset("/admin-lte/plugins/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("/css/main.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

    <link href="{{ asset('/admin-lte/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <!-- Theme style -->
    <link href="{{ asset("/admin-lte/dist/css/AdminLTE.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("/admin-lte/plugins/iCheck/all.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("/admin-lte/dist/css/skins/_all-skins.min.css") }}" rel="stylesheet">

    <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

    <!-- ajax validate form -->
    <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

    <script src="{{ asset ("/js/webcamjs/webcam.min.js") }}"></script>
