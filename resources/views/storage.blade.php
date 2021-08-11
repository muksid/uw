@extends('layouts.dashboard')
@section('content')

    <section class="content-header">
        <h1>
            Bosh sahifa {{ $_SERVER['REMOTE_ADDR'] }}
            <small>nazorat paneli</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Uw</a></li>
            <li class="active">bosh sahifa</li>
        </ol>

        @if(session('message'))
            <div class="box box-default">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <h4 class="modal-title"> {{ session('message') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </section>

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
                    <a href="{{ url('/madmin/get-log-file') }}" class="small-box-footer"
                       target="_blank"
                       onclick="window.open('<?php echo ('/madmin/get-log-file'); ?>',
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
                        <a href="{{ url('/madmin/cache/route') }}">Route Clear</a>
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
                        <a href="{{ url('/madmin/cache/config') }}">Config Cache</a>
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
                        <a href="{{url('/madmin/cache/clear')}}">Clear Cache</a>
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
                        <a href="{{url('/madmin/cache/view')}}">View Cache</a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>

    </section>
@endsection
