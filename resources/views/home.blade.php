@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            @lang('blade.user')
            <small>@lang('blade.panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.panel')</a></li>
            <li class="active">home</li>
        </ol>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('blade.error')</strong> @lang('blade.exist').<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="box box-default">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <h4 class="modal-title"> {{ session('success') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="{{ url('/admin-lte/dist/img/user.png') }}" alt="User profile picture">

                        <h4 class="profile-username text-center">{{ $userInfo->l_name }}</h4>

                        <p class="text-muted text-center">{{ $userInfo->f_name .' '. $userInfo->m_name }}</p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Jismoniy shaxs arizalar</b> <a class="pull-right"><span class="label label-success">{{ $phyClients }}</span></a>
                            </li>
                            <li class="list-group-item">
                                <b>Yuridik shaxs arizalar</b> <a class="pull-right"><span class="label label-success">{{ $jurClients }}</span></a>
                            </li>
                        </ul>

                    </div>

                </div>

            </div>

            <div class="col-md-9">

                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title">Men haqimda</h3>
                    </div>

                    <div class="box-body">

                        <strong><i class="fa fa-bank margin-r-5"></i> Ish joyi va Lavozimi</strong>

                        <h5 class="text-green">{{ $checkUserWork->filial->title??'-' }}<span>, {{ $checkUserWork->department->title??'-' }} {{ $checkUserWork->job_title }}</span></h5>

                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fa fa-book margin-r-5"></i> Passport ma`lumotlari</strong>

                                <h5><b>PINFL: </b>{{ $userInfo->pinfl }}</h5>
                                <h5><b>STIR: </b>{{ $userInfo->inn }}</h5>

                                <h5><b>Passport: </b>{{ $userInfo->doc_series.' '.$userInfo->doc_number }}</h5>

                                <h5><b>Berilgan vaqti: </b>{{ date('d.m.Y', strtotime($userInfo->doc_begin_date))}} йил.</h5>

                                <h5><b>Amal qilish muddati: </b>{{ date('d.m.Y', strtotime($userInfo->doc_end_date))}} йилгача.</h5>

                                <h5><b>Kim tomonidan: </b>{{ $userInfo->doc_address }} томонидан берилган.</h5>

                                <h5><b>Telefon: </b>{{ $userInfo->mobile_phone }}</h5>

                                <h5><b>e-mail: </b>{{ $userInfo->email }}</h5>

                                <h5><b>Tug`ilgan kun: </b>{{ date('d.m.Y', strtotime($userInfo->birthday)) }} йил.</h5>

                                <hr>

                                <strong><i class="fa fa-map-marker margin-r-5"></i> Manzil ma`lumotlari</strong>

                                <h5><b>Yashash manzili: </b>{{ $userInfo->address }}</h5>

                                <hr>

                                <strong><i class="fa fa-pencil margin-r-5"></i> Maqomi (roles)</strong>

                                <p>
                                    @if($roles)
                                        @foreach($roles as $role)

                                            <span class="label label-info">{{ $role->getRoleName->title_ru??'-' }}</span>

                                        @endforeach
                                    @endif
                                </p>

                                <hr>

                                <a href="{{ url('/madmin/update-user-info', $userInfo->emp_id) }}" class="btn btn-flat btn-success"><i class="fa fa-refresh"></i> Yangilash</a>

                            </div>

                            <div class="col-md-6">

                                <div class="col-md-12">
                                    <div class="info-box bg-red">
                                        <span class="info-box-icon"><i class="fa fa-calendar"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Passport muddati tugashiga (kun qoldi)</span>
                                            <span class="info-box-number">{{ $pass_diff->y.' йил. '.$pass_diff->m.' ой. '.$pass_diff->d.' кун.' }}</span>

                                            <div class="progress">
                                                <div class="progress-bar" style="width: {!! $pass_diff->y/10*100 !!}%"></div>
                                            </div>
                                            <span class="progress-description">
                                                {!! $pass_diff->y/10*100 !!}% {{ date('d.m.Y', strtotime($userInfo->doc_end_date))}} йилгача.
                                            </span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>
        </div>

    </section>

@endsection
