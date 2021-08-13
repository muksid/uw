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

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('blade.error')!</strong> @lang('blade.exist').<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-aqua-active">
                            <h4 class="modal-title">
                                @lang('blade.users') <i class="fa fa-check-circle"></i>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <h5>{{ $message }}</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info closeModal" data-dismiss="modal"><i
                                        class="fa fa-check-circle"></i> Ok
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        @endif

    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <div class="box-header">
                            <div class="col-md-1">
                                <a href="{{ route('users.create') }}" class="btn btn-flat btn-info">
                                    <i class="fa fa-plus-circle"></i> @lang('blade.add')
                                </a>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ url('/madmin/ora-index') }}" class="btn bg-olive btn-flat">
                                    <i class="fa fa-database"></i> @lang('blade.add')
                                </a>
                            </div>
                        </div>

                        <div id="oraTableDiv">
                            <div class="box box-primary">
                                <div class="container h-100">
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <form class="col-12">
                                            <div class="form-group">
                                                <div class="col-sm-3 col-xs-offset-3">
                                                    <input type="text" class="form-control" id="emp_code" placeholder="% CARD_NUM, FULL_NAME">
                                                </div>

                                                <div class="col-sm-2">
                                                    <button type="button" id="search" class="btn btn-success btn-flat"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>

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

                            </div>

                        </div>

                        <div class="box-body usersDiv">
                            <form action="{{url('/madmin/users-search')}}" method="POST" role="search">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <select name="uw" class="form-control" style="width: 100%;">
                                                <option value="" selected>
                                                    Select type
                                                </option>
                                                <option value="1">is Uw</option>
                                                <option value="0">is Hr</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="r" class="form-control select2" style="width: 100%;">
                                                @if(!empty($r))
                                                    <option value="{{$r->id}}" selected>
                                                        {{ $r->title_ru }}
                                                    </option>
                                                @else
                                                    <option value="" selected>
                                                        Select role
                                                    </option>
                                                @endif

                                                @if(!empty($roles))
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->title_ru }}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group has-success">
                                            <input type="text" class="form-control" name="t" value="{{ $t }}"
                                                   placeholder="% LOGIN, FULL_NAME, FILIAL">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <a href="{{route('users.index')}}" class="btn btn-flat border-success">
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
                        </div>

                        <h3 class="box-title usersDiv" id="modelTotal">@lang('blade.overall'): <b>{{ $models->total() }}</b></h3>

                        <div id="loading" class="loading-gif" style="display: none"></div>

                        <div class="box-body usersDiv">

                            <table class="table table-striped table-bordered" id="search_table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('blade.full_name')</th>
                                    <th>@lang('blade.username')</th>
                                    <th>@lang('blade.branch')</th>
                                    <th>Office (BXO)-@lang('blade.position')</th>
                                    <th>Hr/Uw</th>
                                    <th>@lang('blade.role')</th>
                                    <th>@lang('blade.status')</th>
                                    <th><i class="fa fa-pencil-square-o text-blue"></i></th>
                                    <th><i class="fa fa-trash-o text-red"></i></th>
                                    <th>@lang('blade.date')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($models as $key => $model)
                                    <tr>
                                        <td>{{ $models->firstItem()+$key }}</td>
                                        <td>
                                            {{ $model->personal->l_name??'-' }} {{ $model->personal->f_name??'-' }}
                                        </td>
                                        <td>{{ $model->username }}</td>
                                        <td>
                                            {{ $model->currentWork->filial->title??'-' }}
                                        </td>
                                        <td>
                                            {{ $model->currentWork->department->title??'-' }}<br>
                                            <span class="text-sm text-muted">
                                                {{ $model->currentWork->job_title??'-' }}
                                            </span>
                                        </td>
                                        <td>@if($model->isUw == 0)
                                                <span class="label label-danger">Hr</span>
                                            @else
                                                <span class="label label-info">Uw</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($model->currentWork->roleId??'')
                                            @foreach($model->currentWork->roleId as $value)
                                                    <span class="label label-info margin-r-5">{{ $value->getRoleName->title??'-' }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @switch($model->status)
                                                @case(0)
                                                <span class="label label-warning">passive</span>
                                                @break
                                                @case(1)
                                                <span class="label label-success">active</span>
                                                @break
                                                @case(2)
                                                <span class="label label-danger">deleted</span>
                                                @break
                                                @default
                                                <span class="label label-default">unknown</span>
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('users.edit', $model->id) }}" class="btn btn-xs btn-info">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                        <td>
                                            @if($model->status == 2)

                                                <a href="javascript:;" data-toggle="modal"
                                                   data-target="#DeleteModal" class="btn btn-xs btn-danger disabled">
                                                    <span class="glyphicon glyphicon-trash"></span></a>
                                            @else

                                                <a href="javascript:;" data-toggle="modal"
                                                   onclick="deleteUrl({{$model->id}})"
                                                   data-target="#DeleteModal" class="btn btn-xs btn-danger">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            @endif

                                        </td>
                                        <td style="min-width: 110px">
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d.m.Y')}}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                            <span class="paginate">{{ $models->links() }}</span>
                        </div>
                        <!-- /.box-body -->

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

            $('.usersDiv').show();

            $('#oraTableDiv').hide();

            $('#openDiv').click(function () {

                $('#oraTableDiv').toggle();

                $('.usersDiv').toggle();
            });

            $("#loading").hide();

            $("#search").click(function () {

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

            $('#emp_code').keydown(function(event){

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
