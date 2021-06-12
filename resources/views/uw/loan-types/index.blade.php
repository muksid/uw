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
                <div id="loading" class="loading-gif" style="display: none"></div>
                <div class="box-body">
                    <table class="table table-striped table-bordered" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kredit nomi</th>
                                <th>Code</th>
                                <th>Foiz %</th>
                                <th>Davr</th>
                                <th>Imt davr</th>
                                <th>Qarz yuki %</th>
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
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Kredit nomi</label>
                                    <input type="text" class="form-control" id="title" name="title" value="" required="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Kredit turi</label>
                                    <select class="form-control" name="short_code" id="short_code">
                                        <option value="M">(M) Mikroqarz</option>
                                        <option value="I">(I) Iste`mol</option>
                                        <option value="A">(A) Avtokredit</option>
                                        <option value="P">(P) Ipoteka</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Kredit code</label>
                                    <select class="form-control" name="credit_type" id="credit_type">
                                        <option value="32">Mikroqarz</option>
                                        <option value="21">Iste`mol</option>
                                        <option value="34">Avtokredit</option>
                                        <option value="24">Ipoteka</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
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
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Credit davri</label>
                                    <input type="number" class="form-control" id="credit_duration" name="credit_duration" value="" required="">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Credit Imt davri</label>
                                    <input type="number" class="form-control" id="credit_exemtion" name="credit_exemtion" value="" required="">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Foiz %</label>
                                    <input type="number" class="form-control" id="procent" name="procent" value="" required="">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Qarz yuki</label>
                                    <input type="number" class="form-control" id="dept_procent" name="dept_procent" value="" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <!-- checkbox -->
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"  id="isActive" name="isActive" value="1" checked="checked">
                                        <label class="form-check-label">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary pull-left" id="btn-save" value="create"><i class="fa fa-save"></i> @lang('blade.save')
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

    {{--banks modal--}}
    <div id="BanksModal" class="modal fade modal-info" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div id="loanName"></div>
                </div>
                <form id="bankForm" name="bankForm">
                    <input type="text" id="loan_id" name="loan_id" hidden>
                    <div class="modal-body">
                        <div class="box-body no-padding">
                            <div class="mailbox-controls">
                                <!-- Check all button -->
                                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                                </button>
                            </div>
                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover">
                                    <tbody id="exp">
                                    </tbody>
                                    <tbody id="filialsData">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline" id="btn-bank-save">Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            //Enable iCheck plugin for checkboxes
            //iCheck for checkbox and radio inputs
            $('.mailbox-messages input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });

            //Enable check and uncheck all functionality
            $(".checkbox-toggle").click(function () {
                var clicks = $(this).data('clicks');
                if (clicks) {
                    //Uncheck all checkboxes
                    $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                    $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                } else {
                    //Check all checkboxes
                    $(".mailbox-messages input[type='checkbox']").iCheck("check");
                    $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                }
                $(this).data("clicks", !clicks);
            });

        });
    </script>


    <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

    <script src="{{ asset("/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

    <script src="{{ asset("/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
    <!-- Select2 -->
    <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

    <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

    <script src="{{ asset ("/js/moment.min.js") }}"></script>

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
                    { data: 'title', name: 'title', className: 'text-sm', width: "220px"},
                    { data: 'credit_type', name: 'credit_type' },
                    { data: "procent", name: 'procent', className: 'text-primary text-center',
                        render: function (data, type, row) {
                            return data+' %';
                        }
                    },
                    { data: "credit_duration", name: 'credit_duration',
                        render: function (data, type, row) {
                            return data+' oy';
                        }
                    },
                    { data: "credit_exemtion", name: 'credit_exemtion', className: 'text-success text-center',
                        render: function (data, type, row) {
                            return data+' oy';
                        }
                    },
                    { data: 'dept_procent', name: 'dept_procent', className: 'text-maroon text-center',
                        render: function (data, type, row) {
                            return data+' %';
                        }
                     },
                    { data: "isActive", name: 'isActive', className: 'bg-info text-center',
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
                    { data: 'created_at', name: 'created_at',
                        render : function (data,full ) {
                            return moment(data).format('DD.MM.YYYY');
                        }
                    },
                    { data: 'action', name: 'action', orderable: false},
                ],
                order: [[7, 'DESC']]
            });

            $('#add-new-post').click(function () {
                $('#btn-save').val("create-post");
                $('#btn-bank-save').val("bank-store");
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
                    $('#dept_procent').val(data.dept_procent);
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

        $('body').on('click', '#banks-post', function (e) {

            e.preventDefault();

            let id = $(this).data("id");

            $.ajax(
                {
                    type: "GET",
                    url: "{{ url('/uw/get-loan-banks') }}/"+id,
                    beforeSend: function(){
                        $("#loading").show();
                        $("#filialsData").empty();
                        $("#exp").empty();
                    },
                    success: function (data) {
                        //console.log(data)
                        $("#loading").hide();
                        let loanName = '';
                        let dataFilials = '';
                        let exp = '';

                        loanName+='<h4 class="modal-title text-center"><span class="fa fa-credit-card"></span> ' +
                            data.loanName.title+', '+data.loanName.procent+' %, '+
                            data.loanName.credit_duration+' oy, '+
                            data.loanName.dept_procent+' %, '+
                            '</h4>';

                        for (let j = 0; j < data.checkedModel.length; j++){
                            let res = data.checkedModel[j];
                            exp+= '<tr>' +
                                '<td><input type="checkbox" checked name="filial_id[]" value="'+res.id+'">' +
                                '</td>' +
                                '<td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a>' +
                                '</td>' +
                                '<td class="mailbox-subject"><b>'+res.filial_code+'</b> - '+res.title_ru+ '</td>' +
                                '</tr>';
                        }

                        $.each(data.model, function (index, itemData) {

                            dataFilials+=
                                '<tr>' +
                                    '<td><input type="checkbox" name="filial_id[]" value="'+itemData.id+'">' +
                                '</td>' +
                                '<td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a>' +
                                '</td>' +
                                    '<td class="mailbox-subject"><b>'+itemData.filial_code+'</b> - '+itemData.title_ru+ '</td>' +
                                '</tr>';
                        });

                        $("#loanName").html(loanName);
                        $("#exp").append(exp);
                        $("#filialsData").append(dataFilials);
                        $("#loan_id").val(id);
                        //console.log(id);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            $('#BanksModal').data('id', id).modal('show');
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
                        beforeSend: function(){
                            $("#loading").show();
                        },
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

        if ($("#bankForm").length > 0) {
            $("#bankForm").validate({
                submitHandler: function(form) {

                    let actionType = $('#btn-bank-save').val();

                    $('#btn-bank-save').html('Sending..');

                    $.ajax({
                        data: $('#bankForm').serialize(),
                        url: "/uw/store-loan-banks",
                        type: "POST",
                        dataType: 'json',
                        beforeSend: function(){
                            $("#loading").show();
                        },
                        success: function (data) {
                            console.log(data)
                            $("#loading").hide();
                            $('#bankForm').trigger("reset");
                            $('#BanksModal').modal('hide');
                            $('#btn-bank-save').html('Save Changes');
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
