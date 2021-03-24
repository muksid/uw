@extends('layouts.uw.dashboard')

@section('content')
    <section class="content-header">
        <h1>
            KATM <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">katm</a></li>
            <li class="active">online</li>
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
        @if(session('error'))
            <div class="box box-default">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <h4 class="modal-title"> {{ session('error') }}</h4>
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
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-xs-6">
                                <div class="box-body">
                                    <div class="box-header">
                                        <h3 class="box-title">ИНФОРМАЦИЯ IABS (<span id="iabs_id">{{ $model->iabs_num }}</span>)</h3>
                                        <button type="button" data-id="{{ $model->id }}"
                                                class="btn btn-info btn-flat pull-right" id="getUwAppBlank">
                                            <i class="fa fa-print"></i> Ariza blank
                                        </button>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body table-responsive no-padding">
                                        <table class="table table-hover">
                                            <tbody><tr>
                                                <th>#</th>
                                                <th>ТИП ИНФОРМАЦИИ</th>
                                                <th>ИНФОРМАЦИЯ</th>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Наименование заёмщика</td>
                                                <td>{{ $model->family_name.' '.$model->name.' '.$model->patronymic }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Дата рождения</td>
                                                <td>{{ \Carbon\Carbon::parse($model->birth_date)->format('d.m.Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Данные документа</td>
                                                <td>{{ $model->document_serial.' '.$model->document_number.' от ' }}{{ \Carbon\Carbon::parse($model->document_date)->format('d.m.Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>ИНН</td>
                                                <td>{{ $model->inn }}</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>ПИНФЛ</td>
                                                <td>{{ $model->pin }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Пол</td>
                                                <td>
                                                    @if($model->gender == 1)
                                                        Erkak
                                                    @else
                                                        Ayol
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Адрес</td>
                                                <td>{{ $model->region->name??'' }}, {{ $model->district->name??'' }}</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Адрес по прописке</td>
                                                <td>{{ $model->registration_address }}</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Иш жойи</td>
                                                <td>{{ $model->job_address }}</td>
                                            </tr>
                                            <tr class="bg-danger">
                                                <td>10</td>
                                                <td>ИНПС</td>
                                                <td>
                                                    @if($model->is_inps == 1)
                                                        Tashkilot hodimi (INPS bor)
                                                        @elseif($model->is_inps == 2)
                                                    Organ hodimi (INPS yo`q)
                                                        @elseif($model->is_inps == 3)
                                                    Nafaqada (INPS yo`q)
                                                        @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Телефон</td>
                                                <td>{{ $model->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>Кредитная заявка</td>
                                                <td>{{ $model->claim_id.' от ' }}{{ \Carbon\Carbon::parse($model->claim_date)->format('d.m.Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>Кредит</td>
                                                <td>{{ number_format($model->summa, 2) }} so`m, {{ $model->procent }}%, {{ $model->credit_duration }} oy muddatga</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <div class="pull-right">
                                        @if(!$katm)
                                            <form method="POST" action="{{ url('uw/postKatm') }}" >
                                                {{csrf_field()}}
                                                <input type="hidden" name="claim_id" value="{{ $model->claim_id }}">
                                                <input type="hidden" name="uw_clients_id" value="{{ $model->id }}">
                                                <input type="hidden" name="uw_is_inps" value="{{ $model->is_inps }}">
                                                <input type="hidden" name="is_inps" value="0">
                                                <button type="submit" class="btn btn-success btn-flat">(1) KATMga so`rov yuborish</button>
                                            </form>
                                        @elseif(!$costs && $model->is_inps == 1)
                                            <form method="POST" action="{{ url('uw/postKatm') }}" >
                                                {{csrf_field()}}
                                                <input type="hidden" name="claim_id" value="{{ $model->claim_id }}">
                                                <input type="hidden" name="uw_clients_id" value="{{ $model->id }}">
                                                <input type="hidden" name="uw_is_inps" value="{{ $model->is_inps }}">
                                                <input type="hidden" name="is_inps" value="1">
                                                <input type="hidden" name="uw_is_inps" value="{{ $model->is_inps }}">
                                                <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-money"></i> (2) INPSga so`rov yuborish</button>
                                            </form>
                                        @elseif(!$costs && ($model->is_inps == 2 || $model->is_inps == 3))

                                            @if($model->status == 1)
                                                @if($katm_scoring >= 200)
                                                    <div class="col-md-4">
                                                        <button type="button" class="btn btn-flat btn-primary" id="sendLoan"
                                                                data-id="{{ $model->claim_id }}"><i class="fa fa-send"></i> Arizani Administratorga yuborish
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-flat btn-danger disabled">
                                                            <i class="fa fa-ban"></i> Arizani yuborish mumkin emas
                                                        </button><hr>
                                                        <div class="box-footer no-padding">
                                                            <ul class="nav nav-stacked">
                                                                <li>
                                                                    <span class="badge bg-danger">
                                                                        <h4>2. Scoring bali 200 baldan past!</h4>
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                @endif

                                            @elseif($model->status == 3)
                                                <div class="col-md-4">
                                                    <button type="button" class="btn btn-flat btn-success"
                                                            data-id="{{ $model->claim_id }}"><i class="fa fa-check-circle-o"></i> Arizani Administrator tasdiqlagan
                                                    </button>
                                                </div>
                                            @elseif($model->status == 0)

                                                <div class="col-md-12" id="sendLoan">
                                                    <div class="callout callout-danger">
                                                        <h4><i class="icon fa fa-warning"></i> Ariza Bekor qilingan!</h4>
                                                        <p>
                                                        {{ $model->descr }}
                                                        </p>
                                                    </div>
                                                    <button type="button" class="btn btn-primary" id="sendReLoan"
                                                            data-id="{{ $model->claim_id }}"><i class="fa fa-pencil"></i> Arizani qayta yuborish
                                                    </button>
                                                </div>
                                            @else

                                                <div class="col-md-12">
                                                    <div class="callout callout-success" id="successLoan">
                                                        <h4><i class="icon fa fa-check-square-o"></i> Ariza yuborildi</h4>
                                                    </div>
                                                </div>

                                            @endif
                                                <div class="col-md-12">
                                                    <div class="callout callout-success" id="successLoan">
                                                        <h4><i class="icon fa fa-check-square-o"></i> Ariza yuborildi</h4>
                                                    </div>
                                                </div>
                                        @endif
                                    </div>

                                </div>

                        <div class="col-md-12">
                            @if(!empty($summ_en) || $model->is_inps !=1)
                            <h3 class="widget-user-username">Mijoz Kredit xujjatlari</h3>
                            @foreach ($model->files as $file)

                                <div id="fileId_{{$file->id}}">

                                    <a href="{{ route('edoPreView',['preViewFile'=>$file->file_hash]) }}"
                                       class="text-info text-bold"
                                       target="_blank" class="mailbox-attachment-name"
                                       onclick="window.open('<?php echo('/uw/filePreView/' . $file->file_hash); ?>',
                                               'modal',
                                               'width=800,height=900,top=30,left=500');
                                               return false;"> <i class="fa fa-search-plus"></i> {{ $file->file_name }}</a>
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <a href="{{ route('file-load',['file'=>$file->file_hash]) }}" class="link-black text-sm"><i
                                                        class="fa fa-cloud-download text-primary"></i> @lang('blade.download')</a>
                                        </li>
                                        @if($model->status == 0 || $model->status == 1)
                                        <li> | </li>
                                        <li class="pull-right">
                                            <button class="btn btn-xs btn-danger deleteFile" data-id="{{ $file->id }}" >
                                                <i class="fa fa-trash"></i> @lang('blade.delete')
                                            </button>
                                        </li>
                                            @endif
                                    </ul>
                                    <i class="text-red">({{ \App\Message::formatSizeUnits($file->file_size) }})</i><br><br>

                                </div>

                            @endforeach
                            @if($model->status == 0 || $model->status == 1)
                                <div class="alert" id="message" style="display: none"></div>
                                <form method="post" id="fileUpload" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <table class="table">
                                            <tr>
                                                <td width="30">
                                                    <input type="file" name="message_file[]" id="message_file" multiple />
                                                    <input type="text" name="model_id" value="{{ $model->id }}" hidden />
                                                </td>
                                                <td width="30%" align="left">
                                                    <button type="submit" name="upload" id="upload" class="btn btn-flat btn-xs btn-info">
                                                        <i class="fa fa-upload"></i> @lang('blade.upload_file')
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            @endif

                            @endif
                        </div>

                        </div>

                        <div class="col-xs-6">
                            <div class="box box-widget widget-user-2">
                                <div class="widget-user-header bg-light-blue">
                                    <h3 class="widget-user-username">KATM Result</h3>
                                    <h5 class="widget-user-desc">online</h5>
                                </div>
                                <div class="box-footer no-padding">
                                </div>
                            </div>

                            <div class="box-header with-border">
                                @if($katm)
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-flat btn-success" id="getResultKATM"
                                                data-id="{{ $model->claim_id }}">KATM skoring natijasi
                                        </button>
                                    </div>
                                @endif
                                @if($costs)
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-flat btn-success" id="getResultINPS"
                                                data-id="{{ $model->claim_id }}">INPS Natijasi
                                        </button>
                                    </div>
                                @endif
                            </div>

                            @if(!empty($summ_en))
                            <div class="box box-widget widget-user-2">
                                <div class="widget-user-header bg-aqua-active">
                                    <h3 class="widget-user-username">Kredit ajratish natijasi</h3>
                                </div>
                                <div class="box-footer no-padding">
                                    <ul class="nav nav-stacked">
                                        <li><a href="#">1. Kredit qarzdorligi:
                                                <span class="pull-right badge bg-red-active" style="font-size: large">
                                                    -{{ number_format($katm_sum, 2).' so`m' }}
                                                </span>
                                            </a>
                                        </li>
                                        <li><a href="#">2. Jami oylik ish xaqi:
                                                <span class="pull-right badge bg-light-blue">
                                                    {{ number_format($costs, 2).' so`m' }} ({{$costs_m.' oyda'}})
                                                </span>
                                            </a>
                                        </li>
                                        <li><a href="#">3. Xar oy to`lov qobiliyati:
                                                <span class="pull-right badge bg-danger" style="font-size: large">
                                                    {{ number_format($summ_en, 2).' so`m' }}
                                                </span>
                                            </a>
                                        </li>
                                        <li><a href="#">4. Mijoz so`ragan kredit:
                                                <span class="pull-right badge bg-gray-active">
                                                    {{ number_format($model->summa, 2).' so`m' }}
                                                </span>
                                            </a>
                                        </li>
                                        <li><a href="#">5. Kredit ajratish mumkin:
                                                @if($loan_summ_en >= 0)
                                                <span class="pull-right badge bg-aqua-active" style="font-size: large">
                                                    @if($loan_summ_en <= $model->summa )
                                                    {{ number_format($loan_summ_en, 2).' so`m' }}
                                                        @else
                                                        {{ number_format($model->summa, 2).' so`m' }}
                                                    @endif
                                                </span>
                                                    @else

                                                    <span class="pull-right badge bg-aqua-active" style="font-size: large">
                                                        0 so`m <p class="text-danger text-sm">({{ number_format($loan_summ_en, 2).' so`m' }})</p>
                                                    </span>

                                                @endif
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                                <div class="box-header with-border">

                                    @if($model->status == 1)
                                         @if(!empty($summ_en) && ($model->summa <= $loan_summ_en && $scoring_ball >= 200))
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-flat btn-primary" id="sendLoan"
                                                        data-id="{{ $model->claim_id }}"><i class="fa fa-send"></i> Arizani Administratorga yuborish
                                                </button>
                                            </div>
                                            @else
                                            <div class="col-md-12">
                                                <div class="callout callout-warning">
                                                    <h4><i class="icon fa fa-warning"></i> Arizani yuborish mumkin emas</h4>
                                                </div>
                                                <div class="box-footer no-padding">
                                                    <ul class="nav nav-stacked">
                                                        @if($model->summa >= $loan_summ_en)
                                                            <li>
                                                        <span class="badge bg-danger">
                                                            <h4>1. To`lov qobiliayit yetarli emas!</h4>
                                                        </span>
                                                            </li>
                                                        @endif

                                                        @if($scoring_ball <= 200)
                                                            <li>
                                                        <span class="badge bg-danger">
                                                            <h4>2. Scoring bali 200 baldan past!</h4>
                                                        </span>
                                                            </li>

                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>

                                        @endif

                                    @elseif($model->status == 3)
                                        <div class="col-md-6">
                                            <div class="callout callout-success">
                                                <h4><i class="icon fa fa-check-square-o"></i> Arizani tasdiqlandi</h4>
                                            </div>
                                        </div>
                                    @elseif($model->status == 0)

                                        <div class="col-md-6">
                                            <div class="callout callout-danger">
                                                <h4><i class="icon fa fa-warning"></i> Ariza Bekor qilingan!</h4>
                                                <p>
                                                    {{ $model->descr }}
                                                </p>
                                            </div>
                                        </div>
                                    @else

                                        <div class="col-md-6">
                                            <div class="callout callout-success">
                                                <h4><i class="icon fa fa-send-o"></i> Ariza yuborildi</h4>
                                            </div>
                                        </div>

                                    @endif
                                        <div class="col-md-6">
                                            <div class="callout callout-success" id="successLoan">
                                                <h4><i class="icon fa fa-send-o"></i> Ariza yuborildi</h4>
                                            </div>
                                        </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{--KATM scoring ball--}}
                    <div class="modal fade" id="resultKATMModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" style="width: 1100px">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <button type="button" class="btn btn-outline pull-right" onclick="print('resultKATMModal')">
                                        <i class="fa fa-print"></i> @lang('blade.print')
                                    </button>
                                    <h4 class="modal-title text-center" id="success">KATM Scoring bali</h4>
                                </div>
                                <div id="scoringPage"></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('blade.close')
                                    </button>
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
                                    <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> File serverdan o`chiriladi!</h4>
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

                    <!--end success modal-->
                    <div class="modal fade modal-success" id="modalEndSuccess" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <h4 class="modal-title" id="success_header"></h4>
                                </div>
                                <div class="modal-body">
                                    <h5 id="success_result"></h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" data-dismiss="modal">@lang('blade.close')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INPS oylik ish haqi -->
                    <div class="modal fade" id="resultINPSModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <button type="button" class="btn btn-outline pull-right" onclick="print('resultINPSModal')">
                                        <i class="fa fa-print"></i> @lang('blade.print')
                                    </button>
                                    <h4 class="modal-title text-center" id="success_inps">Mijoz oylik ish xaqi daromadi (INPS)</h4>
                                </div>
                                <div class="modal-body">
                                    <h4 id="base64INPSSuccess_result" class="text-center">Mijoz:
                                        <b>{{ $model->family_name.' '.$model->name.' '.$model->patronymic }}</b>
                                    </h4>
                                    <div id="resultDataINPS"></div>
                                    <div id="resultDataINPSTotal" class="text-bold"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- app Blank -->
                    <div class="modal fade" id="appBlankModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <button type="button" class="btn btn-outline pull-right" onclick="print('appBlankData')">
                                        <i class="fa fa-print"></i> @lang('blade.print')
                                    </button>
                                    <h4 class="modal-title text-center" id="success_inps">Mijoz Ariza blanki</h4>
                                </div>
                                <div id="appBlankData" class="text-justify" onmousedown='return false;' onselectstart='return false;'></div>
                            </div>
                        </div>
                    </div>

                    <!--confirm form-->
                    <div class="modal fade modal-primary" id="modalFormSend" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modalHeader"></h4>
                                </div>
                                <div class="modal-body">
                                    <h5><span class="glyphicon glyphicon-info-sign"></span> <span id="modalBody"></span></h5>
                                </div>
                                <form id="sendForm" name="sendForm">
                                    <input type="hidden" name="uw_clients_id" id="uw_clients_id" value="{{ $model->id }}">
                                    <div class="col-md-10">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label>IABS unikal raqam<span class="text-red">*</span></label>
                                                <input type="number" id="iabs_num" name="iabs_num"
                                                       class="form-control" placeholder="99008877" required>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <center>
                                            <button type="submit" class="btn btn-outline" id="btn-save-send"
                                                    value="create">Ha, Yuborish
                                            </button>
                                            <button type="button" class="btn btn-outline pull-left"
                                                    data-dismiss="modal">@lang('blade.cancel')</button>
                                        </center>
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

        <style type="text/css">
            table{ border-collapse:collapse; width:100%; }
            table td, th{ border:1px solid #d0d0d0; }

            #main_block table {font-size:9px;}
            .header-color {background-color:#8EB2E2;}
            .mip-color {background-color:#e3fbf7;}
            .procent25_class {width:25%;}
        </style>

        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

        <script>
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

                function getA(b64) {
                    return decodeURIComponent(escape(atob(b64)));
                }
                $.date = function(dateObject) {
                    var d = new Date(dateObject);
                    var day = d.getDate();
                    var month = d.getMonth() + 1;
                    var year = d.getFullYear();
                    if (day < 10) {
                        day = "0" + day;
                    }
                    if (month < 10) {
                        month = "0" + month;
                    }
                    var date = day + "/" + month + "/" + year;

                    return date;
                };

                $('body').on('click', '#getResultKATM', function () {

                    var cid = $('#getResultKATM').data('id');

                    $.get('/uw/get-client-katm/' + cid, function (data) {
                        //console.log(data.scoring.client_info_2_text);

                        $('#scoringPage').empty();
                        $("#scoringPage").prepend(data.scoring_page);

                        $(".client_name").html(data.client.family_name + ' ' + data.client.name + ' ' + data.client.patronymic);

                        var formattedDate = new Date(data.client.birth_date);
                        var d = formattedDate.getDate();
                        var m = formattedDate.getMonth();
                        m += 1;  // JavaScript months are 0-11
                        if (d < 10) {
                            d = "0" + d;
                        }
                        if (m < 10) {
                            m = "0" + m;
                        }
                        var y = formattedDate.getFullYear();
                        $(".client_birth_date").html(d + "." + m + "." + y);
                        if (data.client.gender == 1) {
                            var gender = 'Erkak';
                        } else {
                            gender = 'Ayol';
                        }
                        $(".client_gender").html(gender);
                        $(".client_live_address").html(data.client.live_address);
                        $(".client_pin").html(data.client.pin);
                        $(".client_inn").html(data.client.inn);
                        $(".client_phone").html(data.client.phone);

                        var doc_formattedDate = new Date(data.client.document_date);
                        var doc_d = doc_formattedDate.getDate();
                        var doc_m = doc_formattedDate.getMonth();
                        doc_m += 1;  // JavaScript months are 0-11
                        if (doc_d < 10) {
                            doc_d = "0" + doc_d;
                        }
                        if (doc_m < 10) {
                            doc_m = "0" + doc_m;
                        }
                        var doc_y = doc_formattedDate.getFullYear();
                        $(".client_document").html(data.client.document_serial + ' ' + data.client.document_number + ' '
                            + doc_d + "." + doc_m + "." + doc_y);

                        $('.sc_ball').html(data.scoring.sc_ball);
                        $('.sc_level_info').html(data.scoring.sc_level_info);
                        $('.sc_version').html(data.scoring.sc_version);
                        $('.score_date').html(data.scoring.score_date);


                        $('.client_info_1').html(data.scoring.client_info_1); // fio
                        $('.client_info_2_text').html(data.scoring.client_info_2_text); // den roj
                        $('.client_info_4').html(data.scoring.client_info_4); // adress
                        $('.client_info_5').html(data.scoring.client_info_5); // pinfl
                        $('.client_info_6').html(data.scoring.client_info_6); // inn
                        $('.client_info_8').html(data.scoring.client_info_8); // passport


                        $('.score_img').html('' +
                            '<img id="score_chart" style="padding-left: 9px; margin-bottom: 15px; width: 311px;"' +
                            ' src="' + data.scoring_page1 + '" height="149">\n');

                        $('#tb_row_1_ot').html(data.table.row_1.open_total);
                        $('#tb_row_1_os').html(data.table.row_1.open_summ);
                        $('#tb_row_1_ct').html(data.table.row_1.open_total);
                        $('#tb_row_1_cs').html(data.table.row_1.open_summ);

                        $('#tb_row_2_ot').html(data.table.row_2.open_total);
                        $('#tb_row_2_os').html(data.table.row_2.open_summ);
                        $('#tb_row_2_ct').html(data.table.row_2.open_total);
                        $('#tb_row_2_cs').html(data.table.row_2.open_summ);

                        $('#tb_row_3_ot').html(data.table.row_3.open_total);
                        $('#tb_row_3_os').html(data.table.row_3.open_summ);
                        $('#tb_row_3_ct').html(data.table.row_3.open_total);
                        $('#tb_row_3_cs').html(data.table.row_3.open_summ);

                        $('#tb_row_4_ot').html(data.table.row_4.open_total);
                        $('#tb_row_4_os').html(data.table.row_4.open_summ);
                        $('#tb_row_4_ct').html(data.table.row_4.open_total);
                        $('#tb_row_4_cs').html(data.table.row_4.open_summ);

                        $('#tb_row_5_ot').html(data.table.row_5.open_total);
                        $('#tb_row_5_os').html(data.table.row_5.open_summ);
                        $('#tb_row_5_ct').html(data.table.row_5.open_total);
                        $('#tb_row_5_cs').html(data.table.row_5.open_summ);

                        $('#tb_row_6_ot').html(data.table.row_6.open_total);
                        $('#tb_row_6_os').html(data.table.row_6.open_summ);
                        $('#tb_row_6_ct').html(data.table.row_6.open_total);
                        $('#tb_row_6_cs').html(data.table.row_6.open_summ);

                        $('#tb_row_7_ot').html(0);
                        $('#tb_row_7_os').html(0);
                        $('#tb_row_7_ct').html(0);
                        $('#tb_row_7_cs').html(0);

                        $('#tb_row_8_ot').html(0);
                        $('#tb_row_8_os').html(0);
                        $('#tb_row_8_ct').html(0);
                        $('#tb_row_8_cs').html(0);

                        $('#tb_row_9_ot').html(0);
                        $('#tb_row_9_os').html(0);
                        $('#tb_row_9_ct').html(0);
                        $('#tb_row_9_cs').html(0);
                        /*$('#tb_row_7_ot').html(data.table.row_7.open_total);
                        $('#tb_row_7_os').html(data.table.row_7.open_summ);
                        $('#tb_row_7_ct').html(data.table.row_7.open_total);
                        $('#tb_row_7_cs').html(data.table.row_7.open_summ);

                        $('#tb_row_8_ot').html(data.table.row_8.open_total);
                        $('#tb_row_8_os').html(data.table.row_8.open_summ);
                        $('#tb_row_8_ct').html(data.table.row_8.open_total);
                        $('#tb_row_8_cs').html(data.table.row_8.open_summ);

                        $('#tb_row_9_ot').html(data.table.row_9.open_total);
                        $('#tb_row_9_os').html(data.table.row_9.open_summ);
                        $('#tb_row_9_ct').html(data.table.row_9.open_total);
                        $('#tb_row_9_cs').html(data.table.row_9.open_summ);*/

                        $('#tb_row_12_agr_summ').html(data.table.row_12.agr_summ);
                        $('#tb_row_12_agr_comm2').html(data.table.row_12.agr_comm2.content);
                        $('#tb_row_12_agr_comm3').html(data.table.row_12.agr_comm3);
                        $('#tb_row_12_agr_comm4').html(data.table.row_12.agr_comm4);

                        $('#btn-save').val("KatmResult");

                        $('#katmClaimId').val(cid);

                        $('#resultKATMModal').modal('show');
                    })
                });

                $('body').on('click', '#getResultINPS', function () {

                    var cid = $('#getResultKATM').data('id');

                    $.get('/uw/get-client-inps/' + cid, function (data) {
                        //console.log(data);
                        var data_inps = "";

                        data_inps +="<div class='box-body table-responsive no-padding'>" +
                        "<table class='tabla table-hover'>"+
                        "<tbody>" +
                            "<tr>" +
                                "<th style='border: 1px solid #02497f;'>#</th>" +
                                "<th style='border: 1px solid #02497f;'>Tashkilot INNsi</th>" +
                                "<th style='border: 1px solid #02497f;'>Mijoz oylik daromadi</th>" +
                                "<th style='border: 1px solid #02497f;'>Davr</th>" +
                                "<th style='border: 1px solid #02497f;'>Davr (oy)</th>" +
                                "<th style='border: 1px solid #02497f;'>Tashkilot nomi</th>" +
                            "</tr>";

                        var tot=0;
                        $.each(data, function (key, val) {
                            key++;
                            tot += val.INCOME_SUMMA;
                            var SUMM = (val.INCOME_SUMMA).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                            data_inps +="<tr>" +
                                "<td style='border: 1px solid #02497f;'>"+key+"</td>" +
                                "<td style='border: 1px solid #02497f;'>"+val.ORG_INN+"</td>" +
                                "<td style='border: 1px solid #02497f;'>"+SUMM+"</td>" +
                                "<td style='border: 1px solid #02497f;'>"+val.NUM+"</td>" +
                                "<td style='border: 1px solid #02497f;'>"+val.PERIOD+"</td>" +
                                "<td style='border: 1px solid #02497f;'>"+val.ORGNAME+"</td>" +
                                "</tr>"
                        });
                        data_inps += "</tbody></table></div>";
                        var total = (tot).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

                        $('#resultINPSModal').modal('show');
                        $("#resultDataINPS").html(data_inps);
                        $("#resultDataINPSTotal").html("Total: "+total);
                    })
                });

                $('#successLoan').hide();

                $('#sendLoan').click(function () {

                    $('#btn-save-send').val("sendLoan");

                    $('#sendForm').trigger("reset");

                    $('#modalHeader').html("Ariza yuborish");
                    $('#modalBody').html("Arizani administratorga yuborish");

                    $('#modalFormSend').modal('show');
                });

                $('#sendReLoan').click(function () {

                    $('#btn-save-send').val("sendLoan");

                    $('#sendForm').trigger("reset");

                    $('#modalHeader').html("Arizani qayta yuborish");
                    $('#iabs_num').val({{ $model->iabs_num }});
                    $('#modalBody').html("Arizani qayta administratorga yuborish");

                    $('#modalFormSend').modal('show');
                });

                if ($("#sendForm").length > 0) {

                    $("#sendForm").validate({

                        submitHandler: function (form) {

                            var actionType = $('#btn-save-send').val();

                            $('#btn-save-send').html('Sending..');

                            $.ajax({
                                data: $('#sendForm').serialize(),

                                url: "{{ url('/uw/cs-app-send') }}",

                                type: "POST",

                                dataType: 'json',

                                success: function (data) {

                                    $('#sendForm').trigger("reset");

                                    $('#modalFormSend').modal('hide');

                                    $('#modalEndSuccess').modal('show');

                                    $('#sendLoan').hide();

                                    $('#successLoan').show();

                                    $('#success_header').html('Send');
                                    $('#success_result').html('Ariza yuborildi');

                                    $('#btn-save-send').html('Save Changes');

                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    $('#btn-save-send').html('Save Changes');
                                }
                            });
                        }
                    })
                }

                // file upload
                $('#fileUpload').on('submit', function(event){
                    event.preventDefault();
                    $.ajax({
                        url:"{{ url('uw-client-files/upload') }}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                            if(data.success == true){ // if true (1)
                                location.reload();
                            }
                            $('#message').css('display', 'block');
                            $('#message').html(data.message);
                            $('#message').addClass(data.class_name);
                        }
                    })
                });


                // file delete
                $('.deleteFile').on('click', function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");

                    $('#ConfirmModal').data('id', id).modal('show');
                });

                $('#yesDelete').click(function () {

                    var token = $('meta[name="csrf-token"]').attr('content');

                    var id = $('#ConfirmModal').data('id');

                    $('#fileId_'+id).remove();

                    $.ajax(
                        {
                            url: '/uw-client-file/delete/'+id,
                            type: 'GET',
                            dataType: "JSON",
                            data: {
                                "id": id,
                                "_token": token,
                            },
                            success: function (data)
                            {
                                //console.log(data);
                                $('#successModal').modal('show');
                            }
                        });

                    $('#ConfirmModal').modal('hide');
                });

                iabs_num.oninput = function () {
                    if (this.value.length > 8) {
                        this.value = this.value.slice(0,8);
                    }
                };

                $('body').on('click', '#getUwAppBlank', function () {

                    var claim_id = $('#getUwAppBlank').data('id');

                    $.get('/uw/get-app-blank/' + claim_id, function (data) {

                        $('#appBlankModal').modal('show');
                        $("#appBlankData").html(data);
                    })
                });

            });


            function print(id)
            {
                var divToPrint=document.getElementById(id);

                var newWin=window.open('',id, 'height=700,width=800');

                newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML);
                newWin.document.write('<link rel="stylesheet" href="/admin-lte/dist/css/AdminLTE.min.css" type="text/css" />');
                newWin.document.write('<link rel="stylesheet" href="/admin-lte/bootstrap/css/bootstrap.css" type="text/css" />');
                newWin.document.write('</body></html>');

                newWin.document.close();

            }

        </script>
    </section>
    <!-- /.content -->
@endsection
