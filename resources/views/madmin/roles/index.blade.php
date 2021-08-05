@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Roles
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">roles</a></li>
            <li class="active">index</li>
        </ol>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif

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

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary" style="clear: both;">

                    <div class="box-header with-border">
                        <div class="col-md-1">

                            <button type="button" class="btn btn-flat btn-primary" id="createNewRole">
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
                                <th>@lang('blade.title_uz')</th>
                                <th>@lang('blade.title_ru')</th>
                                <th>@lang('blade.role')</th>
                                <th><i class="fa fa-pencil-square-o"></i></th>
                                <th><i class="fa fa-trash-o"></i></th>
                            </tr>
                            </thead>
                            <tbody id="roleTable">
                            <?php $i = 1 ?>
                                @foreach ($models as $key => $model)
                                    <tr id="rowId_{{ $model->id }}">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $model->title }}</td>
                                        <td>{{ $model->title_ru }}</td>
                                        <td>{{ $model->role_code }}</td>
                                        <td>
                                            <button type="button" class="btn btn-flat btn-info" id="editRole"
                                                    data-id="{{ $model->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-flat btn-danger" id="deleteRole"
                                                    data-id="{{ $model->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
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
                                <form id="roleForm" name="roleForm">
                                    <div class="modal-body">
                                        <input type="hidden" name="model_id" id="model_id">
                                        <div class="form-group">
                                            <label for="name" class="control-label">@lang('blade.title_uz')</label>
                                            <input type="text" class="form-control" style="width: 100%" id="title"
                                                   name="title" value="" required="">
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="control-label">@lang('blade.title_ru')</label>
                                            <input type="text" class="form-control" style="width: 100%" id="titleRu"
                                                   name="title_ru" value="" required="">
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="control-label">@lang('blade.role')</label>
                                            <input type="text" class="form-control" style="width: 100%" id="roleCode"
                                                   name="role_code" value="" required="">
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
                                    <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> Role
                                        serverdan o`chiriladi!</h4>
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
                                        <i class="fa fa-check-circle"></i> <span id="successHeader"></span>
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <h5 id="successBody"></h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline"
                                            data-dismiss="modal">@lang('blade.close')
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

        <script>

            $(function () {
                $("#example1").DataTable();
            });

            // crud form
            $(document).ready(function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#createNewRole').click(function () {

                    $('#btn-save').val("createRole");

                    $('#roleForm').trigger("reset");

                    $('#modalHeader').html("Add role");

                    $('#modalForm').modal('show');
                });

                $('body').on('click', '#editRole', function () {

                    var model_id = $(this).data('id');

                    $.get('/madmin/roles/' + model_id, function (data) {

                        $('#modalHeader').html("Edit role");

                        $('#btn-save').val("editRole");

                        $('#modalForm').modal('show');

                        $('#model_id').val(model_id);

                        $('#title').val(data.title);

                        $('#titleRu').val(data.title_ru);

                        $('#roleCode').val(data.role_code);

                    })
                });

                $('body').on('click', '#deleteRole', function (e) {

                    e.preventDefault();
                    var id = $(this).data("id");

                    $('#ConfirmModal').data('id', id).modal('show');
                });

                $('#yesDelete').click(function () {

                    var id = $('#ConfirmModal').data('id');

                    $.ajax(
                        {
                            type: 'DELETE',
                            url: "{{ url('/madmin/roles') }}" + '/' + id,
                            success: function (data) {
                                $('#successModal').modal('show');
                                $('#successHeader').html(data.success);
                                $('#successBody').html(data.message);
                                if (data.code === 1){
                                    $("#rowId_" + id).remove();
                                }
                            }
                        });

                    $('#ConfirmModal').modal('hide');
                });

            });

            if ($("#roleForm").length > 0) {

                $("#roleForm").validate({

                    submitHandler: function (form) {

                        var actionType = $('#btn-save').val();

                        $('#btn-save').html('Sending..');

                        $.ajax({
                            data: $('#roleForm').serialize(),

                            url: "{{ url('/madmin/roles') }}",

                            type: "POST",

                            dataType: 'json',

                            success: function (data) {

                                var model =
                                    '<tr id="rowId_' + data.id + '">' +
                                    '<td>' + data.id + '</td>' +
                                    '<td>' + data.title + '</td>' +
                                    '<td>' + data.title_ru + '</td>' +
                                    '<td>' + data.role_code + '</td>';

                                model +=
                                    '<td>' +
                                    '<button type="button" class="btn btn-flat btn-info" id="editRole" data-id="' + data.id + '">' +
                                    '<i class="fa fa-pencil">' +
                                    '</button>' +
                                    '</td>';

                                model +=
                                    '<td>' +
                                    '<button type="button" class="btn btn-flat btn-danger" id="deleteRole" data-id="' + data.id + '">' +
                                    '<i class="fa fa-trash">' +
                                    '</button>' +
                                    '</td>' +

                                    '</tr>';


                                if (actionType == "createRole") {

                                    $('#roleTable').prepend(model);

                                } else {

                                    $("#rowId_" + data.id).replaceWith(model);

                                }

                                $('#roleForm').trigger("reset");

                                $('#modalForm').modal('hide');

                                $('#btn-save').html('Save Changes');

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
