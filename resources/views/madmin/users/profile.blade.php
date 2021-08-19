@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            @lang('blade.edit_user')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.sidebar_users')</a></li>
            <li class="active">edit</li>
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

    <section class="content">

        <div id="loading" class="loading-gif" style="display: none"></div>

        <div class="row">
            <div class="col-md-5">
                <div class="box box-success">
                    <h3 class="margin">Username</h3>
                    <form method="POST" action="{{ url('/madmin/user/profile-update',$user->id) }}" role="form">
                        @csrf
                        <div class="row">
                            <div class="col-md-7">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" class="form-control" value="{{ $user->username??'-' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('cb_id') ? 'has-error' : '' }}">
                                        <label for="username">CB ID</label>
                                        <input type="text" class="form-control" value="{{ $user->cb_id??'-' }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Enter password">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('passwordConf') ? 'has-error' : '' }}">
                                        <label for="passwordConf">Password Conf</label>
                                        <input type="password" name="passwordConf" class="form-control" id="passwordConf" placeholder="Enter password conf">
                                    </div>
                                </div>
                            </div>
                            <input type="text" name="isActive" value="{{ $user->isActive }}" hidden>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-pencil"></i> @lang('blade.update')</button>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </section>
@endsection
