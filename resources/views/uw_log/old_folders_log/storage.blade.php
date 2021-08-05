@extends('uw_log.uw.dashboard')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Bosh sahifa {{ $_SERVER['REMOTE_ADDR'] }}
            <small>nazorat paneli</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> WebEDO</a></li>
            <li class="active">bosh sahifa</li>
        </ol>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Xatolik!</strong> xatolik bor.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3></h3>

                        <p>Storage Log</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-alert"></i>
                    </div>
                    <a href="{{ url('storage',['log'=> 'laravel.log']) }}" class="small-box-footer"
                       target="_blank" class="mailbox-attachment-name"
                       onclick="window.open('<?php echo ('/storage/'. 'laravel.log'); ?>',
                               'modal',
                               'width=800,height=900,top=30,left=500');
                               return false;">
                        Storage Log</a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3></h3>

                        <p>Chat</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-send"></i>
                    </div>
                    <a href="#" class="small-box-footer">to`liq ma`lumot <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3></h3>

                        <p>Ro`yhatdan o`tgan xodimlar</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer"> <i class="fa fa-user-plus"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3></h3>

                        <p>Muddatli xatlar</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-alarm-clock"></i>
                    </div>
                    <a href="#" class="small-box-footer">to`liq ma`lumot <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Route</span>
                        <a href="{{url('/route-cache')}}">Route Cache</a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Config</span>
                        <a href="{{url('/config-cache')}}">Config Cache</a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Cache</span>
                        <a href="{{url('/clear-cache')}}">Clear Cache</a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">View</span>
                        <a href="{{url('/view-clear')}}">View Clear</a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <script type="text/javascript">
            $(document).ready(function () {

            });
        </script>

        <!-- Ionicons -->
        <link href="{{ asset("/admin-lte/bootstrap/css/ionicons.min.css") }}" rel="stylesheet" >

        <link href="{{ asset("/admin-lte/bootstrap/css/font-awesome.min.css") }}" rel="stylesheet" >

        <script src="{{ asset("/admin-lte/dist/js/jquery-ui.min.js") }}"></script>


        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

    </section>
@endsection
