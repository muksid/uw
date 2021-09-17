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

    @if ($user->id == Auth::user()->id)

        <!-- Main content -->
        <section class="content">

            <div class="box box-success">
                <div class="box-header with-border">
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="row">

                        <form role="form" method="POST" action="{{ url('/madmin/users/' . $user->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.name') <span style="color:red">*</span></label>
                                    <input type="text" name="fname" class="form-control" value="{{$user->personal->f_name??'-' }}"
                                           placeholder="Enter first name" required>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.surname') <span style="color:red">*</span></label>
                                    <input type="text" name="lname" class="form-control" value="{{$user->personal->l_name??'-' }}"
                                           placeholder="Enter last name" required>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.fathers_name') <span style="color:red">*</span></label>
                                    <input type="text" name="sname" class="form-control" value="{{$user->personal->m_name??'-'}}"
                                           placeholder="Enter middle name" required>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.username') <span style="color:red">*</span></label>
                                    <input type="text" name="username" class="form-control" value="{{$user->username}}"
                                           disabled>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-6">

                                <div class="col-md-6">
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="exampleInputPassword1">@lang('blade.password') <span
                                                    style="color:red">*</span></label>
                                        <input type="password" name="password" class="form-control" id="inputError"
                                               placeholder="@lang('blade.password')" required>
                                        <span class="help-block">@lang('blade.enter_pass_at_least_6')</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        <label for="exampleInputPassword1">@lang('blade.confirm_pass') <span
                                                    style="color:red">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                               id="inputError" placeholder="@lang('blade.password')" required>
                                        <span class="help-block">@lang('blade.repeat_pass')</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <a href="/home" class="btn btn-default">@lang('blade.cancel')</a>
                                        <button type="submit" class="btn btn-primary">@lang('blade.save')</button>
                                    </div>

                                </div>
                            </div>
                            <!-- /.col -->
                        </form>
                        <!-- /.form-group -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </section>


    @else

    <section class="content">

        <div id="loading" class="loading-gif" style="display: none"></div>

        <form id="editForm" method="POST" action="{{ url('/madmin/users-update') }}" role="form">
            @csrf
            <input type="text" name="w_user_id" id="m_work_user_id" value="{{$model->id??''}}" hidden>
            <input type="text" name="user_id" id="user_id" value="{{$model->user_id??''}}" hidden>

            <!-- Login & Password -->
            <div  class="box box-danger">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-11">
                            <h3>Login & Password</h3>
                        </div>
                        <div class="col-sm-1 text-right">
                            <span class="toggleButton">
                                <i id="login_password_toggle_button" data-value="1" class="fa fa-minus"></i>
                            </span>
                        </div>
                    </div>
                    <div id="login_password_div" class="row">
                        <div class="col-sm-2">
                            <div class="form-group has-feedback">
                                <label for="username">Username <span class="text-red">*</span></label>
                                <input type="text" id="username" class="form-control" data-username="" name="username" minlength="3" maxlength="15" placeholder="Login || Username" value="{{$user->username??''}}" autofocus required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block"><b>Maximum of 15 characters</b> </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group has-feedback">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" id="inputPassword" class="form-control" name="password" data-minlength="6" placeholder="Password">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block"><b>Minimum of 6 characters</b> </div>

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group has-feedback">
                                <label for="exampleInputPassword1">Password Confirm</label>
                                <input type="password" id="inputPasswordConfirm" class="form-control" name="password_confirmation" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block"><b></b> </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group has-feedback">
                                <label for="isUw">IsUw</label>
                                <div class="form-group">
                                    <select name="isUw" class="form-control select2" style="width: 100%;">
                                        @if($user->isUw == 1)
                                            <option selected value="1">Uw User</option>
                                            <option value="0">User</option>
                                        @else
                                            <option selected value="0">User</option>
                                            <option value="1">Uw User</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Login Status -->
                        <div class="col-sm-4">
                            <div class="row text-center">
                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <label for="" class="text-bold">Login Status </label>
                                </div>

                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="1" id="status1" {{ ($user->status == 1) ? 'checked':'' }}>
                                        <label class="form-check-label text-success" for="status1">
                                            Active
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="0" id="status0" {{ ($user->status == 0) ? 'checked':'' }}>
                                        <label class="form-check-label text-orange" for="status0">
                                            Passive
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="2" id="status2" {{ ($user->status == 2) ? 'checked':'' }}>
                                        <label class="form-check-label text-red" for="status2">
                                            Deleted
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Personal info -->
            <div class="box box-warning">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-11">
                            <h3>Personal Info</h3>
                        </div>
                        <div class="col-sm-1 text-right">
                            <span class="toggleButton">
                                <i id="personal_info_toggle_button" data-value="2" class="fa fa-minus"></i>
                            </span>
                        </div>
                    </div>

                    <div id="personal_info_div">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="f_name" class="control-label">First Name <span class="text-red">*</span></label>
                                    <input type="text" id="f_name" class="form-control" name="f_name" maxlength="15" value="{{$personal_user->f_name??''}}" placeholder="First Name" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="l_name" class="control-label">Last Name <span class="text-red">*</span></label>
                                    <input type="text" id="l_name" class="form-control" name="l_name" maxlength="15" value="{{$personal_user->l_name??''}}" placeholder="Last Name" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="m_name" class="control-label">Middle Name <span class="text-red">*</span></label>
                                    <input type="text" id="m_name" class="form-control" name="m_name" maxlength="15" value="{{$personal_user->m_name??''}}" placeholder="Middle Name" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group input-daterange has-feedback">
                                    <label for="birthday" class="control-label">Birthday</label>
                                    <input type="date" id="birthday" class="form-control" name="birthday" value="{{$personal_user->birthday??''}}" placeholder="Middle Name">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b></b></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="address" class="control-label">Address</label>
                                    <input type="text" id="address" class="form-control" name="address" maxlength="60" value="{{$personal_user->address??''}}" placeholder="Address">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 60 characters</b> </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="email" class="control-label">Email <span class="text-red">*</span></label>
                                    <input type="email" id="email" class="form-control" data-email="" name="email" maxlength="25" value="{{$personal_user->email??''}}" placeholder="example@tb.uz" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 25 characters</b> </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Position -->
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-11">
                            <h3>Position <b>#{{$model->id??''}}</b></h3>
                        </div>
                        <div class="col-sm-1 text-right">
                            <span class="toggleButton">
                                <i id="position_toggle_button" data-value="3" class="fa fa-minus"></i>
                            </span>
                        </div>
                    </div>

                    <div id="position_div">
                        <div class="row">

                            <!-- Dpeart ID -->
                            <div class="col-sm-4">
                                <div class="form-group has-feedback">
                                    <label for="depart_id">Branch {{ $model->branch_code??''." - ".($model->filial->title??'')}}</label>
                                    <input type="text" id="depart_title" class="form-control" value="{{$model->department->title??''}}" placeholder="Pick a branch from department list" disabled>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b></b></div>
                                </div>
                            </div>

                            <!-- Job Title -->
                            <div class="col-sm-4">
                                <div class="form-group has-feedback">
                                    <label for="job_title" class="control-label">Job Title </label>
                                    <input type="text" id="job_title" class="form-control" value="{{$model->job_title??''}}" placeholder="Job title" disabled required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b></b> </div>
                                </div>
                            </div>

                            <!-- Card Number -->
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="card_num" class="control-label">Card Number </label>
                                    <input type="number" id="card_num" class="form-control" value="{{$model->tab_num??''}}" placeholder="Tabel Number" disabled>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b></b> </div>
                                </div>
                            </div>

                            <!-- Sort -->
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="sort"> Sort <span class="text-red">*</span></label>
                                    <input type="number" id="sort" class="form-control" name="sort" min="0" max="1000" value="{{$model->sort??''}}" placeholder="Order number in position" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"> <b> Maximum is 1000</b></div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <!-- Ip Phone -->
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="ip_phone" class="control-label">Ip phone </label>
                                    <input type="tel" id="ip_phone" class="form-control" name="ip_phone" maxlength="15" value="{{$model->ip_phone??''}}" placeholder="Ip phone number">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                </div>
                            </div>

                            <!-- Mobile Number -->
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="ip_phone" class="control-label">Mobile phone </label>
                                    <input type="tel" id="mob_phone" class="form-control" name="mob_phone" maxlength="15" value="{{$model->mob_phone??''}}" placeholder="Mobile phone number">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div class="col-sm-2">
                                <div class="form-group input-daterange has-feedback">
                                    <label for="date_begin" class="control-label">Start Date <span class="text-red">*</span></label>
                                    <input type="date" id="date_begin" class="form-control" name="date_begin" value="{{$model->date_begin??''}}" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b></b></div>
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="col-sm-2">
                                <div class="form-group input-daterange has-feedback">
                                    <label for="date_end" class="control-label">End Date </label>
                                    <input type="date" id="date_end" class="form-control" name="date_end" value="{{$model->date_end??''}}">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b></b></div>
                                </div>
                            </div>

                            <!-- isActive -->
                            <div class="col-sm-4">
                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <label for="" class="text-bold">Is Active? <span class="text-red">*</span></label>
                                </div>

                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="isActive" value="A" id="isActive1" {{ (($model->isActive??'') === 'A') ? 'checked':'' }}>
                                        <label class="form-check-label text-success" for="isActive1">
                                            Active
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="isActive" value="P" id="isActive2" {{ (($model->isActive??'') === 'P') ? 'checked':'' }}>
                                        <label class="form-check-label text-orange" for="isActive2">
                                            Passive
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="isActive" value="D" id="isActive3" {{ (($model->isActive??'') === 'D') ? 'checked':'' }}>
                                        <label class="form-check-label text-danger" for="isActive3">
                                            Deleted
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group has-feedback">
                                    <label for="roles">Select Roles<span class="text-red">*</span></label>
                                    <select class="form-select select2" id="roles" aria-label="Default select example" style="width:100%" name="roles[]" multiple="multiple" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}"> {{ $role->title }} </option>
                                        @endforeach
                                    </select>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>You must pick at least one role </b></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-warning" onClick="window.location.reload()"><i class="fa fa-undo"></i> Reload</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> @lang('blade.update')</button>
                    </div>
                </div>
            </div>

            <!-- User History -->
            <div class="box box-success">
                <div class="box-body">
                    <div class="form-group">
                        <button type="button" id="addPositionButton" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> Add Position </button>
                    </div>
                    <div class="row">
                        <div class="col-sm-11">


                        </div>
                        <div class="col-sm-1 text-right">
                            <span class="toggleButton">
                                <i id="user_history_toggle_button" data-value="4" class="fa fa-minus"></i>
                            </span>
                        </div>
                    </div>
                    <div id="user_history_div" class="row">
                        <div class="col-sm-12">
                            <table id="userHistoryTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Branch</th>
                                        <th class="text-center">Department</th>
                                        <th class="text-center">Tab №</th>
                                        <th class="text-center">Start date</th>
                                        <th class="text-center">End date</th>
                                        <th class="text-center"><i class="fa fa-phone"></i> Ip</th>
                                        <th class="text-center"><i class="fa fa-phone"></i> Mobile</th>
                                        <th class="text-center">Sort</th>
                                        <th class="text-center">Roles</th>
                                        <th class="text-center">isActive</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Activate</th>
                                    </tr>
                                </thead>
                                <tbody id="userHistoryTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </form>

        <!-- Add Position -->
        <div class="box box-primary" id="addPositionBox" hidden>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-11">
                        <h3>Add Position</h3>
                    </div>
                    <div class="col-sm-1 text-right">
                        <span class="toggleButton">
                            <i id="add_position_toggle_button" data-value="5" class="fa fa-minus"></i>
                        </span>
                    </div>
                </div>
                <div id="add_position_div" class="row">
                    <!-- List departments -->
                    <div class="col-md-6">
                        <div class="form-group" style="overflow-y: scroll; max-height:500px">
                            <label>Department List</label>

                            <ul id="tree1" style="list-style: none">

                                @foreach($filials as $department)

                                    <li class="department_class text-black text-bold" data-id="{{$department->id}}">

                                        {{ $department->branch_code }} - {{ $department->title }}

                                        @if(count($department->childs))

                                            @include('madmin.departments.departChild',['childs' => $department->childs])

                                        @endif

                                    </li>

                                @endforeach

                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <form method="POST" action="{{ route('work-users.store') }}" data-toggle="validator" role="form">
                            @csrf

                            <!-- User ID -->
                            <input type="text" name="user_id" value="{{$user->id}}" hidden>

                            <!-- Dpeart ID -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group has-feedback">
                                        <label for="branch_id">Branch <span class="text-red">*</span> <span id="branch"></span></label>
                                        <input type="text" id="branch_id_title" class="form-control" placeholder="Pick a branch from department list" disabled required>
                                        <input type="hidden" id="branch_id" class="form-control" name="depart_id" placeholder="Pick a branch from department list" required>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"><b></b></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Title -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group has-feedback">
                                        <label for="add_job_title" class="control-label">Job Title <span class="text-red">*</span></label>
                                        <input type="text" id="add_job_title" class="form-control" name="job_title" maxlength="50" placeholder="Job title" required>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"><b>Maximum of 50 characters</b> </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback">
                                        <label for="add_sort"> Sort <span class="text-red">*</span></label>
                                        <input type="number" id="add_sort" class="form-control" name="sort" min="0" max="1000" value="0" placeholder="Order number in position" required>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"> <b> Enter a number. Maximum of 1000</b></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback">
                                        <label for="add_tab_num" class="control-label">Card Number <span class="text-red">*</span></label>
                                        <input type="number" id="add_tab_num" class="form-control" data-tab_num="" name="tab_num" minlength="3" maxlength="6" min="0" max="999999" placeholder="Tabel Number" required>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"><b>Maximum of 6 characters</b> </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ip and Mobile phone -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback">
                                        <label for="add_ip_phone" class="control-label">Ip phone </label>
                                        <input type="tel" id="add_ip_phone" class="form-control" name="ip_phone" maxlength="15" placeholder="Ip phone number">
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group has-feedback">
                                        <label for="mob_phone" class="control-label">Mobile phone </label>
                                        <input type="tel" id="add_mob_phone" class="form-control" name="mob_phone" maxlength="15" placeholder="Mobile phone number">
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Start and End date -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group input-daterange has-feedback">
                                        <label for="add_date_begin" class="control-label">Start Date <span class="text-red">*</span></label>
                                        <input type="date" id="add_date_begin" class="form-control" name="date_begin" required>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"><b></b></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group input-daterange has-feedback">
                                        <label for="add_date_end" class="control-label">End Date</label>
                                        <input type="date" id="add_date_end" class="form-control" name="date_end">
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"><b></b></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Roles -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group has-feedback">
                                        <label for="add_roles">Select Roles<span class="text-red">*</span></label>
                                        <select class="form-select select2" id="add_roles" aria-label="Default select example" style="width:100%" name="roles[]" multiple="multiple" required>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}"> {{ $role->title }} </option>
                                            @endforeach
                                        </select>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"><b>You must pick at least one role </b></div>
                                    </div>
                                </div>
                            </div>

                            <!-- isActive -->
                            <div class="row">
                                <div class="col-sm-2 text-center" align="center">
                                    <br>
                                    <label for="" class="text-bold">Is Active? <span class="text-red">*</span></label>
                                </div>

                                <div class="col-sm-2 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="isActive" value="A" id="addActive1" checked>
                                        <label class="form-check-label text-success" for="addActive1">
                                            Active (A)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="isActive" value="P" id="addActive2">
                                        <label class="form-check-label text-orange" for="addActive2">
                                            Passive (P)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="isActive" value="D" id="addActive3">
                                        <label class="form-check-label text-danger" for="addActive3">
                                            Deleted (D)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="form-group text-center">
                                <button type="button" class="btn btn-warning" onClick="clearAddPosition()">Clear</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>


        <!-- Show Roles Modal -->
        <div class="modal fade" id="userRoles" role="dialog" aria-labelledby="userRolesModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3 class="modal-title text-center" id="userRolesModalLabel">User #</h3>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <select class="select2 text-black" id="get_roles" aria-label="" style="width:100%" multiple="multiple" disabled>
                                    @foreach($roles as $role)
                                        <option class="text-black" value="{{ $role->id }}"> {{ $role->title }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-bold" id="confirmModalLabel"> Are you sure you want to activate this user? </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="text-warning">Warning!</span> After activating the user, other accounts will automatically be deactivated!

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger activateUserConfirmed" data-id="">Activate</button>
                </div>
                </div>
            </div>
        </div>

        <!--Response Modal -->
        <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="responseModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="modal-footer">
                        <button id="responseCloseButton" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            function clearAddPosition() {
                $('#branch_id_title').val("")
                $('#branch_id').val("")
                $('#add_job_title').val("")
                $('#add_sort').val(0)
                $('#add_tab_num').val("")
                $('#add_ip_phone').val("")
                $('#add_mob_phone').val("")
                $('#add_date_begin').val("")
                $('#add_date_end').val("")
                $('#branch').text("")
                $('#add_roles').val(3).trigger('change');
            }

            $(document).ready(function() {

                $('.select2').select2()

                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    forceParse: false,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                })

                document.getElementById("add_date_begin").valueAsDate = new Date()

                let user_id = <?php echo $user->id??1; ?>;

                $.ajax({
                    url: '/madmin/work-users/get-history/'+user_id,
                    type: 'GET',
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function(res){

                        let row = ''
                        let roles = ''
                        let status = ''
                        let activateButton = ''

                        $.each(res, function(index, value) {

                            activateButton = "<button type='button' class='btn btn-xs btn-default' data-id="+value.id+">Activate</button>"
                            roles = "<a class='btn btn-info btn-xs rolesHistory' data-id="+value.id+"><i class='fa fa-wrench' aria-hidden='true'></i></a>"

                            switch (value.isActive) {
                                case "A":
                                    status = "<span class='label label-success'>active</span>"
                                    activateButton = ""
                                    break;
                                case "P":
                                    status = "<span class='label label-warning'>passive</span>"
                                    break;
                                case "D":
                                    status = "<span class='label label-danger'>deleted</span>"
                                    break;
                                default:
                                    break;
                            }
                            row += "<tr>"+
                                    "<td> " + value.id + "</td>"+
                                    "<td> <span class='text-bold'>" + value.branch_code + "</span> - "+ value.branch_title +"</td>"+
                                    "<td style='max-width: 200px'>" + value.depart_title+" <span class='text-muted'>"+value.job_title + "</span></td>"+
                                    "<td>" + value.tab_num + "</td>"+
                                    "<td>" + formatDate(value.date_begin) + "</td>"+
                                    "<td> " + value.date_end + "</td>"+
                                    "<td> " + value.ip_phone + "</td>"+
                                    "<td> " + value.mob_phone + "</td>"+
                                    "<td> " + value.sort + "</td>"+
                                    "<td> " + roles + "</td>"+
                                    "<td> " + status + "</td>"+
                                    "<td> " + formatDate(value.updated_at) + "</td>"+
                                    "<td class='text-center activateUser'>"+activateButton+"</td>"+
                                "</tr>"
                        })
                        $('#userHistoryTableBody').append(row)
                    },

                    error: function (err) {
                        console.log(err)
                    }
                })

                $.ajax({
                    url: '/madmin/work-users/get-roles/'+user_id,
                    type: 'GET',
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function(res){

                        let arr = []

                        $.each(res.models, function(index, value) {
                            arr.push(value.id)
                        })

                        $('#roles').val(arr);
                        $('#roles').trigger('change');

                    },

                    error: function (err) {
                        console.log(err)
                    }
                })

                $('#add_roles').val(3).trigger('change')


                $('#username').keyup(function() {
                    let username = $(this).val()
                    let current_username = "<?php echo $user->username; ?>";

                    if(username == ""){
                        username = 1
                    }

                    if(username != current_username && username.length > 3){
                        console.log("INside")
                        $.ajax({

                            url: '/madmin/users-username-check/'+username,
                            type: 'GET',
                            data: {_token: CSRF_TOKEN},
                            dataType: 'JSON',
                            success: function(res){

                                if(!jQuery.isEmptyObject(res)){
                                    $("#username").closest('.form-group').removeClass('has-success').addClass('has-error');

                                    $('#username').data("username",username )

                                }else{
                                    $("#username").closest('.form-group').removeClass('has-error').addClass('has-success')
                                }
                            },
                            error: function (err) {
                                console.log(err)
                            }

                        })
                    }
                })

                $('#email').keyup(function() {
                    let email = $(this).val()

                    if(email == ""){
                        email = 1
                    }

                    let current_email = "<?php echo $personal_user->email??''; ?>";


                    if(email != current_email && email.length > 3){
                        $.ajax({

                            url: '/madmin/personal-users-email-check/'+email,
                            type: 'GET',
                            data: {_token: CSRF_TOKEN},
                            dataType: 'JSON',
                            success: function(res){

                                if(!jQuery.isEmptyObject(res)){
                                    $("#email").closest('.form-group').removeClass('has-success').addClass('has-error');
                                    $('#email').data("email",email )

                                }else{
                                    $("#email").closest('.form-group').removeClass('has-error').addClass('has-success')
                                }
                            },
                            error: function (err) {
                                console.log(err)
                            }

                        })
                    }
                })

                $('#editForm').validator({
                    custom: {
                        username: function($el) {
                            var matchValue = $el.data("username") // foo
                            if ($el.val() == matchValue) {


                                return "Hey, that's not valid! It's gotta be " + matchValue
                            }
                        },
                        email: function($el) {
                            var matchValue = $el.data("email") // foo
                            if ($el.val() == matchValue) {

                                return "Hey, that's not valid! It's gotta be " + matchValue
                            }
                        },
                        tab_num: function($el) {
                            var matchValue = $el.data("tab_num") // foo
                            if ($el.val() == matchValue) {

                                return "Hey, that's not valid! It's gotta be " + matchValue
                            }
                        }
                    }
                })

            })

            $('#userHistoryTableBody').on('click', '.rolesHistory','button', function(){
                let id = $(this).data("id")
                let arr = []

                $.ajax({
                    url: '/madmin/work-users/get-history-roles/'+id,
                    type: 'GET',
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function(res){

                        $.each(res.models, function(index, value) {
                            arr.push(value.id)
                        })

                        $('#userRolesModalLabel').text("User #"+res.user_id)
                        $('#get_roles').val(arr);
                        $('#get_roles').trigger('change');

                        $('#userRoles').modal('show')

                    },

                    error: function (err) {
                        console.log(err)
                    }
                })
            });

            $("#userHistoryTableBody").on("click", '.activateUser button',function(e){

                let id = $(this).data('id')

                $('.activateUserConfirmed').data('id',id)
                $("#confirmModal").modal('toggle')

            })

            $('.activateUserConfirmed').click(function() {

                $("#confirmModal").modal('toggle')
                $("#loading").show()


                let id = $('.activateUserConfirmed').data('id')

                $.ajax({
                    url: '/madmin/work-users/activate-user/'+id,
                    type: 'GET',
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    beforeSend: function () {

                    },
                    success: function(res){

                        $('#responseModalLabel').text(res.mes)
                        $("#responseModal").modal('toggle')


                        setTimeout(function() {
                            location.reload();
                        }, 1500);

                    },

                    error: function (err) {
                        console.log(err)
                    },
                    complete:function(res){
                        $("#loading").hide()
                    }
                })
            })

            $('.activateUser').unbind().click(function(){
            })

            $('.department_class').on("click",function(e) {

                e.stopPropagation()

                let id = $(this).data("id")
                $('#branch_id').val(id)

                $.ajax({

                    url: '/madmin/users-get-branch/'+id,
                    type: 'GET',
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function(res){
                        let branch = res.branch.branch_code + " - " + res.branch.title
                        $('#branch').text(branch)
                        $('#branch_id_title').val(res.depart.title)

                    },

                    error: function (err) {
                        console.log(err)
                    }
                });

            })

            $('.toggleButton').on('click', function() {

                let div_type = $(this).children('i').data('value')
                let class_name = $(this).children('i')[0].className
                let child = $(this).children('i')

                if(class_name === 'fa fa-minus'){
                    child.removeClass(class_name)
                    child.addClass('fa fa-plus')
                }else{
                    child.removeClass(class_name)
                    child.addClass('fa fa-minus')
                }

                switch (div_type) {
                    // Login & Password div
                    case 1:
                        $('#login_password_div').slideToggle()

                        break;

                    // Personal Info div
                    case 2:
                        $('#personal_info_div').slideToggle()

                        break;

                    // Department div
                    case 3:
                        $('#position_div').slideToggle()

                        break;

                    //
                    case 4:
                        $('#user_history_div').slideToggle()

                        break;

                    // Add position div
                    case 5:
                        $('#add_position_div').slideToggle()

                        break;
                    default:
                        break;
                }


            })

            // Mouse over effects
            $('.department_class').hover(function() {
                $(this).css('cursor','pointer')
                $(this).css({
                    "background-color" : "#e2e2e2",
                    "color":"#505050"
                },1000)
            })

            $(".department_class").mouseout(function(){
                $(this).css({
                    "background-color" : "transparent",
                    "cursor" : "default",
                    "color":"#337ab7"
                })
            })

            $('#addPositionButton').click(function(){
                $('#addPositionBox').slideToggle()
                $('html, body').animate({
                    scrollTop: $("#addPositionBox").offset().top
                }, 1500);
            })

            function formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2)
                    month = '0' + month;
                if (day.length < 2)
                    day = '0' + day;

                return [day, month, year].join('.');
            }


        </script>

    </section>
    <!-- /.content -->
    @endif
@endsection
