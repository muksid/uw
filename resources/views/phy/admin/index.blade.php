@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Jismoniy shaxslar
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">barcha arizalar</a></li>
            <li class="active">clients</li>
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

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">

                    <div class="box-body">

                        <h4 class="box-title">FILTER
                            @if($date_s)
                                <span class="text-sm text-green">{{ \Carbon\Carbon::parse($date_s)->format('d.m.Y')  }} dan {{ \Carbon\Carbon::parse($date_e)->format('d.m.Y') }} gacha</span>
                            @endif
                        </h4>

                        <form action="{{url('/phy/uw/all-clients')}}" method="POST" role="search">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="mfo" value="{{ $mfo }}"
                                               placeholder="MFO">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <select name="status" class="form-control select2" style="width: 100%;">
                                            @if($status_name)
                                                <option value="{{ $status_name->status_code }}"
                                                        selected>{{ $status_name->name }}</option>
                                            @else
                                                <option value="" selected>Holati barchasi</option>
                                            @endif

                                            @foreach($status_names as $key => $value)

                                                <option value="{{$value->status_code}}">
                                                    {{ $value->name }}
                                                </option>

                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-default" id="daterange-btn">
                                                <span>
                                                  <i class="fa fa-calendar"></i> Davr oraliq
                                                </span>
                                                <i class="fa fa-caret-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input name="date_s" id="s_start" value="{{ $date_s }}" hidden>
                                <input name="date_e" id="s_end" value="{{ $date_e }}" hidden>

                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="text" value="{{ $text }}"
                                               placeholder="SEARCH %">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="user" class="form-control select2" style="width: 100%;">

                                            <option value="">
                                                Inspektor Barchasi
                                            </option>

                                            @if(!empty($inspectors))
                                                @foreach($inspectors as $key => $value)
                                                    @if($value->user_id == $user)
                                                        <option value="{{$value->user_id }}" selected>
                                                            {{ $value->full_name }}
                                                        </option>
                                                    @endif
                                                    <option value="{{$value->user_id }}">
                                                        {{ $value->full_name }}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{url('/phy/uw/all-clients')}}" class="btn btn-flat border-success">
                                            <i class="fa fa-refresh"></i> @lang('blade.reset')
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

                        <form action="{{ route('phy-export') }}" method="POST" role="search">
                            {{ csrf_field() }}
                            <input name="mfo" value="{{ $mfo }}" hidden>
                            <input name="status" value="{{ $status_name->status_code??null }}" hidden>
                            <input name="text" value="{{ $text }}" hidden>
                            <input name="user" value="{{ $user }}" hidden>
                            <input name="date_s" id="s_start" value="{{ $date_s }}" hidden>
                            <input name="date_e" id="s_end" value="{{ $date_e }}" hidden>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i> Excel Export
                            </button>
                        </form>
                    </div>

                    <div class="box-body">
                        <b>@lang('blade.overall'){{': '. number_format($models->total()) }} @lang('blade.group_edit_count').</b>

                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kredit Turi</th>
                                <th>Role</th>
                                <th>IABS</th>
                                <th>Ariza #</th>
                                <th>Mijoz FIO</th>
                                <th>Summa</th>
                                <th>Status</th>
                                <th>OnReg</th>
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
                                        <td class="text-maroon">
                                            {!! \Illuminate\Support\Str::words($model->loanType->title??'', '2') !!}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" id="editClient"
                                                    data-id="{{ $model->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </td>
                                        <td>{{ $model->iabs_num??'-' }}</td>
                                        <td>{{ $model->claim_id }}</td>
                                        <td>
                                            <a href="{{ url('/phy/uw/view-client',
                                                    ['id' => $model->id,
                                                    'claim_id' => $model->claim_id]) }}">
                                               {{ $model->family_name. ' '.$model->name. ' '.$model->patronymic}}
                                            </a>
                                        </td>
                                        <td>{{ number_format($model->summa) }}</td>
                                        <td>
                                            <span class="badge {{ $model->statusUw->bg_style??'-' }}">
                                                {{ $model->statusUw->name??'-' }}
                                            </span>
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
                                        <td><span class="badge bg-light-blue-active">{{ $model->branch_code??'' }}</span>
                                            - {!! \Illuminate\Support\Str::words($model->department->title??'Филиал', '3') !!}
                                        </td>
                                        <td class="text-green">{{ $model->inspector->personal->l_name??'-' }} {{ $model->inspector->personal->f_name??'-' }}</td>
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

                //Date range as a button
                $('#daterange-btn').daterangepicker(
                    {
                        ranges: {
                            'Bugun': [moment(), moment()],
                            'Kecha': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Ohirgi 7 kun': [moment().subtract(6, 'days'), moment()],
                            'Ohirgi 30 kun': [moment().subtract(29, 'days'), moment()],
                            'Bu oyda': [moment().startOf('month'), moment().endOf('month')],
                            'O`tgan oyda': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment().subtract(29, 'days'),
                        endDate: moment()
                    },
                    function (start, end) {
                        var s_start = start.format('YYYY-MM-DD');

                        var s_end = end.format('YYYY-MM-DD');

                        $('#s_start').val(s_start);
                        $('#s_end').val(s_end);

                        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    }
                );

                //Date picker
                $('#datepicker').datepicker({
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

                $('body').on('click', '#editClient', function () {

                    var model_id = $(this).data('id');

                    $.get('/madmin/get-phy-client/' + model_id, function (data) {
                        console.log(data);

                        $('#modalHeader').html("Mijoz ma`lumotlarini o`zgartirish");

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

                        if (data.model.reg_status === 0){
                            $('#reg_status').html(
                                '<option value="'+data.model.reg_status+'" selected>OnlineReg (yoniq)</option>'+
                                '<option value="1">OnlineReg (o`chik)</option>'
                            );
                        }else if (data.model.reg_status === 1) {
                            $('#reg_status').html(
                                '<option value="'+data.model.reg_status+'" selected>OnlineReg (o`chik)</option>'+
                                '<option value="0">OnlineReg (yoniq)</option>'
                            );
                        }

                        if (data.model.status === -1){
                            $('#status').html(
                                '<option value="'+data.model.status+'" selected>O`chirilgan</option>'+
                                '<option value="0">Bekor qilish</option>'+
                                '<option value="1">Yaratilgan (CS)</option>'+
                                '<option value="2">Yangi (Risk)</option>'+
                                '<option value="3">Tasdiqlash</option>'
                            );
                        }else if (data.model.status === 0) {
                            $('#status').html(
                                '<option value="'+data.model.status+'" selected>Bekor qilingan</option>'+
                                '<option value="-1">O`chirish</option>'+
                                '<option value="1">Yaratilgan (CS)</option>'+
                                '<option value="2">Yangi (Risk)</option>'+
                                '<option value="3">Tasdiqlash</option>'
                            );
                        }else if (data.model.status === 1) {
                            $('#status').html(
                                '<option value="'+data.model.status+'" selected>Yaratilgan (CS)</option>'+
                                '<option value="-1">O`chirish</option>'+
                                '<option value="0">Bekor qilish</option>'+
                                '<option value="2">Yangi (Risk)</option>'+
                                '<option value="3">Tasdiqlash</option>'
                            );
                        }else if (data.model.status === 2) {
                            $('#status').html(
                                '<option value="'+data.model.status+'" selected>Yangi</option>'+
                                '<option value="-1">O`chirish</option>'+
                                '<option value="0">Bekor qilish</option>'+
                                '<option value="1">Yaratilgan (CS)</option>'+
                                '<option value="3">Tasdiqlash</option>'
                            );
                        }else if (data.model.status === 3) {
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

                        if (data.user) {

                            $('#cs_user_id').append('<option value="'+data.user.work_user_id+'" selected>'+data.user.filial_code+' - '+data.user.full_name+ '</option>');

                        }

                        $.each( data.csUsers, function(k, v) {
                            $('#cs_user_id').append($('<option>', {value:v.work_user_id, text:v.filial_code+' - '+v.full_name}));
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
                            url: "{{ url('/madmin/uw-risk-edit') }}",
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
