@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            @lang('blade.users')
            <small>@lang('blade.users') @lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.users')</a></li>
            <li class="active">index</li>
        </ol>

    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <div class="box-header">
                            <div class="col-md-1">
                                <a href="{{ route('users.index') }}" class="btn btn-flat btn-info">
                                    <i class="fa fa-users"></i> @lang('blade.users')
                                </a>
                            </div>
                        </div>

                        <div class="container h-100">
                            <div class="row h-100 justify-content-center align-items-center">
                                <form class="col-12">
                                    <div class="form-group">
                                        <div class="col-sm-3 col-xs-offset-3">
                                            <input type="search" class="form-control" id="emp_code" placeholder="% CARD_NUM, FULL_NAME">
                                        </div>

                                        <div class="col-sm-2">
                                            <button id="search" class="btn btn-success btn-flat"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>

                        <div id="loading" class="loading-gif" style="display: none"></div>

                        <div class="box-body">
                            <b>@lang('blade.overall') @lang('blade.group_edit_count').</b>
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp ID</th>
                                    <th>TabNum</th>
                                    <th>F.I.O.</th>
                                    <th>Holati</th>
                                    <th>Filial</th>
                                    <th>D.Code</th>
                                    <th>D.Nomi</th>
                                    <th>Ish.Joyi</th>
                                    <th>Lavozimi</th>
                                    <th>Lav.Sana</th>
                                </tr>
                                </thead>
                                <tbody class="data-table">
                                </tbody>
                            </table>
                        </div>

                        <div id="DeleteModal" class="modal fade text-danger" role="dialog">
                            <div class="modal-dialog modal-sm">
                                <!-- Modal content-->
                                <form action="" id="deleteForm" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                                        </div>
                                        <div class="modal-body">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <p class="text-center">Siz xodimni o`chirmoqchimisiz?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <center>
                                                <button type="button" class="btn btn-success" data-dismiss="modal">Bekor
                                                    qilish
                                                </button>
                                                <button type="submit" name="" class="btn btn-danger"
                                                        data-dismiss="modal"
                                                        onclick="formSubmit()">Ha, O`chirish
                                                </button>
                                            </center>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </div>

        <script>

            $(function () {

                $(".select2").select2();
            });

            $("#loading").hide();

            $('#emp_code').keypress(function(event){

                var keyCode = (event.keyCode ? event.keyCode : event.which);
                if (keyCode === 13) {

                    $('#search').trigger('click');
                    event.preventDefault();

                }
            });

            $("#search").click(function (event) {
                event.preventDefault();
                let emp_code = $('#emp_code').val();

                let str_upper = emp_code.toUpperCase();

                $.ajax({
                    url: '/madmin/ora-emp-search',
                    type: 'GET',
                    data: {emp_code: str_upper},
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
                                '<td>'+val.emp_id+'</td>' +
                                '<td>'+val.tab_num+'</td>' +
                                '<td><a href="#'+val.emp_id+'">'+val.last_name+' '+val.first_name+' '+val.middle_name+'</a></td>' +
                                '<td>'+val.condition+'</td>' +
                                '<td>'+val.filial+'</td>' +
                                '<td>'+val.department_code+'</td>' +
                                '<td>'+val.department_name+'</td>' +
                                '<td class="text-sm">'+val.work_dep+'</td>' +
                                '<td class="text-sm">'+val.work_post+'</td>' +
                                '<td>'+formatDate(val.begin_work_date)+'</td>' +
                                '</tr>';
                        }
                        if (res.length <= 0){

                            table+=
                                '<tr>' +
                                '<td colspan="10" class="text-center text-danger">Xodim topilmadi qaytadan urinib ko`ring!!!</td>' +
                                '</tr>';

                        }
                        $('.data-table').html(table);

                    },
                    complete:function(res){
                        $("#loading").hide();
                    }

                });

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
