@extends('uw_log.uw.dashboard')

<!-- Select2 -->
<link href="{{ asset("/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css">

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Uw Users
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">uw users</a></li>
            <li class="active">uw users</li>
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
            <div class="col-xs-12">

                <div class="box box-primary">

                    <div class="box-header with-border">
                        <div class="col-md-1">

                            <button type="button" class="btn btn-flat btn-primary" id="createNewUser">
                                <i class="fa fa-plus"></i> @lang('blade.add')
                            </button>

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Filial</th>
                                <th>Sub filial (BXO)</th>
                                <th>Role</th>
                                <th>login</th>
                                <th>FIO</th>
                                <th>Job</th>
                                <th>status</th>
                                <th><i class="fa fa-pencil-square-o"></i></th>
                                <th><i class="fa fa-trash-o"></i></th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody id="userTable">
                            <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr id="rowId_{{ $model->id }}">
                                    <td>{{ $i++ }}</td>
                                    <td class="text-sm">{{ $model->filial->filial->title??'' }}</td>
                                    <td>{{ $model->bxo->title??'' }}</td>
                                    <td>
                                        @if($model->role->role_code == 'super_admin')
                                            <span class="label label-info">
                                                    {{ $model->role->title??'' }}
                                                </span>
                                        @elseif($model->role->role_code == 'risk_adminstrator')
                                            <span class="label label-success">
                                                    {{ $model->role->title??'' }}
                                                </span>
                                        @elseif($model->role->role_code == 'risk_user')
                                            <span class="label label-primary">
                                                    {{ $model->role->title??'' }}
                                                </span>
                                        @else
                                            <span class="label label-default">
                                                    {{ $model->role->title??'' }}
                                                </span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $model->user->username??'' }}</td>
                                    <td>{{ $model->user->lname??'' }} {{ $model->user->fname??'' }}</td>
                                    <td>{{ $model->user->job_title??'' }}</td>
                                    <td class="text-center">
                                        @switch($model->status)
                                            @case(0)
                                            <span class="label label-warning">passive</span>
                                            @break
                                            @case(1)
                                            <span class="label label-success">active</span>
                                            @break
                                            @case(2)
                                            <span class="label label-danger">deleted</span>
                                            @break
                                            @default
                                            <span class="label label-default">unknown</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-flat btn-info" id="editUser"
                                                data-id="{{ $model->id }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-flat btn-danger" id="deleteUser"
                                                data-id="{{ $model->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($model->created_at)->format('d M, Y H:i')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{--create/updte modal--}}
                    <div class="modal fade modal-primary" id="modalForm" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="modalHeader"></h4>
                                </div>
                                <form id="userForm" name="userForm">
                                    <div class="modal-body">
                                        <input type="hidden" name="model_id" id="model_id">

                                        <div class="form-group">
                                            <label>User: <span id="user_name" class="text-black"></span> <span style="color:red">*</span> </label>
                                            <select id="user_id" class="form-control select2" name="user_id"
                                                    style="width: 100%;">
                                                <option selected value>@lang('blade.select_users')</option>
                                                @foreach($users as $value)
                                                    <option value="{{ $value->id }}">{{$value->branch_code. ' ' .$value->lname. ' ' .$value->fname. ' ' .$value->job_title}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('blade.branch'): <span id="filial_name" class="text-black"></span> <span style="color:red">*</span> </label>
                                            <select id="filial_id" class="form-control select2" name="filial_id"
                                                    style="width: 100%;">
                                                <option selected value>@lang('blade.select_branch')</option>
                                                @foreach($filials as $filial)
                                                    <option value="{{ $filial->id }}">{{$filial->filial_code. ' ' .$filial->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Role: <span id="role_name" class="text-black"></span> <span style="color:red">*</span> </label>
                                            <select id="role_id" class="form-control select2" name="role_id"
                                                    style="width: 100%;">
                                                <option selected value>Select role</option>
                                                @foreach($roles as $value)
                                                    <option value="{{ $value->id }}">{{$value->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('blade.status') <span style="color:red">*</span> </label>
                                            <select id="status" class="form-control select2" name="status"
                                                    style="width: 100%;">
                                                <option selected value="1">Active</option>
                                                <option value="0">Passive</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left"
                                                data-dismiss="modal">@lang('blade.cancel')</button>
                                        <button type="submit" class="btn btn-outline" id="btn-save"
                                                value="create">@lang('blade.save')
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{--delete modal--}}
                    <div id="ConfirmModal" class="modal fade modal-danger" role="dialog">
                        <div class="modal-dialog modal-sm">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                                </div>

                                <div class="modal-body">
                                    <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> User serverdan o`chiriladi!</h4>
                                </div>

                                <div class="modal-footer">
                                    <center>
                                        <button type="button" class="btn btn-outline pull-left"
                                                data-dismiss="modal">@lang('blade.cancel')</button>
                                        <button type="button" class="btn btn-outline" id="yesDelete"
                                                value="create">Ha, O`chirish
                                        </button>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade modal-success" id="successModal" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <h4 class="modal-title">
                                        User <i class="fa fa-check-circle"></i>
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <h4><span id="res_message"></span></h4>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" data-dismiss="modal">@lang('blade.close')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade modal-warning" id="warningModal" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <h4 class="modal-title">
                                        User <i class="fa fa-user"></i>
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <h5 id="errorMessage"></h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" data-dismiss="modal">@lang('blade.close')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
        <!-- Select2 -->
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

        <script>

            $(function () {
                $("#example1").DataTable();

                $(".select2").select2();
            });

            // crud form
            $(document).ready(function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#createNewUser').click(function () {

                    $('#btn-save').val("createUser");

                    $('#userForm').trigger("reset");

                    $('#modalHeader').html("Add user");

                    $('#modalForm').modal('show');
                });

                $('body').on('click', '#editUser', function () {

                    var model_id = $(this).data('id');

                    $.get('/uw/uw-users/' + model_id, function (data) {
                        //console.log(data);
                        $('#modalHeader').html("Edit User");
                        $('#btn-save').val("editUser");
                        $('#modalForm').modal('show');
                        $('#user_name').html(data.user_name);
                        $('#filial_name').html(data.filial_name);
                        $('#role_name').html(data.role_name);
                        $('#model_id').val(model_id);
                        $('#user_id').val(data.user_id);
                        $('#filial_id').val(data.filial_id);
                        $('#role_id').val(data.role_id);
                        $('#status').val(data.status);
                    })
                });

                $('body').on('click', '#deleteUser', function (e) {

                    e.preventDefault();
                    var id = $(this).data("id");

                    $('#ConfirmModal').data('id', id).modal('show');
                });

                $('#yesDelete').click(function () {

                    var token = $('meta[name="csrf-token"]').attr('content');

                    var id = $('#ConfirmModal').data('id');

                    $('#rowId_'+id).remove();

                    $.ajax(
                        {
                            type: 'DELETE',
                            url: "{{ url('uw/uw-users') }}"+'/'+id,
                            success: function (data)
                            {
                                console.log(data);
                                $('#successModal').modal('show');
                                $('#res_message').html(data.success);
                                $("#rowId_" + id).remove();
                            }
                        });

                    $('#ConfirmModal').modal('hide');
                });

            });

            if ($("#userForm").length > 0) {

                $("#userForm").validate({

                    submitHandler: function (form) {

                        var actionType = $('#btn-save').val();

                        $('#btn-save').html('Sending..');

                        $.ajax({
                            data: $('#userForm').serialize(),

                            url: "{{ url('uw/uw-users') }}",

                            type: "POST",

                            dataType: 'json',

                            success: function (data) {
                                console.log(data);
                                if (data.error){
                                    $('#errorMessage').html(data.error);
                                    $('#userForm').trigger("reset");
                                    $('#modalForm').modal('hide');
                                    $('#warningModal').modal('show');

                                } else {
                                    var model =
                                        '<tr id="rowId_' + data.id + '">' +
                                        '<td>' + data.id + '</td>' +
                                        '<td>' + data.filial_name + '</td>' +
                                        '<td>' + data.bxo_name + '</td>' +
                                        '<td>' +
                                        '<span class="label label-primary">'+ data.role_name +'</span>' +
                                        '</td>'+
                                        '<td>' + data.user_name + '</td>'+
                                        '<td>' + data.job_name + '</td>'+
                                        '<td class="text-center">' + data.status + '</td>';
                                    model +=
                                        '<td>' +
                                        '<button type="button" class="btn btn-flat btn-info" id="editUser" data-id="' + data.id + '">' +
                                        '<i class="fa fa-pencil">' +
                                        '</button>' +
                                        '</td>';

                                    model +=
                                        '<td>' +
                                        '<button type="button" class="btn btn-flat btn-danger" id="deleteUser" data-id="' + data.id + '">' +
                                        '<i class="fa fa-trash">' +
                                        '</button>' +
                                        '</td>' +

                                        '<td>' + data.created_at + '</td>' +

                                        '</tr>';

                                    if (actionType == "createUser") {

                                        $('#userTable').prepend(model);

                                    } else {

                                        $("#rowId_" + data.id).replaceWith(model);

                                    }

                                    $('#userForm').trigger("reset");

                                    $('#modalForm').modal('hide');

                                    $('#btn-save').html('Save Changes');

                                }

                            },
                            error: function (data) {
                                console.log('Error:', data);
                                $('#btn-save').html('Save Changes');
                            }
                        });
                    }
                })
            }
        </script>
    </section>
    <!-- /.content -->
@endsection
