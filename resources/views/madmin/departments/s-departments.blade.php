@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            SDepartments
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">s-departments</a></li>
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
                        <div class="col-md-2">
                            <a href="{{ url('/madmin/filials') }}" class="btn btn-danger btn-flat">
                                <i class="fa fa-bank"></i> <b> Filiallar</b>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('departments.index') }}" class="btn btn-primary">
                                <i class="fa fa-list"></i> <b> Departments</b>
                            </a>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ url('/madmin/update-s-departments') }}" class="btn btn-success">
                                <i class="fa fa-refresh"></i> <b> @lang('blade.refresh')</b>
                            </a>
                        </div>
                    </div>

                    <form action="{{url('/madmin/s-departments')}}" method="POST" role="search">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-md-3 col-md-offset-3">
                                <div class="form-group has-success">
                                    <input type="text" class="form-control" name="query" value="{{ $query }}"
                                           placeholder="% CODE, NAME">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <a href="{{url('/madmin/filials')}}" class="btn btn-flat border-success">
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
                                <th>Code</th>
                                <th>Name</th>
                                <th>IsActive</th>
                                <th>Edit</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->code }}</td>
                                    <td>{{ $model->getReplace($model->title??'-')??'-' }}</td>
                                    <td class="text-center">
                                        @if($model->isActive == 'A')
                                            <i class="fa fa-check-circle text-green"></i>
                                        @else
                                            <i class="fa fa-ban text-yellow"></i> {{ $model->isActive }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-xs btn-success"
                                           href="{{ url('/madmin/filials/edit', $model->id) }}"><i
                                                    class="fa fa-pencil"></i></a>
                                    </td>
                                    <td>{{ $model->updated_at->format('d.M.Y') }}</td>
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
