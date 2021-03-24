@extends('layouts.table')

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
        @elseif($message = Session::get('deleted'))

            <div class="modal fade in text-danger" id="deletedModal" role="dialog" style="display: block">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h4 class="modal-title">
                                @lang('blade.users') <i class="fa fa-trash"></i>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <h5>{{ $message }}</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger closeModal" data-dismiss="modal"><i
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-flat"><i
                                    class="fa fa-plus"></i> @lang('blade.create_user')</a>

                        <h3 class="box-title">@lang('blade.overall'): <b>{{ $models->total() }}</b></h3>

                        <div class="box-body">
                            <form action="{{route('users/search')}}" method="POST" role="search">
                                {{ csrf_field() }}
                                <div class="row">
                                    @if(preg_replace('/[^A-Za-z0-9. -]/', '', Auth::user()->roles) == 'admin')
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select name="f" class="form-control select2" style="width: 100%;">
                                                    @if($f_title == '')
                                                        <option selected="selected" value="{{$f}}">{{$f??'Select branch'}}</option>
                                                    @else
                                                        <option selected="selected"
                                                                value="{{$f}}">{{$f.' - '.$f_title}}</option>
                                                    @endif
                                                    @if(!empty($filial))
                                                        @foreach($filial as $key => $value)
                                                            <option value="{{$value->branch_code}}">{{$value->branch_code.' - '.$value->title}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="q" value="{{$q}}"
                                                   placeholder="Users fio">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <select name="s" class="form-control" style="width: 100%;">
                                                @if($s == '')
                                                    <option selected="selected" value="">All</option>
                                                @elseif($s == 1)
                                                    <option selected="selected" value="1">Active</option>
                                                @elseif($s == 0)
                                                    <option selected="selected" value="0">Passive</option>
                                                @elseif($s == 2)
                                                    <option selected="selected" value="2">Deleted</option>
                                                @endif
                                                <option value="">All</option>
                                                <option value="1">Active</option>
                                                <option value="0">Passive</option>
                                                <option value="2">Deleted</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-default btn-flat"
                                                    onclick="location.href='/admin/users';"><i
                                                        class="fa fa-refresh"></i> @lang('blade.reset')</button>
                                            <button type="submit" class="btn btn-primary btn-flat"><i
                                                        class="fa fa-search"></i> @lang('blade.search')</button>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </form>
                        </div>

                        <div class="box-body table-responsive no-padding">

                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('blade.username')</th>
                                    <th>@lang('blade.full_name')</th>
                                    <th>@lang('blade.branch')</th>
                                    <th>@lang('blade.dep')-@lang('blade.position')</th>
                                    <th>@lang('blade.position_date')</th>
                                    <th>@lang('blade.groups_table') #</th>
                                    <th>@lang('blade.role')</th>
                                    <th><i class="fa fa-sort-numeric-asc"></i></th>
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
                                        <td>{{ $model->username }}</td>
                                        <td style="min-width: 170px">
                                            {{ $model->lname.' '.$model->fname }}<br>
                                            <span class="text-sm text-muted">{{ $model->sname }}</span>
                                        </td>
                                        <td>
                                            <span class="text-sm text-muted">{{ $model->filial->title??'' }}</span><br>
                                            {{ $model->filial->branch_code??'' }}
                                        </td>
                                        <td>
                                            <span class="text-sm text-muted">{{ $model->department->title??'' }}</span>
                                            <br>
                                            {{ $model->job_title }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($model->job_date)->format('d.m.Y')}}
                                        </td>
                                        <td>{{ $model->card_num }}</td>
                                        <td>
                                            <span class="text-sm text-muted">
                                                <?php
                                                    $string = $model->roles??'';
                                                    $pattern = '/"/';
                                                    $replacement = "";
                                                    echo preg_replace($pattern, $replacement, $string);
                                                ?>
                                            </span>
                                        </td>
                                        <td style="width: 50px; text-align: center; font-weight: bold">{{ $model->user_sort }}</td>
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
                                        <td>
                                            <a href="{{ route('users.edit', $model->id) }}"><i class="fa fa-pencil"></i></a>
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
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d.M.Y')}}
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
