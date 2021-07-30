@extends('layouts.uw.dashboard')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Mijoz yaratish
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">juridical</a></li>
            <li class="active">create</li>
        </ol>

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">
                    <div class="container h-100">
                        <div class="row h-100 justify-content-center align-items-center">
                            <form class="col-12">
                                <div class="form-group">
                                    <div class="col-sm-2">
                                    </div>
                                    <label for="inputName" class="col-sm-2 control-label">Код клиента (IABS#)</label>

                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="client_code" maxlength="8" onkeypress='validate(event)' placeholder="Код клиента">
                                    </div>

                                    <div class="col-sm-2">
                                        <button type="button" id="search" class="btn btn-success btn-flat"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="box-body">
                        <div id="loading" class="loading-gif"></div>
                        <b>@lang('blade.overall') @lang('blade.group_edit_count').</b>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Filial Code</th>
                                <th>Client Code</th>
                                <th>Client Name</th>
                                <th>Client Tin</th>
                                <th>Director Name</th>
                                <th>Region Name</th>
                                <th>District Name</th>
                                <th>Address</th>
                                <th>Date Open</th>
                            </tr>
                            </thead>
                            <tbody class="data-table">
                            </tbody>
                        </table>
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
            function validate(evt) {
                var theEvent = evt || window.event;

                // Handle paste
                if (theEvent.type === 'paste') {
                    key = event.clipboardData.getData('text/plain');
                } else {
                    // Handle key press
                    var key = theEvent.keyCode || theEvent.which;
                    key = String.fromCharCode(key);
                }
                var regex = /[0-9]|\./;
                if( !regex.test(key) ) {
                    theEvent.returnValue = false;
                    if(theEvent.preventDefault) theEvent.preventDefault();
                }
            }
            $("#loading").hide();
            $("#search").click(function () {

                let client_code = $('#client_code').val();

                $.ajax({
                    url: '/jur/ora-search',
                    type: 'GET',
                    data: {client_code: client_code},
                    dataType: 'json',
                    beforeSend: function(){
                        $("#loading").show();
                    },
                    success: function(res){
                        console.log(res)
                        let table = '';
                        let key = 1;
                        for (let i = 0; i < res.length; i++){
                            let val = res[i];

                            table+=
                                '<tr>' +
                                    '<td>'+ key++ +'.</td>' +
                                    '<td>'+val.code_filial+'</td>' +
                                    '<td>'+val.code+'</td>' +
                                    '<td><a href="/jur/view-form/'+val.id+'">'+val.name+'</a></td>' +
                                    '<td>'+val.inn+'</td>' +
                                    '<td>'+val.director_name+'</td>' +
                                    '<td class="text-sm">'+val.reg_name.trim()+'</td>' +
                                    '<td class="text-sm">'+val.dis_name.trim()+'</td>' +
                                    '<td class="text-sm">'+val.address+'</td>' +
                                    '<td>'+formatDate(val.date_open)+'</td>' +
                                '</tr>';
                        }
                        if (res.length <= 0){

                            table+=
                                '<tr>' +
                                '<td colspan="10" class="text-center text-danger">Mijoz topilmadi qaytadan urinib ko`ring!!!</td>' +
                                '</tr>';

                        }
                        $('.data-table').html(table);

                    },
                    complete:function(res){
                        $("#loading").hide();
                    }

                });

            });

            $('#search_tin').keydown(function(event){

                var keyCode = (event.keyCode ? event.keyCode : event.which);
                if (keyCode === 13) {

                    $('#search').trigger('click');

                }
            });

            function formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2)
                    month = '0' + month;
                if (day.length < 2)
                    day = '0' + day;

                return [day, month, year].join('.');
            }



        </script>
    </section>
    <!-- /.content -->
@endsection
