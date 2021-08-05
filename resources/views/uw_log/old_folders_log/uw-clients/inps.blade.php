@extends('uw_log.uw.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            KATM
            <small>jadval</small>
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


    </section>
    {"report":{
    "incomes":{
    "INCOME":[{
    "ORG_INN":203752379,"INCOME_SUMMA":5441480,"NUM":1,"PERIOD":"2019-08","ORGNAME":"ТУРОН БАНК"},{
    "ORG_INN":203752379,"INCOME_SUMMA":1.01291784E7,"NUM":2,"PERIOD":"2019-10","ORGNAME":"ТУРОН БАНК"},{
    "ORG_INN":203752379,"INCOME_SUMMA":872845.6,"NUM":3,"PERIOD":"2019-12","ORGNAME":"ТУРОН БАНК"},{
    "ORG_INN":201055108,"INCOME_SUMMA":2.71534032E7,"NUM":4,"PERIOD":"2020-02","ORGNAME":"АКБ Туронбанк"},{
    "ORG_INN":201055108,"INCOME_SUMMA":8751212.8,"NUM":5,"PERIOD":"2020-03","ORGNAME":"АКБ Туронбанк"},{
    "ORG_INN":201055108,"INCOME_SUMMA":5460338.4,"NUM":6,"PERIOD":"2020-04","ORGNAME":"АКБ Туронбанк"},{
    "ORG_INN":201055108,"INCOME_SUMMA":6060384,"NUM":7,"PERIOD":"2020-05","ORGNAME":"АКБ Туронбанк"},{
    "ORG_INN":201055108,"INCOME_SUMMA":6060375.2,"NUM":8,"PERIOD":"2020-06","ORGNAME":"АКБ Туронбанк"},{
    "ORG_INN":201055108,"INCOME_SUMMA":1.82261464E7,"NUM":9,"PERIOD":"2020-07","ORGNAME":"АКБ Туронбанк"},{
    "ORG_INN":201055108,"INCOME_SUMMA":8579102.4,"NUM":10,"PERIOD":"2020-08","ORGNAME":"АКБ Туронбанк"}]},

    "incomes_period":{
    "incomes_period_begin":"2019-09-16","incomes_period_end":"2020-09-16","incomes_all_summa":9.67344664E7},
    "sysinfo":{
    "date":"2020-09-16 12:05:45",
    "bank":"\"ТУРОНБАНК\" АТ БАНКИ",
    "id_demand":"0112020260384146",
    "user_id":"оффлайн",
    "claim_id":1090111,
    "report_name":"Сведения о доходах по ИНПС XML","declaration":"Внимание! Предоставление и использование кредитной информации регулируется Законом Республики Узбекистан \"Об обмене кредитной информацией\" № 301 от 04.10.2011 года.",
    "branch":"01144",
    "claim_date":"2020-09-15",
    "report_code":"025"},
    "presence_reports":{
    "presence_report":[{
    "num":2,"report_name":"Скоринг КИАЦ","presence":"Да"},{
    "num":1,"report_name":"Информация по кредитам","presence":"Да"}]},
    "client":{
    "pinfl":30106911571417,
    "address":"Jizzax viloyati Baxmal tumani IIB",
    "document_number":"0313625",
    "gender":"Ж",
    "document_type_id":6,
    "birth_date":"1996-09-14",
    "name":"Xalbayeva Oygul Ibragim Qizi",
    "inn":544537765,
    "document_date":"2018-11-27",
    "resident":"Резидент",
    "document_type":"Биометрический паспорт гражданина Республики Узбекистан",
    "document_serial":"AC"},
    "notifications":""}}
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box" style="clear: both;">

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-xs-6">
                        <form method="POST" action="{{ url('uw-online-reg-katm') }}" >
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{ $model->id }}">
                            <input type="hidden" name="claim_id" value="{{ $model->claim_id }}">
                                <div class="box-body">
                                    <div class="box-header">
                                        <h3 class="box-title">ИНФОРМАЦИЯ</h3>
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
                                                <td>2</td>
                                                <td>Резидентность</td>
                                                <td>Резидент</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Пол</td>
                                                <td>{{ $model->gender }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Адрес по прописке	</td>
                                                <td>{{ $model->registration_address }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>ПИНФЛ</td>
                                                <td>{{ $model->pin }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>ИНН</td>
                                                <td>{{ $model->inn }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Тип документа</td>
                                                <td>Биометрический паспорт гражданина Республики Узбекистан</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Данные документа</td>
                                                <td>{{ $model->document_serial.' '.$model->document_number.' от ' }}{{ \Carbon\Carbon::parse($model->document_date)->format('d.m.Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Пользователь кредитного отчёта</td>
                                                <td>ТОШКЕНТ Ш., "ТУРОНБАНК" АТ БАНКИНИНГ ЮНУСОБОД ФИЛИАЛИ (оффлайн)</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Кредитная заявка</td>
                                                <td>{{ $model->claim_id.' от ' }}{{ \Carbon\Carbon::parse($model->claim_date)->format('d.m.Y') }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->

                                <div class="box-footer">
                                    <div class="pull-right">
                                        <a href="{{ url('uw-clients') }}" class="btn btn-default"><i class="fa fa-remove"></i> Bekor
                                            qilish
                                        </a>
                                        <button type="submit" class="btn btn-primary prg">(1) @lang('blade.send')</button>
                                    </div>
                                </div>
                                <!-- /.box-footer -->
                        </form>

                        </div>

                        <div class="col-xs-6">
                            <div class="box box-widget widget-user-2">
                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                <div class="widget-user-header bg-aqua-active">
                                    <!-- /.widget-user-image -->
                                    <h3 class="widget-user-username">KATM Result</h3>
                                    <h5 class="widget-user-desc">online</h5>
                                </div>
                                <div class="box-footer no-padding">
                                    <ul class="nav nav-stacked">
                                        <li><a href="#">Projects <span class="pull-right badge bg-blue">31</span></a></li>
                                        <li><a href="#">Tasks <span class="pull-right badge bg-aqua">5</span></a></li>

                                    </ul>
                                </div>
                            </div>
                            <div class="box-header with-border">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-flat bg-aqua-active" id="postClientKatmToken"
                                            data-id="{{ $model->claim_id }}">(2) KATM (token)
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-flat btn-primary" id="getResultKATMBefore"
                                            data-id="{{ $model->claim_id }}">(3) Get Result
                                    </button>

                                    <button type="button" class="btn btn-flat btn-success" id="getResultKATM"
                                            data-id="{{ $model->claim_id }}"><i class="fa fa-search-plus"></i>(4) Show Result
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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

                    {{--create/updte Result KATM--}}
                    <div class="modal fade" id="resultKATMModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title text-center" id="success">KATM Result</h4>
                                </div>
                                <div id="reportBase64Modal"></div>
                                <form id="roleForm14" name="roleForm14">
                                    <div class="modal-body">
                                        <input type="hidden" name="claim_id" id="katmClaimId">
                                        <input type="hidden" name="katmSumm" id="katmSumm" value="">
                                    </div>
                                    <div class="modal-footer">
                                        <center>
                                            <button type="submit" class="btn btn-flat btn-lg bg-aqua-active" id="postKatmSumm"
                                                    value="create"><i class="glyphicon glyphicon-check"></i> Natijani tasdiqlash
                                            </button>
                                        </center>
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
                                    <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> Role serverdan o`chiriladi!</h4>
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

                    <!--token error message-->
                    <div class="modal fade modal-warning" id="modalTokenError" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <h4 class="modal-title" id="tokenError_header"></h4>
                                </div>
                                <div class="modal-body">
                                    <h5 id="tokenError_result"></h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" data-dismiss="modal">@lang('blade.close')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--token success message-->
                    <div class="modal fade modal-primary" id="modalTokenSuccess" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="tokenSuccess_header"></h4>
                                </div>
                                <div class="modal-body">
                                    <h5 id="tokenSuccess_result"></h5>
                                    <h4 class="text-bold"><i class="glyphicon glyphicon-info-sign"></i> KATM Tokenni tasdiqlang!</h4>
                                </div>
                                <form id="tokenSuccessForm" name="tokenSuccessForm">
                                        <input type="hidden" id="tokenSuccess_code" value="">
                                        <input type="hidden" id="tokenSuccess_token" value="">
                                        <input type="hidden" id="tokenSuccess_claim_id" value="">
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-outline" id="btn-save"
                                                value="create">Tasdiqlash
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!--token base64 insert datatable-->
                    <div class="modal fade modal-primary" id="getKATMForm" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="base64Success_header"></h4>
                                </div>
                                <div class="modal-body">
                                    <h5 id="base64Success_result"></h5>
                                    <h4 class="text-bold"><i class="glyphicon glyphicon-info-sign"></i> KATM Natijasini tasdiqlang!</h4>
                                </div>
                                <form id="roleGetKATMForm" name="roleGetKATMForm">
                                        <input type="hidden" name="res_uw_clients_id" id="res_uw_clients_id" value="{{ $model->id }}">
                                        <input type="hidden" name="res_claim_id" id="res_claim_id" value="">
                                        <input type="hidden" name="res_reportBase64" id="res_reportBase64" value="">
                                        <input type="hidden" name="res_katm_code" id="res_result" value="">

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-outline" id="res_katm-btn-save"
                                                value="create">Tasdiqlash
                                        </button>
                                    </div>
                                </form>
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

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <style type="text/css">
            table{ border-collapse:collapse; width:100%; }
            table td, th{ border:1px solid #d0d0d0; }
        </style>
        <style type="text/css">
            #main_block table {font-size:9px;}
            .header-color {background-color:#8EB2E2;}
            .mip-color {background-color:#e3fbf7;}
            .procent25_class {width:25%;}
        </style>
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

        <script>

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

                $('body').on('click', '#editRole', function () {

                    var model_id = $(this).data('id');

                    $.get('/roles/' + model_id, function (data) {

                        $('#modalHeader').html("Edit role");

                        $('#btn-save').val("editRole");

                        $('#modalForm').modal('show');

                        $('#model_id').val(model_id);

                        $('#title').val(data.title);

                        $('#titleRu').val(data.title_ru);

                        $('#roleCode').val(data.role_code);

                    })
                });

                $('body').on('click', '#getSumm', function () {

                    var summ = $('#empty').text();
                    var a = 'Test123*** TEST';
                    var b = summ.replace(/[^a-z0-9]/gi,'');

                    alert(b);
                });
                $('#getResultKATM').hide();
                $('body').on('click', '#getResultKATMBefore', function () {

                    var cid = $('#getResultKATM').data('id');

                    $.get('/uw/get-client-katm/' + cid, function (data) {
                        var decodedString = atou(data.reportBase64);
                        var summ = $('#empty').text();
                        $('#reportBase64Modal').prepend(decodedString);

                        $('#getResultKATMBefore').hide();
                        $('#getResultKATM').show();
                        $('#resultKATMModal').modal('hide');
                        $('#reportBase64Modal').empty();


                    })
                });

                $('body').on('click', '#getResultKATM', function () {

                    var cid = $('#getResultKATM').data('id');

                    $.get('/uw/get-client-katm/' + cid, function (data) {
                        //console.log(data);
                        var decodedString = atou(data.reportBase64);
                        var summ = $('#empty').text();

                        $('#modalHeader').html("Edit role");

                        $('#reportBase64Modal').prepend(decodedString);

                        $('#btn-save').val("editRole");

                        $('#katmClaimId').val(cid);

                        $('#katmSumm').val(summ.replace(/[^a-z0-9]/gi,''));

                        $('#titleRu').val(data.title_ru);

                        $('#roleCode').val(data.role_code);

                        $('#resultKATMModal').modal('show');



                    })
                });

                $('#postClientKatmToken').click(function () {
                    var claimId = $('#postClientKatmToken').data('id');
                    $.ajax(
                        {
                            type: "POST",
                            url: "http://10.22.50.3:8001/katm-api/v1/credit/report",
                            data: '{ "security": {' +
                                '"pLogin" : "turonbank", "pPassword" : "!trB&GkL@200130"}, ' +
                                '"data": {"pHead" : "011", "pCode" : "01144", "pLegal" : 1, "pClaimId" : "'+claimId+'", "pReportId" : 1, "pReportFormat" : 0 } }',
                            dataType : "json",
                            contentType: "application/json",
                            processData: false,
                            success: function (data) {
                                console.log(data);
                                if(data.code===200){
                                    if (data.data.result === '05002'){
                                        $('#tokenError_header').html('Xatolik mavjud !');
                                        $('#tokenError_result').html(data.data.result+' - '+data.data.resultMessage);
                                        $('#modalTokenError').modal('show');
                                    }
                                    else if(data.data.result === '05050'){
                                        $('#tokenSuccess_header').html('Token Success !');
                                        $('#tokenSuccess_result').html(data.data.result+' - '+data.data.resultMessage);
                                        $('#tokenSuccess_claim_id').val(claimId);
                                        $('#tokenSuccess_code').val(data.data.result);
                                        $('#tokenSuccess_token').val(data.data.token);
                                        $('#modalTokenSuccess').modal('show');
                                    } else {
                                        $('#result').html(data.data.result);
                                        $('#resultMessage').html(data.data.resultMessage);
                                        $('#code').val(data.code);
                                        $('#claim_id').val(claimId);
                                        $('#token').val(data.data.token);
                                        $('#modalForm').modal('show');
                                        $('#success').html('Data added successfully !');

                                    }
                                }
                                else if(dataResult.code==201){
                                    alert("Error occured !");
                                }


                            }
                        });

                    $('#ConfirmModal').modal('hide');
                });

/*                $('#getKatm').click(function () {

                    var token = $('meta[name="csrf-token"]').attr('content');

                    var id = $('#ConfirmModal').data('id');

                    var claimId = "004010";

                    $.ajax(
                        {
                            type: "POST",
                            url: "http://10.22.50.3:8001/katm-api/v1/credit/report/status",
                            data: '{ "security": {' +
                                '"pLogin" : "turonbank", "pPassword" : "!trB&GkL@200130"}, ' +
                                '"data": {"pHead" : "011", "pCode" : "00446", "pToken" : "ahnwwewsigvqddyydskncwlduocmkezj", "pLegal" : 1, "pClaimId" : '+claimId+', "pReportId" : 1, "pReportFormat" : 0 } }',

                            dataType : "json",

                            contentType: "application/json",

                            processData: false,

                            success: function (data) {

                                console.log(data);
                                if(data.code===200){
                                    $('#res_resultMessage').html(data.data.resultMessage);
                                    $('#res_reportBase64').val(data.data.reportBase64);
                                    $('#res_claim_id').val(data.data.result);
                                    $('#res_result').val(data.data.result);
                                    $('#getKATMForm').modal('show');
                                    $('#success').html('Data added successfully !');
                                }
                                else if(dataResult.code==201){
                                    alert("Error occured !");
                                }


                            }
                        });

                    $('#ConfirmModal').modal('hide');
                });*/

                // insert katm data (datatable)
                if ($("#tokenSuccessForm").length > 0) {

                    $("#tokenSuccessForm").validate({
                        submitHandler: function (form) {
                            var actionType = $('#btn-save').val();
                            $('#btn-save').html('Sending..');
                            var k_token = $("#tokenSuccess_token").val();
                            var claimId = $("#tokenSuccess_claim_id").val();
                            console.log('my-tok: '+claimId);
                            $.ajax({
                                type: "POST",
                                url: "http://10.22.50.3:8001/katm-api/v1/credit/report/status",
                                data: '{ "security": {' +
                                    '"pLogin" : "turonbank", "pPassword" : "!trB&GkL@200130"}, ' +
                                    '"data": {"pHead" : "011", "pCode" : "01144", "pToken" : "'+k_token+'", "pLegal" : 1, "pClaimId" : "'+claimId+'", "pReportId" : 1, "pReportFormat" : 0 } }',

                                dataType : "json",
                                contentType: "application/json",
                                processData: false,
                                success: function (data) {
                                    console.log(data);
                                    if(data.code===200){
                                        if (data.data.result === '05002'){
                                            $('#tokenError_header').html('Xatolik mavjud !');
                                            $('#tokenError_result').html(data.data.result+' - '+data.data.resultMessage);
                                            $('#modalTokenSuccess').modal('hide');
                                            $('#modalTokenError').modal('show');
                                        }
                                        else if(data.data.result === '05000'){
                                            $('#base64Success_header').html('Result Success!');
                                            $('#base64Success_result').html(data.data.result+' - '+data.data.resultMessage);
                                            $('#res_reportBase64').val(data.data.reportBase64);
                                            $('#res_claim_id').val(claimId);
                                            $('#res_result').val(data.data.result);
                                            $('#modalTokenSuccess').modal('hide');
                                            $('#getKATMForm').modal('show');

                                        } else {

                                            $('#res_resultMessage').html(data.data.resultMessage);
                                            $('#res_reportBase64').val(data.data.reportBase64);
                                            $('#res_claim_id').val(claimId);
                                            $('#res_result').val(data.data.result);
                                            $('#token').val(data.data.token);
                                            $('#modalTokenSuccess').modal('hide');
                                            $('#getKATMForm').modal('show');
                                            $('#success').html('Data added successfully !');

                                        }
                                    }
                                    else if(dataResult.code==201){
                                        alert("Error occured !");
                                    }
                                }
                            });
                        }
                    })
                }

                // insert Summ
                if ($("#roleForm14").length > 0) {

                    $("#roleForm14").validate({
                        submitHandler: function (form) {
                            var actionType = $('#postKatmSumm').val();
                            $('#postKatmSumm').html('Sending..');
                            $.ajax({

                                data: $('#roleForm14').serialize(),
                                url: "{{ route('katm-insert-summ') }}",

                                type: "POST",

                                dataType: 'json',

                                success: function (data) {

                                    console.log(data);
                                    $('#resultKATMModal').modal('hide');
                                    $('#successModal').modal('show');
                                }
                            });
                        }
                    })
                }

                if ($("#roleGetKATMForm").length > 0) {

                    $("#roleGetKATMForm").validate({

                        submitHandler: function (form) {

                            var actionType = $('#res_katm-btn-save').val();

                            $('#res_katm-btn-save').html('Sending..');

                            $.ajax({
                                data: $('#roleGetKATMForm').serialize(),

                                url: "{{ url('uw/data-katm') }}",

                                type: "POST",

                                dataType: 'json',

                                success: function (data) {

                                   console.log(data);

                                    $('#roleGetKATMForm').trigger("reset");

                                    $('#getKATMForm').modal('hide');

                                    $('#success_header').html('KAtm Success!');

                                    $('#success_result').html('KATM Mijoz kredit tarixi yakunlandi');

                                    $('#modalEndSuccess').modal('show');

                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    $('#btn-save').html('Save Changes');
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
