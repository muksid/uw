@extends('layouts.uw.dashboard')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Barcha Arizalar
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

                <div class="box box-primary">

                    <div class="box-body">

                        <form action="{{url('/phy/all-clients')}}" method="POST" role="search">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="u" class="form-control select2" style="width: 100%;">
                                            @if(!empty($searchUser))
                                                <option value="{{$searchUser->id}}" selected>
                                                    {{$searchUser->branch_code??''}} - {{ $searchUser->personal->l_name??'' }} {{$searchUser->personal->f_name??'-'}}
                                                </option>
                                            @else
                                                <option value="" selected>
                                                    @lang('blade.select_employee')
                                                </option>
                                            @endif

                                            @if(!empty($users))
                                                @foreach($users as $key => $value)
                                                    <option value="{{$value->currentWork->id}}">
                                                        {{$value->currentWork->branch_code??''}} - {{$value->personal->l_name??''}} {{$value->personal->f_name??''}}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="t" value="{{ $t }}"
                                               placeholder="(iabs, ariza#, fio, inn, summa, mfo)">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <div class="input-group input-daterange">
                                                <input type="text" name="d" id="out_date" value="{{ $d }}"
                                                       class="form-control" placeholder="@lang('blade.date')"
                                                       readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{url('/phy/all-clients')}}" class="btn btn-default btn-flat">
                                            <i class="fa fa-refresh"></i> @lang('blade.reset')
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-flat">
                                            <i class="fa fa-search"></i> @lang('blade.search')
                                        </button>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </form>
                    </div>
                    <div class="box-body">
                        <b>@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kredit Turi</th>
                                <th>Role</th>
                                <th>IABS #</th>
                                <th>Ariza #</th>
                                <th>Mijoz FIO</th>
                                <th>STIR</th>
                                <th>Summa</th>
                                <th>Status</th>
                                <th>OnlineReg</th>
                                <th>Filial</th>
                                <th>Inspektor</th>
                                <th>Sana</th>
                            </tr>
                            </thead>
                            <tbody id="roleTable">
                            <?php $i = 1; $price = 0; $branch = '09011'; ?>
                            @if($models->count())
                                @foreach ($models as $key => $model)
                                    <tr id="rowId_{{ $model->id }}" class="text-sm">
                                        <td>{{ $i++ }}</td>
                                        <td class="text-maroon">{{ $model->loanType->title??'' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-flat btn-info" id="editClient"
                                                    data-id="{{ $model->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </td>
                                        <td>{{ $model->iabs_num }}</td>
                                        <td>{{ $model->claim_id }}</td>
                                        <td>
                                            <a href="{{ url('uw/view-loan',
                                                    ['id' => $model->id,
                                                    'claim_id' => $model->claim_id]) }}">
                                                {{ $model->family_name. ' '.$model->name. ' '.$model->patronymic}}
                                            </a>
                                        </td>
                                        <td>{{ $model->inn }}</td>
                                        <td>{{ number_format($model->summa, 2) }}</td>
                                        <td>
                                            @if($model->status == 0)
                                                <span class="badge bg-warning">Taxrirlashda</span>
                                            @elseif($model->status == 1)
                                                <span class="badge bg-yellow">Yaratilgan CS</span>
                                            @elseif($model->status == 2)
                                                <span class="badge bg-primary">Yangi</span>
                                            @elseif($model->status == 3)
                                                <span class="badge bg-aqua-active">Tasdiqlangan</span>
                                            @elseif($model->status == -1)
                                                <span class="badge bg-red-active">O`chirilgan</span>
                                            @else
                                                <span class="badge bg-red-active">Aniqlanmagan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group center-block" data-toggle="btn-toggle">
                                                @if($model->reg_status == 0)
                                                    <button type="button" class="btn btn-default btn-sm">
                                                        <i class="fa fa-square text-red"></i>
                                                    </button>
                                                @elseif($model->reg_status == 1)
                                                    <button type="button" class="btn btn-default btn-sm active">
                                                        <i class="fa fa-square text-green"></i>
                                                    </button>
                                                @else
                                                    <span class="badge bg-red-active">Not found</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light-blue-active">{{ $model->filial->filial_code??'' }}</span>
                                            - {!! \Illuminate\Support\Str::words($model->department->title??'Филиал', '3') !!}
                                        </td>
                                        <td class="text-green">{{ $model->currentWork->personal->l_name??'-' }} {{ $model->currentWork->personal->f_name??'-' }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d.m.Y H:i')  }}<br>
                                        </td>
                                    </tr>
                                @endforeach

                            @else
                                <td class="text-red text-center" colspan="12"><i class="fa fa-search"></i>
                                    <b>@lang('blade.not_found')</b></td>
                            @endif
                            </tbody>
                        </table>
                        <span class="paginate">{{ $models->links() }}</span>
                    </div>

                    {{--create/updte modal--}}
                    <div class="modal fade" id="modalForm" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="modalHeader"></h4>
                                </div>
                                <form id="appForm" name="appForm">
                                    <div class="modal-body">
                                        <div class="row">
                                            <input type="hidden" name="model_id" id="model_id">
                                            <div class="col-md-12">

                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <select id="status" class="form-control select2" name="status" style="width: 100%">
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="text-maroon">KATM Online Registration <i class="fa fa-exclamation"></i></label>
                                                            <select id="reg_status" class="form-control select2" name="reg_status" style="width: 100%">
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="text-yellow">KATM Status</label>
                                                            <select id="reg_katm" class="form-control" name="reg_katm" style="width: 100%">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="text-yellow">INPS Status</label>
                                                            <select id="reg_inps" class="form-control" name="reg_inps" style="width: 100%">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Inspektor</label>
                                                            <select id="cs_user_id" class="form-control select2" name="cs_user_id" style="width: 100%">
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="descr" class="control-label">Izox</label>
                                                            <input type="text" class="form-control"
                                                                   style="width: 100%" id="descr"
                                                                   name="descr" value="" required="">
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

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

        <link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>

        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <script>

            $(function () {
                $("#example1").DataTable();
                //Initialize Select2 Elements
                $(".select2").select2();

                //Date picker
                $('#datepicker').datepicker({
                    autoclose: true
                });
                $('.input-datepicker').datepicker({
                    todayBtn: 'linked',
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    forceParse: false,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            });

            // crud form
            $(document).ready(function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
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

                        $('#reg_katm').html(
                            '<option value="1" selected>KATM status</option>'+
                            '<option value="0">O`chirish</option>'
                        );

                        $('#reg_inps').html(
                            '<option value="1" selected>INPS status</option>'+
                            '<option value="0">O`chirish</option>'
                        );

                        if (data.model.reg_status == 0){
                            $('#reg_status').html(
                                '<option value="'+data.model.reg_status+'" selected>OnlineReg (yoniq)</option>'+
                                '<option value="1">OnlineReg (o`chik)</option>'
                            );
                        }else if (data.model.reg_status == 1) {
                            $('#reg_status').html(
                                '<option value="'+data.model.reg_status+'" selected>OnlineReg (o`chik)</option>'+
                                '<option value="0">OnlineReg (yoniq)</option>'
                            );
                        }

                        if (data.model.status == -1){
                            $('#status').html(
                                '<option value="'+data.model.status+'" selected>O`chirilgan</option>'+
                                '<option value="0">Bekor qilish</option>'+
                                '<option value="1">Yaratilgan (CS)</option>'+
                                '<option value="2">Yangi (Risk)</option>'+
                                '<option value="3">Tasdiqlash</option>'
                            );
                        }else if (data.model.status == 0) {
                            $('#status').html(
                                '<option value="'+data.model.status+'" selected>Bekor qilingan</option>'+
                                '<option value="-1">O`chirish</option>'+
                                '<option value="1">Yaratilgan (CS)</option>'+
                                '<option value="2">Yangi (Risk)</option>'+
                                '<option value="3">Tasdiqlash</option>'
                            );
                        }else if (data.model.status == 1) {
                            $('#status').html(
                                '<option value="'+data.model.status+'" selected>Yaratilgan (CS)</option>'+
                                '<option value="-1">O`chirish</option>'+
                                '<option value="0">Bekor qilish</option>'+
                                '<option value="2">Yangi (Risk)</option>'+
                                '<option value="3">Tasdiqlash</option>'
                            );
                        }else if (data.model.status == 2) {
                            $('#status').html(
                                '<option value="'+data.model.status+'" selected>Yangi</option>'+
                                '<option value="-1">O`chirish</option>'+
                                '<option value="0">Bekor qilish</option>'+
                                '<option value="1">Yaratilgan (CS)</option>'+
                                '<option value="3">Tasdiqlash</option>'
                            );
                        }else if (data.model.status == 3) {
                            $('#status').html(
                                '<option value="'+data.model.status+'" selected>Tasdiqlangan</option>'+
                                '<option value="-1">O`chirish</option>'+
                                '<option value="0">Bekor qilish</option>'+
                                '<option value="1">Yaratilgan (CS)</option>'+
                                '<option value="2">Yangi (Risk)</option>'
                            );
                        } else {
                            $('#status').html(
                                '<option value="'+data.model.status+'" selected>Aniqlanmagan</option>'+
                                '<option value="-1">O`chirish</option>'+
                                '<option value="0">Bekor qilish</option>'+
                                '<option value="1">Yaratilgan (CS)</option>'+
                                '<option value="2">Yangi (Risk)</option>'+
                                '<option value="3">Tasdiqlash</option>'
                            );
                        }

                        $('#cs_user_id').empty();

                        $.each( data.csUsers, function(k, v) {
                            if (v.work_user_id == data.model.work_user_id)
                            {
                                $('#cs_user_id').append('<option value="'+v.work_user_id+'" selected>'+v.full_name+' - '+v.filial_code+' - '+v.filial_name+ '</option>');
                            } else
                            {
                                $('#cs_user_id').append($('<option>', {value:v.work_user_id, text:v.filial_code+' - '+v.full_name+' - '+v.filial_name}));
                            }
                        });

                    })
                });

            });

            if ($("#appForm").length > 0) {

                $("#appForm").validate({

                    submitHandler: function (form) {

                        $('#btn-save').html('Sending..');

                        $.ajax({
                            data: $('#appForm').serialize(),
                            url: "{{ url('uw-risk-edit') }}",
                            type: "POST",
                            dataType: 'json',
                            success: function (data) {
                                console.log(data);
                                var model =
                                    '<tr id="rowId_' + data.model.id + '">' +
                                    '<td>' + data.model.id + '</td>' +
                                    '<td>' + data.loan_name + '</td>'+
                                    '<td>' +
                                    '<a class="btn btn-flat btn-info" id="editClient" data-id="' + data.id + '"><i class="fa fa-pencil"></i></a>' +
                                    '</td>'+
                                    '<td>' + data.model.iabs_number + '</td>' +
                                    '<td>' + data.model.claim_id + '</td>' +
                                    '<td>' + data.model.family_name +' '+data.model.name+' '+data.model.patronymic+ '</td>' +
                                    '<td>' + data.model.inn + '</td>' +
                                    '<td>' + formatCurrency(data.model.summa) + '</td>' +
                                    '<td><span class="badge bg-yellow-active">Yangi</span></td>' +
                                    '<td><span class="badge bg-yellow-active">Yangi</span></td>' +
                                    '<td>' + data.sc_ball + '</td>' +
                                    '<td>' + data.model.branch_code + '</td>'+
                                    '<td>' + data.model.user_id + '</td>'+
                                    '<td>' + data.model.created_at + '</td>';

                                $("#rowId_" + data.model.id).replaceWith(model);

                                $('#appForm').trigger("reset");

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

                function formatCurrency(total) {
                    var neg = false;
                    if(total < 0) {
                        neg = true;
                        total = Math.abs(total);
                    }
                    return (neg ? "-" : '') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                }
            }
        </script>
    </section>
    <!-- /.content -->
@endsection
