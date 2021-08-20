@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            SDepartments
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">s-departments</a></li>
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

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">

                    <div class="box-header">
                        <div class="col-md-1">
                            <a href="{{ url('/madmin/update-s-departments') }}" class="btn btn-success">
                                <i class="fa fa-refresh"></i> <b> @lang('blade.refresh')</b>
                            </a>
                        </div>
                    </div>

                    <form action="{{url('/madmin/s-departments')}}" method="POST" role="search">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-md-3 col-md-offset-3">
                                <div class="form-group has-success">
                                    <input type="text" class="form-control" name="query" value="{{ $query }}"
                                           placeholder="% CODE, NAME">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <a href="{{url('/madmin/s-departments')}}" class="btn btn-flat border-success">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    <button type="submit" class="btn btn-success btn-flat">
                                        <i class="fa fa-search"></i> @lang('blade.search')
                                    </button>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </form>

                    <div class="box-body">
                        <h5 class="box-title">@lang('blade.overall'): <b>{{ $models->total() }}</b></h5>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>LocalCode</th>
                                <th>Name</th>
                                <th>IsActive</th>
                                <th>Edit</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->code }}</td>
                                    <td>{{ $model->local_code }}</td>
                                    <td>{{ $model->getReplace($model->title??'-')??'-' }}</td>
                                    <td class="text-center">
                                        @if($model->isActive == 'A')
                                            <i class="fa fa-check-circle text-green"></i>
                                        @else
                                            <i class="fa fa-ban text-yellow"></i> {{ $model->isActive }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-info" id="editFilial"
                                                data-id="{{ $model->id }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </td>
                                    <td>{{ $model->updated_at->format('d.M.Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <span class="paginate">{{ $models->links() }}</span>

                    </div>
                    <!-- /.box-body -->
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
                                        <input type="hidden" name="type" id="type" value="d">
                                        <div class="form-group">
                                            <label for="name" class="control-label">@lang('blade.title_uz')</label>
                                            <input type="text" class="form-control" style="width: 100%" id="title"
                                                   name="title" value="" required="">
                                        </div>
                                        <div class="form-group">
                                            <label for="local_code" class="control-label">Local Code</label>
                                            <input type="text" class="form-control" style="width: 100%" id="local_code"
                                                   name="local_code" value="" required="">
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="control-label">@lang('blade.status')</label>
                                            <input type="text" class="form-control" style="width: 100%" id="isActive"
                                                   name="isActive" value="" required="">
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

                $('#createNewFilial').click(function () {

                    $('#btn-save').val("createFilial");

                    $('#filialForm').trigger("reset");

                    $('#modalHeader').html("Add Filial");

                    $('#modalForm').modal('show');
                });

                $('body').on('click', '#editFilial', function () {

                    var model_id = $(this).data('id');

                    $.get('/madmin/get-s-department/' + model_id, function (data) {

                        $('#modalHeader').html("Edit filial");

                        $('#btn-save').val("editFilial");

                        $('#modalForm').modal('show');

                        $('#model_id').val(model_id);

                        $('#title').val(data.title);

                        $('#local_code').val(data.local_code);

                        $('#isActive').val(data.isActive);

                    })
                });

            });

            if ($("#filialForm").length > 0) {

                $("#filialForm").validate({

                    submitHandler: function (form) {

                        var actionType = $('#btn-save').val();

                        $('#btn-save').html('Sending..');

                        $.ajax({
                            data: $('#filialForm').serialize(),

                            url: "{{ url('/madmin/edit-s-department') }}",

                            type: "POST",

                            dataType: 'json',

                            success: function (data) {

                                var model =
                                    '<tr id="rowId_' + data.id + '">' +
                                    '<td>' + data.id + '</td>' +
                                    '<td>' + data.code + '</td>' +
                                    '<td>' + data.local_code + '</td>' +
                                    '<td>' + data.title + '</td>' +
                                    '<td class="text-center">' + data.isActive + '</td>';

                                model +=
                                    '<td class="text-center">' +
                                    '<button type="button" class="btn btn-xs btn-info" id="editFilial" data-id="' + data.id + '">' +
                                    '<i class="fa fa-pencil">' +
                                    '</button>' +
                                    '</td>';
                                model +='<td>' + data.created_at + '</td>';


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

@endsection
