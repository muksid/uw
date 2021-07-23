<html>
<head>
    <meta charset="utf-8">

    <title>Anderrating</title>

    <link href="{{ asset("admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("admin-lte/dist/css/skins/_all-skins.min.css") }}" rel="stylesheet" type="text/css">


</head>
<body class="skin-green">

    <div class="wrapper">
        @include('layouts.error_header')

        @include('layouts.error_sidebar')

            <div class="content-wrapper">
                @yield('content')
            </div>

        @include('layouts.footer')
    </div>

</body>

<script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

<script src="{{ asset ("admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>

<script src="{{ asset ("admin-lte/dist/js/app.min.js") }}"></script>

<script src="{{ asset ("admin-lte/dist/js/demo.js") }}"></script>

</html>
