@extends('layouts.uw.dashboard')

<!-- Select2 -->
<link href="{{ asset("/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css">

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Uw Kredit turlari
        <small>jadval</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
        <li><a href="#">uw loan type</a></li>
        <li class="active">uw loan type</li>
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
    <div id="loading-gif" class="loading-gif" style="display: none"></div>
    <div class="row">
        <div class="col-xs-12">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <div class="col-md-1">
                        <a href="javascript:void(0)" class="btn btn-info ml-3" id="add-new-post"><i class="fa fa-plus"></i> @lang('blade.add')</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-striped table-bordered" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Credit type</th>
                                <th>Procent</th>
                                <th>Credit Dur</th>
                                <th>Credit Exam</th>
                                <th>Currency</th>
                                <th>Short Code</th>
                                <th>IsActive</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>

    <div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="postCrudModal"></h4>
                </div>
                <div class="modal-body">
                    <form id="postForm" name="postForm">
                        <input type="hidden" name="post_id" id="post_id">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="" required="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Title (Short)</label>
                                    <select class="form-control" name="short_code" id="short_code">
                                        <option value="M">(M) Mikroqarz</option>
                                        <option value="I">(I) Iste`mol</option>
                                        <option value="A">(A) Avtokredit</option>
                                        <option value="P">(P) Ipoteka</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Credit Type</label>
                                    <select class="form-control" name="credit_type" id="credit_type">
                                        <option value="32">Mikroqarz</option>
                                        <option value="21">Iste`mol</option>
                                        <option value="34">Avtokredit</option>
                                        <option value="24">Ipoteka</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Credit davri</label>
                                    <input type="number" class="form-control" id="credit_duration" name="credit_duration" value="" required="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Credit Imt davri</label>
                                    <input type="number" class="form-control" id="credit_exemtion" name="credit_exemtion" value="" required="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Valyuta</label>
                                    <select class="form-control" name="currency" id="currency">
                                        <option value="000" SELECTED>UZB (000)</option>
                                        <option value="840">USD (840)</option>
                                        <option value="978">EUR (978)</option>
                                        <option value="392">JPY (392)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Percent</label>
                                    <input type="number" class="form-control" id="procent" name="procent" value="" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <!-- checkbox -->
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"  id="isActive" name="isActive" value="1" checked="checked">
                                        <label class="form-check-label">Checkbox checked</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary pull-left" id="btn-save" value="create"><i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
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

    {{--delete modal--}}
    <div id="MessageModal" class="modal fade modal-warning" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">O`chirishda xatolik yuz berdi!</h4>
                </div>

                <div class="modal-body">
                    <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> <span id="message"></span></h4>
                </div>

                <div class="modal-footer">
                    <center>
                        <button type="button" class="btn btn-outline pull-right"
                                data-dismiss="modal">@lang('blade.close')</button>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

    <script src="{{ asset("/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

    <script src="{{ asset("/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
    <!-- Select2 -->
    <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

    <script src="{{ asset ("/js/jquery.validate.js") }}"></script>
    <style>
        .loading-gif {
            background: url({{asset('images/loading-3.gif')}}) no-repeat center center;
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            z-index: 9999999;
            opacity: 0.5;
        }
    </style>
    <script>
        $(document).ready( function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#laravel_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('uw-loan-types.index') }}",
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id', 'visible': true},
                    { data: 'title', name: 'title'},
                    { data: 'credit_type', name: 'credit_type' },
                    { data: 'procent', name: 'procent' },
                    { data: 'credit_duration', name: 'credit_duration' },
                    { data: 'credit_exemtion', name: 'credit_exemtion' },
                    { data: 'currency', name: 'currency' },
                    { data: 'short_code', name: 'short_code' },
                    { data: "isActive", name: 'isActive', className: 'bg-info',
                        render: function (data, type, row) {
                            if (data === 1) {
                                return '<i class="fa fa-check-circle-o text-success"></i>';
                            }
                            if (data === 0) {
                                return '<i class="fa fa-ban text-danger"></i>';
                            }
                            return 'None';
                        }
                    },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#post_id').val('');
                $('#postForm').trigger("reset");
                $('#postCrudModal').html("Add Loan");
                $('#ajax-crud-modal').modal('show');
            });


            $('body').on('click', '.edit-post', function () {
                var post_id = $(this).data('id');
                $.get('uw-loan-types/'+post_id+'/edit', function (data) {
                    $('#name-error').hide();
                    $('#email-error').hide();
                    $('#postCrudModal').html("Edit Loan");
                    $('#btn-save').val("edit-post");
                    $('#ajax-crud-modal').modal('show');
                    $('#post_id').val(data.id);
                    $('#title').val(data.title);
                    $('#credit_type').val(data.credit_type);
                    $('#procent').val(data.procent);
                    $('#credit_duration').val(data.credit_duration);
                    $('#credit_exemtion').val(data.credit_exemtion);
                    $('#currency').val(data.currency);
                    $('#short_code').val(data.short_code);
                    $('#isActive').val(data.isActive);
                })
            });

            $('body').on('click', '#delete-post', function (e) {

                e.preventDefault();
                var id = $(this).data("id");

                $('#ConfirmModal').data('id', id).modal('show');
            });

            $('#yesDelete').click(function () {
                var id = $('#ConfirmModal').data('id');
                $.ajax(
                    {
                        type: "DELETE",
                        url: "{{ url('uw-loan-types') }}/"+id,
                        success: function (data) {
                            console.log(data);
                            if (data.code === 200){
                                var oTable = $('#laravel_datatable').dataTable();
                                oTable.fnDraw(false);
                            }
                            else if(data.code === 201){
                                $('#MessageModal').modal('show');
                                $('#message').html(data.message);
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });

                $('#ConfirmModal').modal('hide');
            });

            $('body').on('click', '#passive-post', function (e) {

                e.preventDefault();
                var id = $(this).data("id");

                $.ajax(
                    {
                        type: "GET",
                        url: "{{ url('uw-loan-types') }}/"+id,
                        beforeSend: function(){

                            $(".inquiry-individual").prop('disabled', true);
                            $("#loading-gif").show();

                        },
                        success: function (data) {
                            $("#loading-gif").hide();
                            //console.log(data);
                            var oTable = $('#laravel_datatable').dataTable();
                            oTable.fnDraw(false);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
            });

        });

        if ($("#postForm").length > 0) {
            $("#postForm").validate({
                submitHandler: function(form) {
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');

                    $.ajax({
                        data: $('#postForm').serialize(),
                        url: "{{ route('uw-loan-types.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#postForm').trigger("reset");
                            $('#ajax-crud-modal').modal('hide');
                            $('#btn-save').html('Save Changes');
                            var oTable = $('#laravel_datatable').dataTable();
                            oTable.fnDraw(false);
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