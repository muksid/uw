@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            @lang('blade.create_user')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.users')</a></li>
            <li class="active">@lang('blade.create_user')</li>
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

        <div class="row">

            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">

                        <div class="container h-100">
                            <div class="row h-100 justify-content-center align-items-center">
                                <form class="col-12">
                                    <div class="form-group">
                                        <div class="col-sm-3 col-xs-offset-3">
                                            <input type="search" class="form-control" id="emp_code"
                                                   placeholder="% CARD_NUM, FULL_NAME">
                                        </div>

                                        <div class="col-sm-2">
                                            <button id="search" class="btn btn-success btn-flat"><i
                                                        class="fa fa-search"></i> @lang('blade.search')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>

                        <div id="loading" class="loading-gif" style="display: none"></div>

                        <div class="box-body" style="max-height: 500px; overflow-y: scroll">
                            <b>@lang('blade.overall') @lang('blade.group_edit_count').</b>
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp ID</th>
                                    <th>TabNum</th>
                                    <th>CB_ID</th>
                                    <th>F.I.O.</th>
                                    <th>Holati</th>
                                    <th>Filial</th>
                                    <th>D.Code</th>
                                    <th>D.Nomi</th>
                                    <th>Ish.Joyi</th>
                                    <th>Lavozimi</th>
                                    <th>Lav.Sana</th>
                                </tr>
                                </thead>
                                <tbody class="data-table">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="storeForm" data-bv-live="enabled" role="form" method="POST"
              action="{{ url('/madmin/users/store-new')}}">
            @csrf

            <div class="box box-primary" id="user_info_content">
                <div class="box-body">
                    <div class="col-sm-11">
                        <h3>Personal Info</h3>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group has-feedback" id="usernameFeedback">
                                <label for="username">Username <span class="text-red username">*</span></label>
                                <input type="text" id="username" class="form-control" name="username" data-username=""
                                       placeholder="Login || Username"
                                       value="{{ old('username') }}" autofocus required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block"><b>Must be unique</b></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="l_name" class="control-label">Tabel Number <span
                                            class="text-red">*</span></label>
                                <input type="text" id="cb_id" name="cb_id" hidden>
                                <input type="text" id="tab_num" class="form-control"
                                       readonly>
                                <div class="help-block"><b>Maximum of 15 characters</b></div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group has-feedback">
                                <label for="l_name" class="control-label">Last Name <span
                                            class="text-red">*</span></label>
                                <input type="text" id="l_name" class="form-control" name="l_name" maxlength="15"
                                       placeholder="Last Name" value="{{ old('l_name') }}" required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block"><b>Maximum of 15 characters</b></div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group has-feedback">
                                <label for="f_name" class="control-label">First Name <span
                                            class="text-red">*</span></label>
                                <input type="text" id="f_name" class="form-control" name="f_name" maxlength="15"
                                       placeholder="First Name" value="{{ old('f_name') }}" required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block"><b>Maximum of 15 characters</b></div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group has-feedback">
                                <label for="m_name" class="control-label">Middle Name <span
                                            class="text-red">*</span></label>
                                <input type="text" id="m_name" class="form-control" name="m_name" maxlength="15"
                                       value="{{ old('m_name') }}" placeholder="Middle Name" required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block"><b>Maximum of 15 characters</b></div>
                            </div>
                        </div>

                        <div class="col-sm-11">
                            <h3>Position</h3>
                        </div>

                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group has-feedback">
                                        <label for="branch_id">Branch <span class="text-red">*</span> <span
                                                    id="branch"></span></label>
                                        <input type="text" id="branch_id_title" class="form-control"
                                               placeholder="Pick a branch from department list" disabled required>
                                        <input type="hidden" id="branch_id" class="form-control" name="depart_id"
                                               placeholder="Pick a branch from department list" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Title -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group has-feedback">
                                        <label for="job_title" class="control-label">Job Title <span
                                                    class="text-red">*</span></label>
                                        <input type="text" id="job_title" class="form-control" name="job_title"
                                               maxlength="50" value="{{ old('job_title') }}" placeholder="Job title"
                                               required>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"><b>Maximum of 50 characters</b></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Roles -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group has-feedback">
                                        <label for="roles">Select Roles<span class="text-red">*</span></label>
                                        <select class="form-select select2" id="roles"
                                                aria-label="Default select example" style="width:100%" name="roles[]"
                                                multiple="multiple" required>
                                            @foreach($roles as $role)
                                                @if($role->role_code === 'phy_ins')
                                                    <option value="{{ $role->id }}"
                                                            selected> {{ $role->title }} </option>
                                                @else
                                                    <option value="{{ $role->id }}"> {{ $role->title }} </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block"><b>You must pick at least one role </b></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="form-group text-center">
                        <div id="view_user_button"></div>
                    </div>


                </div>

            </div>

        </form>

        <script>

            $(function () {

                $(".select2").select2();
            });

            $("#loading").hide();

            $("#user_info_content").hide();

            $('#emp_code').keypress(function (event) {

                var keyCode = (event.keyCode ? event.keyCode : event.which);
                if (keyCode === 13) {

                    $('#search').trigger('click');
                    event.preventDefault();

                }
            });

            $("#search").click(function (event) {
                event.preventDefault();
                let emp_code = $('#emp_code').val();

                let str_upper = emp_code.toUpperCase();

                $.ajax({
                    url: '/madmin/ora-emp-search',
                    type: 'GET',
                    data: {emp_code: str_upper},
                    dataType: 'json',
                    beforeSend: function () {
                        $("#loading").show();
                    },
                    success: function (res) {
                        console.log(res)
                        let table = '';
                        let key = 1;
                        let tt = 1;
                        for (let i = 0; i < res.length; i++) {
                            let val = res[i];

                            table +=
                                '<tr>' +
                                '<td>' + key++ + '.</td>' +
                                '<td>' + val.emp_id + '</td>' +
                                '<td>' + val.tab_num + '</td>' +
                                '<td>' + val.cb_id + '</td>' +
                                '<td class="text-green">' + val.last_name + ' ' + val.first_name + ' ' + val.middle_name + ' ' +
                                '<button data-id="' + val.cb_id + '" class="btn btn-xs bg-green-active" id="add_user">' +
                                '<i class="fa fa-plus-circle"></i>Add' +
                                '</button>' +
                                '</td>' +
                                '<td>' + val.condition + '</td>' +
                                '<td>' + val.filial + '</td>' +
                                '<td>' + val.department_code + '</td>' +
                                '<td>' + val.department_name + '</td>' +
                                '<td class="text-sm">' + val.work_dep + '</td>' +
                                '<td class="text-sm">' + val.work_post + '</td>' +
                                '<td>' + formatDate(val.begin_work_date) + '</td>' +
                                '</tr>';
                            tt = val.emp_id
                        }
                        if (res.length <= 0) {

                            table +=
                                '<tr>' +
                                '<td colspan="10" class="text-center text-danger">Xodim topilmadi qaytadan urinib ko`ring!!!</td>' +
                                '</tr>';

                        }

                        $('.data-table').html(table);

                    },
                    complete: function (res) {
                        $("#loading").hide();
                    }

                });

            });

            $('body').on('click', '#add_user', function () {

                let cb_id = $(this).data('id');

                $.ajax({
                    url: '/madmin/get-user-info/' + cb_id,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function () {
                        $("#loading").show();
                    },
                    success: function (res) {

                        console.log(res)

                        $('#user_info_content').show();

                        let button = null;

                        if (res.isUser === 'Yes') {
                            button = '<a href="/madmin/users/' + res.user_id + '/edit" id="view_user_button" class="btn btn-primary">' +
                                '<i class="fa fa-eye-slash"></i> View User' +
                                '</a>';
                        } else {
                            button = '<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> @lang('blade.save')</button>';
                        }
                        $('#view_user_button').empty();

                        $('#view_user_button').append(button);

                        $('#cb_id').val(res.data.cb_id);
                        $('#tab_num').val(res.data.tab_num);
                        $('#l_name').val(res.data.last_name);
                        $('#f_name').val(res.data.first_name);
                        $('#m_name').val(res.data.middle_name);
                        $('#username').val(res.data.filial + res.data.tab_num);
                        $('#branch_id_title').val(res.data.department_name);
                        $('#job_title').val(res.data.work_post);

                    },
                    complete: function () {
                        $("#loading").hide();
                    }

                });
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

@endsection
