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
                <div class="box box-info">
                    <div class="box-header with-border">
                        <div class="box-header">
                            <div class="col-md-1">
                                <a href="{{ route('users.create') }}" class="btn btn-flat btn-info">
                                    <i class="fa fa-plus-circle"></i> @lang('blade.add')
                                </a>
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
                                    <th>@lang('blade.role')</th>
                                    <th>@lang('blade.status')</th>
                                    <th><i class="fa fa-pencil-square-o text-blue"></i></th>
                                    <th><i class="fa fa-trash-o text-red"></i></th>
                                    <th>@lang('blade.date')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($models as $key => $model)
                                    <tr id="rowId_{{ $model->id }}">
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
                                        <td>
                                            @if($model->currentWork->roleId??'')
                                            @foreach($model->currentWork->roleId as $value)
                                                    <span class="label label-info margin-r-5">{{ $value->getRoleName->title??'-' }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @switch($model->isActive)
                                                @case('P')
                                                <span class="label label-warning">passive</span>
                                                @break
                                                @case('A')
                                                <span class="label label-success">active</span>
                                                @break
                                                @case('H')
                                                <span class="label label-danger">hr</span>
                                                @break
                                                @case('D')
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
                                            <button type="button" class="btn btn-xs btn-danger" id="deleteRole"
                                                    data-id="{{ $model->id }}">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </button>
                                            {{--@if($model->isActive == 'D')

                                                <a href="javascript:;" data-toggle="modal"
                                                   data-target="#DeleteModal" class="btn btn-xs btn-danger disabled">
                                                    <span class="glyphicon glyphicon-trash"></span></a>
                                            @else

                                                <a href="javascript:;" data-toggle="modal"
                                                   onclick="deleteUrl({{$model->id}})"
                                                   data-target="#DeleteModal" class="btn btn-xs btn-danger">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            @endif--}}

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
                                        <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> Xodim
                                            serverdan o`chiriladi!</h4>
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
                                            <i class="fa fa-check-circle"></i> <span id="successHeader"></span>
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        <h4 id="successBody"></h4>
                                        <h5 id="resultData1"></h5>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline"
                                                data-dismiss="modal">@lang('blade.close')
                                        </button>
                                    </div>
                                </div>
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

            $('body').on('click', '#deleteRole', function (e) {

                e.preventDefault();
                var id = $(this).data("id");

                $('#ConfirmModal').data('id', id).modal('show');
            });

            $('#yesDelete').click(function () {

                let user_id = $('#ConfirmModal').data('id');

                $.ajax(
                    {
                        type: 'GET',
                        url: "{{ url('/madmin/users/delete') }}"+'/' + user_id,
                        beforeSend: function(){
                            $("#loading").show();
                        },
                        success: function (data) {
                            console.log(data.data)
                            $('#successModal').modal('show');
                            $('#successHeader').html(data.success);
                            $('#successBody').html(data.success);
                            $('#resultData1').html(data.data.id);
                            if (data.status === 'P'){
                                $("#rowId_" + user_id).remove();
                            }
                        },
                        complete:function(){
                            $("#loading").hide();
                        }
                    });

                $('#ConfirmModal').modal('hide');
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
