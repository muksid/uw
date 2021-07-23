@extends('layouts.uw.dashboard')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Anderrayting Arizalar
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">anderrayting</a></li>
            <li class="active">anderrayting</li>
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

                        <form action="{{url('/uw/loan-app/'.$status.'')}}" method="POST" role="search">
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

                                <div class="col-md-4">
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
                                        <a href="{{url('/uw/loan-app/'.$status.'')}}" class="btn btn-default btn-flat">
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
                                <th>Kredit</th>
                                <th>IABS #</th>
                                <th>Ariza #</th>
                                <th>Mijoz Nomi</th>
                                <th>STIR</th>
                                <th>Summa</th>
                                <th class="text-center">@lang('blade.status')</th>
                                <th><i class="fa fa-bank"></i> Filial Nomi</th>
                                <th class="text-center"><i class="fa fa-user"></i> Inspektor</th>
                                <th>Sana</th>
                            </tr>
                            </thead>
                            <tbody id="roleTable">
                            <?php $i = 1 ?>
                            @if($models->count())
                            @foreach ($models as $key => $model)
                                <tr id="rowId_{{ $model->id }}" class="text-sm">
                                    <td>{{ $i++ }}</td>
                                    <td style="max-width: 100px;">
                                        <span class="text-green">{!! \Illuminate\Support\Str::words($model->loanType->title??'', '3') !!}</span>
                                    </td>
                                    <td>{{ $model->iabs_num }}</td>
                                    <td>{{ $model->claim_id }}</td>
                                    <td class="text-uppercase">
                                        <a href="{{ url('uw/view-loan',
                                                    ['id' => $model->id,
                                                    'claim_id' => $model->claim_id]) }}">
                                            {{ $model->family_name. ' '.$model->name. ' '.$model->patronymic}}
                                        </a>
                                    </td>
                                    <td>{{ $model->inn }}</td>
                                    <td><b>{{ number_format($model->summa, 2) }}</b></td>

                                    <td>
                                        @if($model->status == 0)
                                            <span class="badge bg-red-active">Taxrirlashda</span>
                                            @elseif($model->status == 2)
                                            <span class="badge bg-yellow-active">Yangi</span>
                                            @elseif($model->status == 3)
                                            <span class="badge bg-aqua-active">Tasdiqlandi</span>
                                            @elseif($model->status == 4)
                                            <span class="badge bg-aqua-active">Yopilgan</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-light-blue-active">{{ $model->filial->filial_code??'' }}</span>
                                       - {!! \Illuminate\Support\Str::words($model->department->title??'Филиал', '3') !!}
                                    </td>
                                    <td class="text-green">{{ $model->currentWork->personal->l_name??'' }} {{ $model->currentWork->personal->f_name??'' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($model->created_at)->format('d.m.Y H:i')  }}<br>
                                        <span class="text-maroon"> ({{$model->created_at->diffForHumans()}})</span>
                                    </td>
                                </tr>
                            @endforeach @else
                                <td class="text-red text-center" colspan="12"><i class="fa fa-search"></i>
                                    <b>@lang('blade.not_found')</b></td>
                            @endif
                            </tbody>
                        </table>
                        <span class="paginate">{{ $models->links() }}</span>
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
                                            <label for="name" class="control-label">Inn</label>
                                            <input type="text" class="form-control" style="width: 100%" id="inn"
                                                   name="inn" value="" required="">
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="control-label">INPS</label>
                                            <input type="text" class="form-control" style="width: 100%" id="pin"
                                                   name="pin" value="" required="">
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="control-label">summa</label>
                                            <input type="text" class="form-control" style="width: 100%" id="summa"
                                                   name="summa" value="" required="">
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

                $('body').on('click', '#editRole', function () {

                    var model_id = $(this).data('id');

                    $.get('/uw-clients/' + model_id, function (data) {
                        console.log(data);

                        $('#modalHeader').html("Edit client");

                        $('#btn-save').val("editRole");

                        $('#modalForm').modal('show');

                        $('#model_id').val(model_id);

                        $('#inn').val(data.inn);

                        $('#pin').val(data.pin);

                        $('#summa').val(data.summa);

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
                                /*console.log(data);*/

                                var model =
                                    '<tr id="rowId_' + data.id + '">' +
                                    '<td>' + data.id + '</td>' +
                                    '<td>' + data.claim_id + '</td>' +
                                    '<td>' + data.family_name +' '+data.name+' '+data.patronymic+ '</td>' +
                                    '<td>' + data.summa + '</td>' +
                                    '<td>' + data.created_at + '</td>' +
                                    '<td><span class="badge bg-yellow-active">Yangi</span></td>' +
                                    '<td>1</td>';

                                model +=
                                    '<td>' +
                                    '<a class="btn btn-flat btn-primary" href="" data-id="' + data.id + '">' +
                                    '<i class="fa fa-eye-slash"> Show' +
                                    '</a>' +
                                    '</td>';

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

                                    console.log(data);

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
