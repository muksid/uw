@extends('layouts.uw.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Filial
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">Filial</a></li>
            <li class="active">Filial</li>
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
                                <th>Filial code</th>
                                <th>Local code</th>
                                <th>Parent</th>
                                <th>status</th>
                                <th><i class="fa fa-pencil-square-o"></i></th>
                                {{--<th><i class="fa fa-trash-o"></i></th>--}}
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody id="filialTable">
                            <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr id="rowId_{{ $model->id }}">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->title }}</td>
                                    <td>{{ $model->filial_code }}</td>
                                    <td>{{ $model->local_code }}</td>
                                    <td>{{ $model->filial->title??'' }}</td>
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
                                        <button type="button" class="btn btn-flat btn-info" id="editFilial"
                                                data-id="{{ $model->id }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </td>
                                    {{--<td>
                                        <button type="button" class="btn btn-flat btn-danger" id="deleteFilial"
                                                data-id="{{ $model->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>--}}
                                    <td>{{ \Carbon\Carbon::parse($model->created_at)->format('d.m.Y H:i')}}</td>
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
                                <form id="filialForm" name="filialForm">
                                    <div class="modal-body">
                                        <input type="hidden" name="model_id" id="model_id">
                                        <div class="form-group">
                                            <label for="title" class="control-label">@lang('blade.title_uz')</label>
                                            <input type="text" class="form-control" style="width: 100%" id="title"
                                                   name="title" value="" required="">
                                        </div>
                                        <div class="form-group">
                                            <label for="title_ru" class="control-label">@lang('blade.title_ru')</label>
                                            <input type="text" class="form-control" style="width: 100%" id="title_ru"
                                                   name="title_ru" value="" required="">
                                        </div>

                                        <div class="form-group">
                                            <label for="filial_code" class="control-label">Filial code</label>
                                            <input type="number" class="form-control" style="width: 100%" id="filial_code"
                                                   name="filial_code" value="" required="">
                                        </div>

                                        <div class="form-group">
                                            <label for="local_code" class="control-label">Local code</label>
                                            <input type="text" class="form-control" style="width: 100%" id="local_code"
                                                   name="local_code" value="" required="">
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('blade.branch') <span style="color:red">*</span> </label>
                                            <select id="parent_id" class="form-control select2" name="parent_id"
                                                    style="width: 100%;">
                                                <option selected value>@lang('blade.select_branch')</option>
                                                @foreach($parent as $filial)
                                                    <option value="{{ $filial->id }}">{{$filial->filial_code. ' ' .$filial->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="control-label">Sort</label>
                                            <input type="number" class="form-control" style="width: 100%" id="f_sort"
                                                   name="f_sort" value="1" required="">
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
                                    <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> Filial serverdan o`chiriladi!</h4>
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
                                        Filial <i class="fa fa-check-circle"></i>
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <h5>Filial Successfully deleted</h5>
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

        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

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

                    $('#btn-save').val("createFilial");

                    $('#filialForm').trigger("reset");

                    $('#modalHeader').html("Add role");

                    $('#modalForm').modal('show');
                });

                $('body').on('click', '#editFilial', function () {

                    var model_id = $(this).data('id');

                    $.get('/uw/filials/' + model_id, function (data) {
                        $('#modalHeader').html("Edit Filial");
                        $('#btn-save').val("editFilial");
                        $('#modalForm').modal('show');
                        $('#model_id').val(model_id);
                        $('#title').val(data.title);
                        $('#title_ru').val(data.title_ru);
                        $('#filial_code').val(data.filial_code);
                        $('#local_code').val(data.local_code);
                        $('#parent_id').val(data.parent_id);
                        $('#f_sort').val(data.f_sort);
                        $('#status').val(data.status);
                    })
                });

                $('body').on('click', '#deleteFilial', function (e) {

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
                            url: "{{ url('uw/filials') }}"+'/'+id,
                            success: function (data)
                            {
                                $('#successModal').modal('show');
                                $("#rowId_" + id).remove();
                            }
                        });

                    $('#ConfirmModal').modal('hide');
                });

            });

            if ($("#filialForm").length > 0) {

                $("#filialForm").validate({

                    submitHandler: function (form) {

                        var actionType = $('#btn-save').val();

                        $('#btn-save').html('Sending..');

                        $.ajax({
                            data: $('#filialForm').serialize(),

                            url: "{{ url('uw/filials') }}",

                            type: "POST",

                            dataType: 'json',

                            success: function (data) {

                                var model =
                                    '<tr id="rowId_' + data.id + '">' +
                                        '<td>' + data.id + '</td>' +
                                        '<td>' + data.title + '</td>' +
                                        '<td>' + data.filial_code + '</td>' +
                                        '<td>' + data.local_code + '</td>' +
                                        '<td>' + data.parent_id + '</td>'+
                                        '<td>' + data.status + '</td>'+
                                        '<td><button type="button" class="btn btn-flat btn-info" id="editFilial" data-id="' + data.id + '">' +
                                                '<i class="fa fa-pencil">' +
                                            '</button>' +
                                        '</td>'+
                                        '<td>' + data.created_at + '</td>' +
                                    '</tr>';


                                if (actionType == "createFilial") {

                                    $('#filialTable').prepend(model);

                                } else {

                                    $("#rowId_" + data.id).replaceWith(model);

                                }

                                $('#filialForm').trigger("reset");

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
