@extends('layouts.uw.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Kredit natijasi
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">underwriter</a></li>
            <li class="active">underwriter</li>
        </ol>

        @if (Session::has('message'))
            <div class="alert alert-{{ Session::get('status') }} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-{{ Session::get('status') }}"></i> Message!</h4>
                {{ Session::get('message') }}
            </div>
        @endif

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary" style="clear: both">
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div id="loading-gif" class="loading-gif" style="display: none"></div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="col-md-12 bg-gray">
                                    <div class="box-body">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th colspan="3"><i class="fa fa-user"></i> Mijoz pasport ma`lumotlari</th>
                                            </tr>
                                            <tr>
                                                <td>1.</td>
                                                <td>FIO</td>
                                                <td>{{ $model->family_name ?? ''}} {{ $model->name ?? ''}} {{ $model->patronymic??'' }}</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>Tug`ilgan yili</td>
                                                <td><i class="fa fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($model->birth_date)->format('d.m.Y') }} yil.
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3.</td>
                                                <td>Jinsi</td>
                                                <td><i class="fa fa-child"></i>
                                                    {{ $model->gender == 1 ? 'Erkak' : 'Ayol' }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4.</td>
                                                <td>Pasport ma`lumotlari</td>
                                                <td><i class="fa fa-user"></i>
                                                    {{ $model->document_serial ?? ''}} {{ $model->document_number ?? ''}}
                                                    {{ \Carbon\Carbon::parse($model->document_date)->format('d.m.Y') }} yil.
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5.</td>
                                                <td>Pasport berilgan joyi</td>
                                                <td><i class="fa fa-globe"></i>
                                                    {{ \Carbon\Carbon::parse($model->document_date)->format('d.m.Y') }} yilda
                                                    {{ $model->region1->name?? '' }} {{ $model->docDistrict->name?? '1'}}  <br>
                                                    IIB tomonidan berilgan
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6.</td>
                                                <td>Yashash joy ma`lumotlari</td>
                                                <td><i class="fa fa-globe"></i>
                                                    {{ $model->region->name?? ''}}, {{ $model->district->name?? ''}}
                                                    {{ $model->registration_address ?? ''}}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7.</td>
                                                <td>Telefon raqam</td>
                                                <td><i class="fa fa-phone"></i>
                                                    {{ $model->phone }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>8.</td>
                                                <td>Mijoz INPS</td>
                                                <td><i class="fa fa-user"></i>
                                                    {{ $model->pin }}
                                                    <span class="text-danger text-bold">
                                                        @if($model->is_inps == 1)
                                                            Tashkilot xodimi (INPS bor)
                                                        @else
                                                            Nafaqada || Organ xodimi (INPS yo`q)
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>9.</td>
                                                <td>Mijoz INN</td>
                                                <td>
                                                    {{ $model->inn }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>10.</td>
                                                <td>Mijoz ish joy manzili</td>
                                                <td><i class="fa fa-map-marker"></i>
                                                    {{ $model->job_address }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                            </tbody></table>
                                    </div>
                                </div>

                                <div class="col-md-12 bg-danger">
                                    <div class="box-body">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th colspan="3"><i class="fa fa-credit-card"></i> Kredit ma`lumotlari</th>
                                            </tr>
                                            <tr>
                                                <td>1.</td>
                                                <td>Kredit turi</td>
                                                <td class="text-bold">{{ $model->loanType->title ?? '' }}</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>Kredit davri</td>
                                                <td class="text-bold"><i class="fa fa-hourglass-1"></i>
                                                    {{ $model->loanType->credit_duration ?? '' }} oy.
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>Kredit imtiyozli davr</td>
                                                <td class="text-bold"><i class="fa fa-hourglass-2"></i> {{ $model->loanType->credit_exemtion ?? '' }} oy.</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3.</td>
                                                <td>Kredit %</td>
                                                <td class="text-bold"><i class="fa fa-balance-scale"></i> {{ $model->loanType->procent ?? '' }} %</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4.</td>
                                                <td>Kredit summasi</td>
                                                <td class="text-bold"><i class="fa fa-cc"></i>
                                                    {{ number_format($model->summa ?? '')}} so`m.
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5.</td>
                                                <td>Ariza raqami</td>
                                                <td class="text-bold"><i class="fa fa-list"></i>
                                                    {{ $model->claim_id ?? ''}}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6.</td>
                                                <td>Ariza sanasi</td>
                                                <td class="text-bold"><i class="fa fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($model->claim_date)->format('d.m.Y') }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7.</td>
                                                <td>IABS unikal kodi</td>
                                                <td class="text-bold"><i class="fa fa-internet-explorer"></i>
                                                    {{ $model->iabs_num ?? ''}}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                            </tbody></table>
                                    </div>

                                </div>

                                <div class="col-md-12 bg-blue">
                                    <div class="box-body">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th colspan="3"><i class="fa fa-bank"></i> Filial (BXO) ma`lumotlari</th>
                                            </tr>
                                            <tr>
                                                <td>1.</td>
                                                <td>Bank MFO</td>
                                                <td class="text-bold">({{ $model->branch_code }}) {{ $model->filial->title ?? '' }}</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>BXO</td>
                                                <td class="text-bold">{{ $model->uwUser->bxo->title ?? '' }}</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3.</td>
                                                <td>Kredit Inspektor FIO</td>
                                                <td class="text-bold">
                                                    {{ $model->user->lname ?? '' }} {{ $model->user->fname ?? '' }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4.</td>
                                                <td>Ariza yuborgan sanasi</td>
                                                <td class="text-bold"><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($model->updated_at)->format('d.m.Y H:i:s') }}</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                            </tbody></table>
                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="box-header with-border">
                                            <h4 class="text-danger"><i class="fa fa-child"></i> Qo`shimcha qarzdor ma`lumotlari</h4>
                                        </div>
                                        <table class="table table-striped table-bordered" id="debtors_datatable">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Qo`shimcha qarzdor FIO</th>
                                                <th>STIR</th>
                                                <th>Ish joy manzili</th>
                                                <th>Jami oylik daromadi</th>
                                                <th>Jami (Oy)da</th>
                                                <th>To`lov qobiliyati</th>
                                            </tr>
                                            </thead>
                                            <tfoot align="right" class="bg-danger">
                                            <tr>
                                                <th colspan="4">Jami:</th>
                                                <th colspan="2"></th>
                                                <th colspan="1"></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="box-header with-border">
                                            <h4 class="text-danger"><i class="fa fa-child"></i> Kafil ma`lumotlari</h4>
                                        </div>
                                        <table class="table table-striped table-bordered" id="guar_datatable">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Guar type</th>
                                                <th>Title</th>
                                                <th>Guar owner</th>
                                                <th>Guar sum</th>
                                                <th>Address</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="box-header with-border">
                                            <h4 class="text-primary"><i class="fa fa-paperclip"></i> Ilovala hujjatlari</h4>
                                        </div>
                                        <table class="table table-striped table-bordered" id="file_datatable">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>File size</th>
                                                <th>View</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="box box-widget widget-user-2">
                                        <div class="widget-user-header bg-aqua-active">
                                            <h3>KATM so`rovi natijasi</h3>
                                            <h5 class="widget-user-desc">online request</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="katm_inps_buttons"></div>
                                    </div>
                                    <div class="box box-widget widget-user-2">
                                        <div class="widget-user-header bg-blue-active">
                                            <h3 class="widget-user-username">Ball: <span id="scoring_ball"></span> <i class='fa fa-line-chart'></i> Kredit ajratish natijasi</h3>
                                        </div>
                                        <div class="box-footer no-padding">
                                            <ul class="nav nav-stacked">
                                                <li><a href="#">1. Kredit qarzdorligi:
                                                        <span class="pull-right badge bg-red-active" style="font-size: large">
                                                            <span id="credit_debt"></span> so`m
                                                        </span>
                                                    </a>
                                                </li>
                                                <li><a href="#">2. Jami oylik ish xaqi:
                                                        <span class="pull-right badge bg-light-blue">
                                                            <span id="total_month_salary"></span> so`m <span id="total_monthly"></span> oyda
                                                        </span>
                                                    </a>
                                                </li>
                                                <li><a href="#">3. Xar oy to`lov qobiliyati:
                                                        <span class="pull-right badge bg-danger" style="font-size: large">
                                                            <span id="total_month_payment"></span> so`m
                                                        </span>
                                                    </a>
                                                </li>
                                                <li><a href="#">4. Mijoz so`ragan kredit:
                                                        <span class="pull-right badge bg-gray-active">
                                                            <span id="credit_sum"></span> so`m
                                                        </span>
                                                    </a>
                                                </li>
                                                <li><a href="#">5. Kredit ajratish mumkin:
                                                        <span class="pull-right badge bg-aqua-active" style="font-size: large">
                                                            <span id="credit_can_be"></span> so`m
                                                        </span>
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="box-header with-border">
                                        @switch($model->status)
                                            @case(2)
                                            @switch(Auth::user()->uwUsers())
                                                @case('super_admin')
                                                @case('risk_adminstrator')
                                                <div id="confirmButtons">
                                                    <button class='btn btn-flat btn-info margin' id='confirmLoan' data-id='{{ $model->id }}'>
                                                        <i class='fa fa-check-circle-o'></i> Tasdiqlash
                                                    </button>

                                                    <button class='btn btn-flat btn-warning margin' id='cancelLoan' data-id='{{ $model->id }}'>
                                                        <i class='fa fa-pencil'></i> Taxrirlashga qayta yuborish
                                                    </button>
                                                </div>
                                                @break
                                            @endswitch
                                            @break
                                            @case(3)
                                            <h3 class="text-green"><i class="fa fa-check-circle-o"></i> Ariza Tasdiqlandi</h3>
                                            @break
                                            @case(0)
                                            <h3 class="text-maroon"><i class="fa fa-pencil"></i> Ariza Taxrirlashda</h3>
                                            @break
                                        @endswitch
                                        @if($modelComments)
                                            <h4 class="text-danger"><i class="fa fa-commenting"></i> Izoxlar tarixi</h4>
                                            @foreach($modelComments as $key => $value)
                                                <div class="comment-text">
                                                          <span class="username">
                                                              <b>{{ $key+1 }}.</b>
                                                            <span class="text-muted pull-right"><i class="fa fa-clock-o"></i>
                                                                {{ \Carbon\Carbon::parse($value->created_at)->format('d.m.Y H:i') }}</span>
                                                          </span><!-- /.username -->
                                                    {{ $value->title }}
                                                </div>
                                            @endforeach

                                        @endif
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->

        </div>

        <!--confirm form-->
        <div class="modal fade modal-primary" id="modalFormCancel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalHeader"></h4>
                    </div>
                    <div class="modal-body">
                        <h4><span class="glyphicon glyphicon-comment"></span> <span id="modalBody"></span></h4>
                    </div>
                    <form id="cancelForm" name="cancelForm">
                        <input type="hidden" name="uw_clients_id" id="uw_clients_id" value="{{ $model->id }}">
                        <div class="box-body bg-primary">
                            <input name="descr" class="form-control input-lg" type="text" placeholder="Izoh qoldiring..." required>
                        </div>
                        <div class="modal-footer">
                            <center>
                                <button type="submit" class="btn btn-outline" id="btn-save-cancel"
                                        value="create">Ha, Taxrirlash
                                </button>
                                <button type="button" class="btn btn-outline pull-left"
                                        data-dismiss="modal">@lang('blade.cancel')</button>
                            </center>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!--confirm form-->
        <div class="modal fade modal-primary" id="modalFormConfirm" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-check-circle-o"></i>  <span id="modalHeaderConf"></span></h4>
                    </div>
                    <div class="modal-body">
                        <h4><span class="glyphicon glyphicon-info-sign"></span> <span id="modalBodyConf"></span></h4>
                    </div>
                    <form id="confirmForm" name="confirmForm">
                        <input type="hidden" name="uw_clients_id" id="uw_clients_id" value="{{ $model->id }}">
                        <div class="modal-footer">
                            <center>
                                <button type="submit" class="btn btn-outline" id="btn-save-confirm"
                                        value="create">Ha, Tasdiqlash
                                </button>
                                <button type="button" class="btn btn-outline pull-left"
                                        data-dismiss="modal">@lang('blade.cancel')</button>
                            </center>
                        </div>

                    </form>
                </div>
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

        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

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
            table{ border-collapse:collapse; width:100%; }
            table td, th{ border:1px solid #d0d0d0; }

            #main_block table {font-size:9px;}
            .header-color {background-color:#8EB2E2;}
            .mip-color {background-color:#e3fbf7;}
            .procent25_class {width:25%;}
        </style>

        <script>
            var resultButton = $("#katm_inps_buttons");
            var sendToAdminButton = $("#confirm_admin_buttons");
            var id = "{{ $model->id }}";

            var button_res_k = "<button class='btn btn-flat btn-bitbucket margin' id='getResultKATM' data-id='"+id+"'><i class='fa fa-history'></i> KATM natijasi</button>";

            var button_res_i = "<button class='btn btn-flat btn-bitbucket margin' id='getResultINPS' data-id='"+id+"'><i class='fa fa-credit-card'></i> INPS natijasi</button>";

            var katm_inps_route = "{{ route('uw.get-result-buttons', ['id' => $model->id]) }}";

            $.get(katm_inps_route, function(res){
                console.log(res);
                $('#credit_can_be').append(formatCurrency(res.credit_results.credit_can_be));
                $('#credit_sum').append(formatCurrency(res.credit_results.credit_sum));
                $('#total_month_payment').append(formatCurrency(res.credit_results.total_month_payment));
                $('#total_month_salary').append(formatCurrency(res.credit_results.total_month_salary));
                $('#total_monthly').append(res.credit_results.total_monthly);
                $('#scoring_ball').append(res.credit_results.scoring_ball);
                if (res.data_k !== null) {
                    resultButton.append(button_res_k);
                    $('#credit_debt').append(formatCurrency(res.credit_results.credit_debt));
                }
                if (res.data_i.length > 0) {
                    resultButton.append(button_res_i);
                }

            });

            function formatCurrency(total) {
                var neg = false;
                if(total < 0) {
                    neg = true;
                    total = Math.abs(total);
                }
                return (neg ? "-" : '') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
            }

            $(document).ready( function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // BUTTON GET KATM RESULT
                $('body').on('click', '#getResultKATM', function () {

                    var id = $('#getResultKATM').data('id');

                    $.get('/uw/get-client-res-k/' + id, function (data) {
                        console.log(data);

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
                        if (data.scoring.client_info_7){
                            $(".client_info_7").html(data.scoring.client_info_7);
                        }

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

                        $('#tb_row_12_agr_summ').html(data.table.row_12.agr_summ);
                        $('#tb_row_12_agr_comm2').html(data.table.row_12.agr_comm2.content);
                        $('#tb_row_12_agr_comm3').html(data.table.row_12.agr_comm3);
                        $('#tb_row_12_agr_comm4').html(data.table.row_12.agr_comm4);

                        $('#btn-save').val("KatmResult");

                        $('#katmClaimId').val(id);

                        $('#resultKATMModal').modal('show');
                    })
                });

                // BUTTON GET INPS RESULT
                $('body').on('click', '#getResultINPS', function () {

                    var id = $('#getResultINPS').data('id');

                    $.get('/uw/get-client-res-i/' + id, function (data) {
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

                // request DEBTORS
                $('#debtors_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    paginate: false,
                    searching: false,
                    bInfo: false,
                    ajax: {
                        url: "{{ url('uw-debtors', ['id' => $model->id]) }}",
                        type: 'GET',
                    },
                    columns: [
                        { data: 'id', name: 'id', 'visible': true, "searchable": false},
                        { data: null,
                            render: function ( data, type, row ) {
                                return data.family_name +' '+ data.name;
                            }
                        },
                        { data: 'inn', name: 'inn' },
                        { data: 'live_address', name: 'live_address' },
                        { data: null,
                            render: function ( data, type, row ) {
                                return formatCurrency(data.total_sum);
                            }
                        },
                        { data: 'total_month', name: 'total_month' },
                        { data: null,name: 'total',
                            render: function ( data, type, row ) {
                                var payment = (data.total_sum/data.total_month*0.87)*0.5;
                                return formatCurrency(payment);
                            }
                        },
                    ],
                    footerCallback: function (row, data, start, end, display) {
                        console.log(data)
                        var totalAmount = 0;
                        for (var i = 0; i < data.length; i++) {
                            totalAmount += parseFloat(data[i]['total_sum']);
                        }
                        var totalPayment = 0;
                        for (var j = 0; j < data.length; j++) {
                            totalPayment += (parseFloat(data[j]['total_sum'])*0.87)/parseFloat(data[j]['total_month'])*0.5;
                        }
                        var api = this.api();
                        $(api.column(4).footer()).html(formatCurrency(totalAmount));
                        $(api.column(6).footer()).html(formatCurrency(totalPayment));
                    },
                    order: [[0, 'desc']]
                });

                // request Guards
                $('#guar_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    paginate: false,
                    searching: false,
                    bInfo: false,
                    ajax: {
                        url: "{{ route('uw.get-client-guars', ['id' => $model->id]) }}",
                        type: 'GET',
                    },
                    columns: [
                        { data: 'id', name: 'id', 'visible': true, "searchable": false},
                        { data: 'guar_type', name: 'guar_type' },
                        { data: 'title', name: 'title' },
                        { data: 'guar_owner', name: 'guar_owner' },
                        { data: 'guar_sum', name: 'guar_sum' },
                        { data: 'address', name: 'guar_sum' },
                    ],
                    order: [[0, 'desc']]
                });

                // request Files
                $('#file_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    paginate: false,
                    searching: false,
                    bInfo: false,
                    ajax: {
                        url: "{{ route('uw.get-client-files', ['id' => $model->id]) }}",
                        type: 'GET',
                    },
                    columns: [
                        { data: 'id', name: 'id', 'visible': true, "searchable": false},
                        { data: 'file_name', name: 'file_name' },
                        { data: 'file_size', name: 'file_size' },
                        { data: 'view', name: 'view', orderable: false},
                    ],
                    order: [[0, 'desc']]
                });



                $('#confirmLoan').click(function () {

                    $('#btn-save-confirm').val("confirmLoan");

                    $('#confirmForm').trigger("reset");

                    $('#modalHeaderConf').html("Tasdiqlash");
                    $('#modalBodyConf').html("Arizani to`g`riligini tasdiqlash");

                    $('#modalFormConfirm').modal('show');
                });

                $('#cancelLoan').click(function () {

                    $('#btn-save-cancel').val("cancelLoan");

                    $('#cancelForm').trigger("reset");

                    $('#modalHeader').html("Taxrirlash");
                    $('#modalBody').html("Arizani Taxrirlashga yuborish");

                    $('#modalFormCancel').modal('show');
                });

                if ($("#confirmForm").length > 0) {

                    $("#confirmForm").validate({

                        submitHandler: function (form) {

                            var actionType = $('#btn-save-confirm').val();

                            $('#btn-save-confirm').html('Sending..');

                            $.ajax({
                                data: $('#confirmForm').serialize(),

                                url: "{{ url('/uw/risk-admin-confirm') }}",

                                type: "POST",

                                dataType: 'json',
                                beforeSend: function(){
                                    $("#loading-gif").show();
                                },
                                success: function (data) {
                                    $("#loading-gif").hide();
                                    $('#confirmForm').trigger("reset");
                                    $('#modalFormConfirm').modal('hide');
                                    $('#confirmButtons').empty().append('<h3 class="text-green"><i class="fa fa-check-circle-o"></i> Ariza tasdiqlandi</h3>');
                                    $('#btn-save-confirm').html('Save Changes');

                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    $('#btn-save-confirm').html('Save Changes');
                                }
                            });
                        }
                    })
                }

                if ($("#cancelForm").length > 0) {

                    $("#cancelForm").validate({

                        submitHandler: function (form) {

                            var actionType = $('#btn-save-cancel').val();

                            $('#btn-save-cancel').html('Sending..');

                            $.ajax({
                                data: $('#cancelForm').serialize(),
                                url: "{{ url('/uw/risk-admin-cancel') }}",
                                type: "POST",
                                dataType: 'json',
                                beforeSend: function(){
                                    $("#loading-gif").show();
                                },
                                success: function (data) {
                                    $("#loading-gif").hide();
                                    $('#cancelForm').trigger("reset");
                                    $('#modalFormCancel').modal('hide');

                                    $('#confirmButtons').empty().append('<h3 class="text-maroon"><i class="fa fa-pencil"></i> Ariza taxrirlashga yuborildi</h3>');

                                    $('#btn-save-cancel').html('Save Changes');

                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    $('#btn-save-confirm').html('Save Changes');
                                }
                            });
                        }
                    })
                }

            });

        </script>
    </section>
    <!-- /.content -->
@endsection
