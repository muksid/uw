@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Departamentlar
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">departments</a></li>
            <li class="active">index</li>
        </ol>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Xatolik!</strong> Ma`lumotlarni qaytadan tekshiring.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">

                    <div class="box-header">
                        <div class="col-md-1">
                            <a href="{{ route('departments.create') }}" class="btn btn-success btn-flat">
                                <i class="fa fa-plus-circle"></i> <b> @lang('blade.add')</b>
                            </a>
                        </div>
                    </div>

                    <form action="{{url('/madmin/departments')}}" method="POST" role="search">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-md-3 col-md-offset-3">
                                <div class="form-group has-success">
                                    <input type="text" class="form-control" name="query" value="{{ $query }}"
                                           placeholder="% FILIAL, LOCAL CODE, NAME">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <a href="{{route('departments.index')}}" class="btn btn-flat border-success">
                                        <i class="fa fa-refresh"></i>
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

                    <div class="box-body">
                        <h5 class="box-title">@lang('blade.overall'): <b>{{ $models->total() }}</b></h5>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>BranchCode</th>
                                <th>Filial</th>
                                <th>BranchName</th>
                                <th>LocalCode</th>
                                <th>Status</th>
                                <th>View</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->branch_code }}</td>
                                    <td class="text-green text-sm">{{ $model->filial->title??'-' }}</td>
                                    <td>{{ $model->title }}</td>
                                    <td class="text-center">
                                        @if($model->branch_code == $model->local_code)
                                            <span class="badge bg-secondary">{{ $model->local_code }}</span>
                                        @else
                                            {{ $model->local_code }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($model->status == 1)
                                            <i class="fa fa-check-circle text-green"></i>
                                        @elseif($model->status == 2)
                                            <i class="fa fa-trash text-red"></i>
                                        @else
                                            <i class="fa fa-ban text-yellow"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-xs btn-primary" href="{{ route('departments.show', $model->id) }}"><i
                                                    class="fa fa-search-plus"></i></a>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-xs btn-success"
                                           href="{{ route('departments.edit', $model->id) }}"><i
                                                    class="fa fa-pencil"></i></a>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('departments.destroy', $model->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $model->created_at->format('d.M.Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <span class="paginate">{{ $models->links() }}</span>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

    </section>

@endsection
