@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Ta`minot turlari
            <small>@lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">Ta`minot turlari</a></li>
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
            <div class="alert alert-success">
                {{ $message }}
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
                                <a href="{{ route('guar-type.create') }}" class="btn btn-flat btn-info">
                                    <i class="fa fa-plus-circle"></i> @lang('blade.add')</a>
                            </div>
                        </div>

                        <div class="box-body">

                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('blade.title_uz')</th>
                                    <th>@lang('blade.title_ru')</th>
                                    <th>Code</th>
                                    <th>@lang('blade.status')</th>
                                    <th><i class="fa fa-pencil-square-o text-blue"></i></th>
                                    <th><i class="fa fa-trash-o text-red"></i></th>
                                    <th>@lang('blade.date')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($models as $key => $model)
                                    <tr>
                                        <td>{{ $key++ }}</td>
                                        <td>{{ $model->title }}</td>
                                        <td>{{ $model->title_ru }}</td>
                                        <td>{{ $model->code }}</td>
                                        <td>
                                            @if($model->isActive == 0)
                                                <span class="label label-danger">Passive</span>
                                            @else
                                                <span class="label label-info">Active</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('guar-type.edit', $model->id) }}" class="btn btn-xs btn-info">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('guar-type.destroy', $model->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-danger">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td style="min-width: 110px">
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d.m.Y')}}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->

                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
@endsection
