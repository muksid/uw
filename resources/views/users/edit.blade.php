<?php ?>
@extends('layouts.table')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.edit_user')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.sidebar_users')</a></li>
            <li class="active">@lang('blade.edit_user')</li>
        </ol>
    </section>

    @if ($user->id == Auth::user()->id)

        <!-- Main content -->
        <section class="content">

            <!-- SELECT2 EXAMPLE -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <!-- Message Succes -->
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                    <!-- Display Validation Errors -->
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Xatolik!</strong> Ma`lumotlarni qaytadan tekshiring.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form role="form" method="POST" action="{{ url('/admin/users/' . $user->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.name') <span style="color:red">*</span></label>
                                    <input type="text" name="fname" class="form-control" value="{{$user->fname}}"
                                           placeholder="Enter first name" required>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.surname') <span style="color:red">*</span></label>
                                    <input type="text" name="lname" class="form-control" value="{{$user->lname}}"
                                           placeholder="Enter last name" required>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.fathers_name') <span style="color:red">*</span></label>
                                    <input type="text" name="sname" class="form-control" value="{{$user->sname}}"
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.tabel_num') (card_num) <span style="color:red">*</span></label>
                                    <input type="text" name="card_num" class="form-control" value="{{$user->card_num}}"
                                           disabled>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">email <span style="color:red">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{$user->email}}"
                                           id="exampleInputEmail1" disabled>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-6">
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label>@lang('blade.position') <span style="color:red">*</span></label>
                                    <input type="text" name="job_title" class="form-control"
                                           value="{{$user->job_title}}" placeholder="Enter job" required>
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
        <!-- Main content -->
        <section class="content">

            <!-- SELECT2 EXAMPLE -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">MFO: {{$user->branch_code}} {{$user->lname}} {{$user->fname}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <!-- Message Succes -->
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                    <!-- Display Validation Errors -->
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>@lang('blade.error')</strong> @lang('blade.error_check')<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form role="form" method="POST" action="{{ url('/admin/users/' . $user->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.surname') <span style="color:red">*</span></label>
                                    <input type="text" name="lname" class="form-control" value="{{$user->lname}}"
                                           placeholder="@lang('blade.ph_surname')" required>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.name') <span style="color:red">*</span> </label>
                                    <input type="text" name="fname" class="form-control" value="{{$user->fname}}"
                                           placeholder="@lang('blade.ph_name')" required>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.fathers_name') <span style="color:red">*</span> </label>
                                    <input type="text" name="sname" class="form-control" value="{{$user->sname}}"
                                           placeholder="@lang('blade.ph_fathers_name')" required>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Username (@lang('blade.username')) <span style="color:red">*</span> </label>
                                    <input type="text" name="username" class="form-control" value="{{$user->username}}"
                                           placeholder="@lang('blade.ph_login')" required>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.tabel_num') (card_num) <span style="color:red">*</span> </label>
                                    <input type="text" name="card_num" class="form-control" value="{{$user->card_num}}"
                                           placeholder="@lang('blade.ph_tabel_num')" required>
                                </div>
                                <!-- /.form-group -->
                            </div>


                            @foreach(json_decode(Auth::user()->roles) as $role)
                                @switch($role)
                                    @case('admin')
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email <span style="color:red">*</span>
                                            </label>
                                            <input type="email" name="email" class="form-control"
                                                   value="{{$user->email}}" id="exampleInputEmail1" required>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('blade.branch') <span style="color:red">*</span> </label>
                                            <select id="filial_code" class="form-control select2" name="branch_code"
                                                    style="width: 100%;">
                                                <option selected="selected" value="{{$user->branch_code}}"
                                                        disabled>{{$user->branch_code . " " . $user->filial->title??''}}
                                                </option>
                                                @foreach($filial as $filial)

                                                    <option value="{{$filial->branch_code}}">
                                                        {{$filial->branch_code. ' ' .$filial->title}}
                                                    </option>

                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group departdiv">
                                            <label>@lang('blade.dep') <span style="color:red">*</span> </label>
                                            <select id="depart" name="depart_id" class="form-control select2"
                                                    style="width: 100%;">
                                                <option selected="selected"
                                                        value="{{ $user->depart_id }}">{{ $user->department->title??'' }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group departdiv2" hidden>
                                            <label>@lang('blade.dep') </label>
                                            <select id="sub_depart" name="depart_id" class="form-control select2">
                                                <option selected="selected" value="{{ $user->depart_id }}"></option>
                                            </select>
                                        </div>
                                        <div class="form-group departdiv3" hidden>
                                            <label>@lang('blade.dep') </label>
                                            <select id="sub_sub_depart" name="depart_id" class="form-control select2">
                                                <option selected="selected" value="{{ $user->depart_id }}"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('blade.role') <span style="color:red">*</span> </label>
                                            <select class="form-control" name="roles[]"
                                                    style="width: 100%; height: 260px" multiple>
                                            @foreach($roles as $role)
                                                <!-- Jamshid selected role 2020-06-10 15:38:50  -->
                                                    @if($user->roles=== "[\"".$role->role_code ."\"]" )
                                                        <option value="{{$role->role_code}}"
                                                                {{ $role->role_code, $role->title ?
                                                                    "selected" : $role->role_code }} selected>
                                                            {{$role->title}}
                                                        </option>
                                                    @else
                                                        <option value="{{$role->role_code}}"
                                                                {{ $role->role_code, $role->title ?
                                                                    "selected" : $role->role_code }}>{{$role->title}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @break
                                    @case('branch_admin')

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email <span style="color:red">*</span>
                                            </label>
                                            <input type="email" name="email" class="form-control"
                                                   value="{{$user->email}}" id="exampleInputEmail1" disabled>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('blade.branch') MFO - {{ $user->filial->title??''}}<span
                                                            style="color:red">*</span> </label>
                                                <input type="text" name="branch_code" class="form-control"
                                                       value="{{$user->branch_code}}" disabled>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Departments ******************************** -->
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('blade.select_section') <span style="color:red">*</span>
                                                </label>
                                                <select id="depart" class="form-control select2" name="depart_id">
                                                    <option selected="selected"
                                                            value="{{$user->depart_id}}">{{$user->title}}</option>
                                                    @foreach($departments as $department)
                                                        <option value="{{$department->id}}">{{$department->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group departdiv2" hidden>
                                                <label>@lang('blade.dep') </label>
                                                <select id="sub_depart" class="form-control select2" name="depart_id">
                                                    <option selected="selected" value="{{ $user->depart_id }}"></option>
                                                </select>
                                            </div>
                                            <div class="form-group departdiv3" hidden>
                                                <label>@lang('blade.dep') </label>
                                                <select id="sub_sub_depart" class="form-control select2"
                                                        name="depart_id">
                                                    <option selected="selected" value="{{ $user->depart_id }}"></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Role -->
                                    <select class="form-control" name="roles[]"
                                            style="width: 100%; height: 110px; display: none;" multiple>
                                        <option value="user" selected></option>
                                    </select>

                                @break
                            @endswitch
                        @endforeach
                        <!-- /.form-group -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.position') <span style="color:red">*</span> </label>
                                    <input type="text" name="job_title" class="form-control"
                                           value="{{$user->job_title}}" placeholder="@lang('blade.ph_position')"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{{ $errors->has('job_date') ? ' has-error' : '' }}">
                                    <label>@lang('blade.position_date') <span style="color:red">*</span> </label>
                                    <input type="date" name="job_date" class="form-control"
                                           placeholder="@lang('blade.position_date')" value="{{ $user->job_date }}"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('blade.sort') <span style="color:red">*</span> </label>
                                    <input type="number" min="0" max="254" name="user_sort" class="form-control"
                                           placeholder="sort number" value="{{$user->user_sort}}" maxlength="3"
                                           required>
                                </div>
                                <!-- /.form-group -->
                            </div>

                            <div class="col-md-12">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('blade.status') <span style="color:red">*</span> </label>
                                            <select class="form-control " name="status">
                                                <option selected="selected" value="{{$user->status}}">@if($user->status == 1)
                                                        Active @else Passive @endif </option>
                                                <option value="1">Active</option>
                                                <option value="0">Passive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="exampleInputPassword1">@lang('blade.password') <span
                                                        style="color:red">*</span> </label>
                                            <input type="password" name="password" class="form-control" id="inputError"
                                                   placeholder="@lang('blade.password')">
                                            <span class="help-block">@lang('blade.enter_pass_at_least_6')</span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            <label for="exampleInputPassword1">@lang('blade.repeat_pass') <span
                                                        style="color:red">*</span> </label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                   id="inputError" placeholder="@lang('blade.password')">
                                            <span class="help-block">@lang('blade.repeat_pass')</span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <a href="/admin/users" class="btn btn-default">@lang('blade.cancel') </a>
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

        @endif

            <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

            <script type="text/javascript">
                $(document).ready(function () {
                    $("#loding1").hide();
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $("#filial_code").change(function () {
                        $("#loding1").show();
                        var branch_code = $(this).val();
                        $.ajax({
                            type: "POST",
                            url: "/get-department",
                            data: {_token: CSRF_TOKEN, branch_code: branch_code},
                            dataType: 'JSON',
                            success: function (res) {
                                var user_input = "";
                                var obj = res;

                                if (res) {
                                    $("#loding1").hide();
                                    $("#depart").empty();
                                    if (obj['msg'] != 0) {
                                        $('.departdiv').show();
                                        $('.departdiv2').hide();
                                        $('.departdiv3').hide();
                                        $("#depart").append('<option value=' + res.req + ' disabled selected> Departamentni tanlang </option>');
                                        $("#sub_depart").append('<option value=' + res.req + ' selected></option>');
                                        $("#sub_sub_depart").append('<option value=' + res.req + ' selected></option>');

                                        $.each(obj['msg'], function (key, val) {
                                            user_input += '<option value="' + val.id + '"> --- ' + val.title + '</option>';
                                        });
                                    } else {
                                        $('.departdiv').hide();
                                    }
                                    $("#depart").append(user_input); //// For Append
                                }
                            },

                            error: function () {
                                console.log('error');
                            }
                        });
                    }); // End of #filial_code
                    $("#depart").change(function () {
                        $("#loding1").show();
                        var dataString = $(this).val();
                        $.ajax({
                            type: "POST",
                            url: "/postbranch",
                            data: {_token: CSRF_TOKEN, name: dataString},
                            dataType: 'JSON',
                            success: function (res) {
                                var user_input = "";
                                var obj = res;

                                if (res) {
                                    $("#loding1").hide();
                                    $("#sub_depart").empty();
                                    $("#sub_depart").append('<option value=' + res.req + ' disabled selected> Sub Departamentni tanlang</option>');
                                    $("#sub_sub_depart").append('<option value=' + res.req + ' selected> Sub Departamentni tanlang </option>');
                                    if (obj['msg'] != 0) {
                                        $('.departdiv2').show();
                                        $('.departdiv3').hide();
                                        $.each(obj['msg'], function (key, val) {
                                            user_input += '<option value="' + val.id + '">' + val.title + '</option>';

                                        });
                                    } else {
                                        $('.departdiv2').hide();
                                        $('.departdiv3').hide();
                                    }
                                    $("#sub_depart").append(user_input); //// For Append
                                }
                            },
                            error: function () {
                                console.log('error');
                            }
                        });
                    }); // End Of #depart
                    $("#sub_depart").change(function () {
                        $("#loding1").show();
                        var dataString = $(this).val();
                        $.ajax({
                            type: "POST",
                            url: "/postbranch",
                            data: {_token: CSRF_TOKEN, name: dataString},
                            dataType: 'JSON',
                            success: function (res) {
                                var user_input = "";
                                var obj = res;

                                if (res) {
                                    $("#loding1").hide();
                                    $("#sub_sub_depart").empty();
                                    $("#sub_sub_depart").append('<option  value=' + res.req + ' disabled selected> Sub Sub Departamentni tanlang </option>');

                                    if (obj['msg'] != 0) {
                                        $('.departdiv3').show();
                                        $.each(obj['msg'], function (key, val) {
                                            user_input += '<option value="' + val.id + '">' + val.title + '</option>';
                                        });
                                    } else {
                                        $('.departdiv3').hide();
                                    }

                                    $("#sub_sub_depart").append(user_input); //// For Append
                                }
                            },
                            error: function () {
                                console.log('error');
                            }
                        });
                    }); // End #sub_depart
                }); //End of $(document).ready(function ()


            </script>

        </section>


@endsection