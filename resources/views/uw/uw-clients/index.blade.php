@extends('layouts.uw.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Underwriter Arizalar
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">underwriter</a></li>
            <li class="active">underwriter</li>
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

                <div class="box box-primary" style="clear: both;">

                    <div class="box-header with-border">
                        <div class="col-md-1">

                            <a href="{{ url('uw/clients') }}" class="btn btn-flat btn-primary">
                                <i class="fa fa-plus"></i> @lang('blade.add')</a>

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kredit Turi</th>
                                <th>IABS unikal</th>
                                <th>Ariza #</th>
                                <th>Mijoz nomi</th>
                                <th>Kredit summasi</th>
                                <th class="text-center">@lang('blade.status')</th>
                                <th class="text-center">KATM qarzdorligi</th>
                                <th class="text-center">Ko`rish</th>
                                <th><i class="fa fa-pencil-square-o"></i></th>
                                <th><i class="fa fa-trash-o"></i></th>
                                <th>Sanasi</th>
                            </tr>
                            </thead>
                            <tbody id="roleTable">
                            <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr id="rowId_{{ $model->id }}">
                                    <td>{{ $i++ }}</td>
                                    <td style="max-width: 100px;">
                                        @php($fa = '')
                                        @if($model->loanType->credit_type == 32)
                                            @php($fa = 'credit-card')
                                        @elseif($model->loanType->credit_type == 21)
                                            @php($fa = 'desktop')
                                        @elseif($model->loanType->credit_type == 34)
                                            @php($fa = 'car')
                                        @elseif($model->loanType->credit_type == 24)
                                            @php($fa = 'home')
                                        @endif
                                        <span class="text-sm text-green"><i class="fa fa-{{ $fa }} text-maroon"></i> {{ $model->loanType->title??'' }}</span>
                                    </td>
                                    <td>{{ $model->iabs_num }}</td>
                                    <td>{{ $model->claim_id }}</td>
                                    <td>{{ $model->family_name. ' '.$model->name. ' '.$model->patronymic}}</td>
                                    <td>{{ number_format($model->summa, 2) }}</td>
                                    <td>
                                        @if($model->status == 0)
                                            <span class="badge bg-red-active">Bekor qilingan</span>
                                            @elseif($model->status == 1)
                                            <span class="badge bg-yellow-active">Yangi</span>
                                            @elseif($model->status == 2)
                                            <span class="badge bg-light-blue">Yuborilgan</span>
                                            @elseif($model->status == 3)
                                            <span class="badge bg-aqua-active">Tasdiqlangan</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($summ = $model->katm->katm_summ??'')
                                            @if($summ == 0)
                                                <p class="text-green text-bold">{{ number_format(0, 2) }}</p>
                                                @else
                                                <p class="text-maroon text-bold">{{ number_format($model->katm->katm_summ, 2) }}</p>
                                            @endif

                                            @else
                                            <p class="text-green text-bold">{{ number_format(0, 2) }}</p>
                                        @endif

                                    </td>
                                    {{--<td>
                                        <a class="btn btn-flat btn-primary" href="{{ route('uw-katm',
                                                    ['id' => $model->id,
                                                    'claim_id' => $model->claim_id]) }}">
                                            <i class="fa fa-eye-slash"></i> Ko`rish
                                        </a>
                                    </td>--}}
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('uw.create.step.result',
                                                    ['id' => $model->id]) }}">
                                            <i class="fa fa-eye-slash"></i> Ko`rish
                                        </a>
                                    </td>
                                    @if($model->status == 1 || $model->status == 0)
                                    <td>
                                        <button type="button" class="btn btn-flat btn-info" id="editClient"
                                                data-id="{{ $model->id }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-flat btn-danger disabled"
                                                data-id="{{ $model->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                        @else
                                        <td>
                                            <button type="button" class="btn btn-flat btn-info disabled"
                                                    data-id="{{ $model->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-flat btn-danger disabled"
                                                    data-id="{{ $model->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    @endif
                                    <td>
                                        {{ \Carbon\Carbon::parse($model->created_at)->format('d.m.Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal fade" id="resultKATMModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" style="width: auto; max-width: 1100px">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title text-center" id="success">Mijozning kredit tarixi (KATM)</h4>
                                </div>
                                <div id="reportBase64Modal"></div>
                                <form id="roleForm14" name="roleForm14">
                                    <div class="modal-body">
                                        <input type="hidden" name="claim_id" id="katmClaimId">
                                        <input type="hidden" name="katmSumm" id="katmSumm" value="">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-flat pull-right btn-default" data-dismiss="modal">@lang('blade.close')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{--create/updte modal--}}
                    <div class="modal fade" id="modalForm" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="modalHeader"></h4>
                                </div>
                                <form id="roleForm" name="roleForm">
                                    <div class="modal-body">
                                        <div class="row">
                                            <input type="hidden" name="model_id" id="model_id">
                                            <div class="col-md-12">

                                                <div class="row">

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Familya</label>
                                                            <input type="text" id="family_name" name="family_name"
                                                                   class="form-control latin-only" value="" required>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Ismi<span class=""></span></label>
                                                            <input type="text" id="name" name="name"
                                                                   class="form-control latin-only" value="" required>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group {{ $errors->has('patronymic') ? 'has-error' : '' }}">
                                                            <label>Otasining ismi<span class=""></span></label>
                                                            <input type="text" id="patronymic" name="patronymic"
                                                                   class="form-control latin-only" value="">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Tug`ilgan sana</label>
                                                            <div class="input-group date">
                                                                <div class="input-group">
                                                                    <input type="date" name="birth_date" id="birth_date"
                                                                           value="" class="form-control" required/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Jinsi <span class="text-red">*</span></label>
                                                            <select id="gender" class="form-control" name="gender">
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="name" class="control-label">Passport AA</label>
                                                            <input type="text" class="form-control" style="width: 100%"
                                                                   id="document_serial"
                                                                   name="document_serial" value="" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="name" class="control-label">Raqami</label>
                                                            <input type="number" class="form-control"
                                                                   style="width: 100%" id="document_number"
                                                                   name="document_number" value="" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Berilgan sana</label><sup class="text-red">
                                                                *</sup>
                                                            <input type="date" name="document_date" id="document_date"
                                                                   value="" class="form-control" required/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="name" class="control-label">INPS</label>
                                                            <input type="number" class="form-control"
                                                                   style="width: 100%" id="pin"
                                                                   name="pin" value="" required="">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="name" class="control-label">Inn</label>
                                                            <input type="number" class="form-control"
                                                                   style="width: 100%" id="inn"
                                                                   name="inn" value="" required="">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>INPS tanlang <span class="text-red">*</span></label>
                                                            <select id="is_inps" class="form-control" name="is_inps">
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="name" class="control-label">Kredit Summa</label>
                                                            <input type="number" class="form-control"
                                                                   style="width: 100%" id="summa"
                                                                   name="summa" value="" required="">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="iabs_num" class="control-label">IABS Num</label>
                                                            <input type="number" class="form-control"
                                                                   style="width: 100%" id="iabs_num"
                                                                   name="iabs_num" value="">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="registration_address" class="control-label">Yashash manzili</label>
                                                            <input type="text" class="form-control"
                                                                   style="width: 100%" id="registration_address"
                                                                   name="registration_address" value="" required="">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="job_address" class="control-label">Ish joy manzili</label>
                                                            <input type="text" class="form-control"
                                                                   style="width: 100%" id="job_address"
                                                                   name="job_address" value="" required="">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Kredit turi<span class="text-red">*</span></label>
                                                            <select id="loan_type_id" class="form-control" name="loan_type_id">
                                                            </select>

                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat pull-left"
                                                data-dismiss="modal"><i class="fa fa-ban"></i> @lang('blade.cancel')</button>
                                        <button type="submit" class="btn btn-primary btn-flat" id="btn-save"
                                                value="create"><i class="fa fa-save"></i> @lang('blade.save')
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
                                    <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> Client serverdan o`chiriladi!</h4>
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
                                        Client <i class="fa fa-check-circle"></i>
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <h5>Client Successfully deleted</h5>
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

        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet" />
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
        <script>

            $(function () {
                $("#example1").DataTable();
            });

            //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            });
            $('.input-datepicker').datepicker({
                todayBtn: 'linked',
                todayHighlight: true,
                format: 'dd.mm.yyyy',
                autoclose: true
            });
            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                forceParse: false,
                todayHighlight: true,
                format: 'dd.mm.yyyy',
                autoclose: true
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


                function atou(b64) {
                    return decodeURIComponent(escape(atob(b64)));
                }

                $('body').on('click', '#getKatmShow', function () {
                    $('#reportBase64Modal').empty();
                    var cid = $(this).val();

                    $.get('/uw/get-client-katm/' + cid, function (data) {
                        //console.log(data);

                        var decodedString = atou(data.reportBase64);

                        $('#reportBase64Modal').prepend(decodedString);

                        $('#resultKATMModal').modal('show');

                    })
                });

                $('body').on('click', '#editClient', function () {

                    var model_id = $(this).data('id');

                    $.get('/uw-clients/' + model_id, function (data) {
                        console.log(data);

                        $('#modalHeader').html("Edit client");

                        $('#btn-save').val("editClient");

                        $('#modalForm').modal('show');

                        $('#model_id').val(model_id);

                        $('#inn').val(data.model.inn);
                        $('#document_serial').val(data.model.document_serial);
                        $('#document_number').val(data.model.document_number);
                        $('#document_date').val(data.model.document_date);
                        $('#birth_date').val(data.model.birth_date);

                        if (data.model.gender == 1){
                            $('#gender').html(
                                '<option value="'+data.model.gender+'" selected>Erkak</option>'+
                            '<option value="2">Ayol</option>');
                        } else {
                            $('#gender').html(
                                '<option value="'+data.model.gender+'" selected>Ayol</option>'+
                                '<option value="1">Erkak</option>');
                        }

                        if (data.model.is_inps == 1){
                            $('#is_inps').html(
                                '<option value="'+data.model.is_inps+'" selected>Tashkilot hodimi (INPS bor)</option>'+
                                '<option value="2">Organ hodimi (INPS yo`q)</option>'+
                                '<option value="3">Nafaqada (INPS yo`q)</option>'
                            );
                        }if (data.model.is_inps == 2) {
                            $('#is_inps').html(
                                '<option value="'+data.model.is_inps+'" selected>Organ hodimi (INPS yo`q)</option>'+
                                '<option value="1">Tashkilot hodimi (INPS bor)</option>'+
                                '<option value="3">Nafaqada (INPS yo`q)</option>'
                            );
                        } if (data.model.is_inps == 3) {
                            $('#is_inps').html(
                                '<option value="'+data.model.is_inps+'" selected>Nafaqada (INPS yo`q)</option>'+
                                '<option value="1">Tashkilot hodimi (INPS bor)</option>'+
                                '<option value="2">Organ hodimi (INPS yo`q)</option>'
                            );
                        }

                        $('#family_name').val(data.model.family_name);
                        $('#name').val(data.model.name);
                        $('#patronymic').val(data.model.patronymic);
                        $('#pin').val(data.model.pin);
                        $('#summa').val(data.model.summa);
                        $('#registration_address').val(data.model.registration_address);
                        $('#job_address').val(data.model.job_address);
                        $('#iabs_num').val(data.model.iabs_num);

                        $('#loan_type_id').empty();
                        $.each( data.modelLoanType, function(k, v) {
                            if (v.id == data.model.loan_type_id)
                            {
                                $('#loan_type_id').append('<option value="'+v.id+'" selected>'+v.title+'</option>');
                            } else {
                                $('#loan_type_id').append($('<option>', {value:v.id, text:k+1+'. '+v.title}));

                            }
                        });

                    })
                });

                $('body').on('click', '#deleteRole', function (e) {

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
                            url: "{{ url('uw-clients') }}"+'/'+id,
                            success: function (data)
                            {
                                console.log(data);
                                $('#successModal').modal('show');
                                $("#rowId_" + id).remove();
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

                            url: "{{ url('uw-clients-edit') }}",

                            type: "POST",

                            dataType: 'json',

                            success: function (data) {

                                console.log(data);
                                var model =
                                    '<tr id="rowId_' + data.model.id + '">' +
                                    '<td>' + data.model.id + '</td>' +
                                    '<td>' + data.loan_name + '</td>' +
                                    '<td>' + data.model.iabs_number + '</td>' +
                                    '<td>' + data.model.claim_id + '</td>' +
                                    '<td>' + data.model.family_name +' '+data.model.name+' '+data.model.patronymic+ '</td>' +
                                    '<td>' + formatCurrency(data.model.summa) + '</td>' +
                                    '<td><span class="badge bg-yellow-active">Yangi</span></td>' +
                                    '<td>' + formatCurrency(data.credit_debt) + '</td>';

                                model +=
                                    '<td>' +
                                    '<a class="btn btn-flat btn-primary" href="" data-id="' + data.id + '">' +
                                    '<i class="fa fa-eye-slash"> Ko`rish' +
                                    '</a>' +
                                    '</td>';

                                model +=
                                    '<td>' +
                                    '<button type="button" class="btn btn-flat btn-info" id="editClient" data-id="' + data.id + '">' +
                                    '<i class="fa fa-pencil">' +
                                    '</button>' +
                                    '</td>';

                                model +=
                                    '<td>' +
                                    '<button type="button" class="btn btn-flat btn-danger" id="deleteRole" data-id="' + data.id + '">' +
                                    '<i class="fa fa-trash">' +
                                    '</button>' +
                                    '</td>' +
                                    '<td>' + data.model.created_at + '</td>' +

                                    '</tr>';


                                if (actionType == "createRole") {

                                    $('#roleTable').prepend(model);

                                } else {

                                    $("#rowId_" + data.model.id).replaceWith(model);

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

            function formatCurrency(total) {
                var neg = false;
                if(total < 0) {
                    neg = true;
                    total = Math.abs(total);
                }
                return (neg ? "-" : '') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
            }

        </script>
    </section>
    <!-- /.content -->
@endsection
