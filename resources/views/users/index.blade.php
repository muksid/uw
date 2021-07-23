@extends('layouts.uw.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.users')
            <small>@lang('blade.users') @lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.users')</a></li>
            <li class="active">@lang('blade.users') @lang('blade.groups_table')</li>
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

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <div class="box-header">
                            <div class="col-md-1">
                                <a href="{{ route('users.create') }}" class="btn btn-flat btn-info">
                                    <i class="fa fa-plus-circle"></i> @lang('blade.create_user')</a>
                            </div>
                        </div>

                        <div class="box-body">

                            <form action="{{url('/admin/users-search')}}" method="POST" role="search">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-md-2">
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

                                    <div class="col-md-4">
                                        <div class="form-group has-success">
                                            <input type="text" class="form-control" name="t" value="{{ $t }}"
                                                   placeholder="% username, card, full_name, branch_code, local_code">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <a href="{{url('/admin/users/')}}" class="btn btn-flat border-success">
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

                        <h3 class="box-title" id="modelTotal">@lang('blade.overall'): <b>{{ $models->total() }}</b></h3>

                        <div id="loading" class="loading-gif" style="display: none"></div>

                        <div class="box-body">

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
        <!-- /.row -->

        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <script>

            $(".select2").select2();

            $('#search_refresh').click(function(){

                $('#search_f').val('');

                $('#search_t').val('');

                let filial = $('#search_f').val();

                let text = $('#search_t').val();

                $.ajax({
                    type : 'get',
                    url : '/admin/users-search',
                    data:{
                        'filial':filial,
                        'text'  :text
                    },
                    beforeSend: function(){
                        $("#loading").show();
                    },
                    success: function(res){
                        //console.log(res);
                        $('#search_table').html(res);

                    },
                    complete:function(res){
                        $("#loading").hide();
                    }
                });

            });

            $('#search_t').keydown(function(event){

                var keyCode = (event.keyCode ? event.keyCode : event.which);
                if (keyCode === 13) {

                    $('#search').trigger('click');

                }
            });

            $('#search').click(function(){

                let filial = $('#search_f').val();
                let text = $('#search_t').val();

                $.ajax({
                    type : 'get',
                    url : '/admin/users-search',
                    data:{
                        'filial':filial,
                        'text'  :text
                    },
                    beforeSend: function(){
                        $("#loading").show();
                    },
                    success: function(res){
                        console.log(res)
                        $('#search_f').hide();
                        $('#modelTotal').hide();
                        $('.paginate').hide();
                        $('#search_table').html(res);

                    },
                    complete:function(res){
                        $("#loading").hide();
                    }
                });
            });

            // delete model
            function deleteUrl(id) {
                var id = id;
                var url = '{{ url("admin/users") }}/' + id;
                url = url.replace(':id', id);
                $("#deleteForm").attr('action', url);
            }

            function formSubmit() {
                $("#deleteForm").submit();
            }

            // close deleted Modal
            $('.closeModal').click(function () {

                $('#delModal').hide();

            });

            // close deleted Modal
            $('.closeModal').click(function () {

                $('#deletedModal').hide();

            });

            // close my Modal
            $('.closeModal').click(function () {

                $('#myModal').hide();

            });

        </script>

    </section>
    <!-- /.content -->
@endsection
