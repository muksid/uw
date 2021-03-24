@extends('layouts.table')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Departments Tables
            <small>advanced tables</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Departments</a></li>
            <li class="active">Departments table</li>
        </ol>
        <!-- Message Succes -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
    <!-- Display Validation Errors -->
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

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <div class="col-md-1">
                            <a href="{{ route('departments.create') }}" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> <b> Create Department</b></a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Branch name</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th><i class="fa fa-eye text-green"></i></th>
                                <th><i class="fa fa-pencil-square-o text-blue"></i></th>
                                <th><i class="fa fa-trash-o text-red"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach ($departments as $key => $department)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        @if($department->parent_id == 0)
                                            {{ $department->branch_code. ' ' .$department->title }}
                                        @else
                                            {{ $department->branch_code }}
                                        @endif
                                    </td>
                                    <td>{{ $department->title }}</td>
                                    <td>
                                        @if($department->status == 1)
                                            <i class="fa fa-check-circle text-green"></i>
                                        @elseif($department->status == 2)
                                            <i class="fa fa-trash text-red"></i>
                                        @else
                                            <i class="fa fa-ban text-yellow"></i>
                                        @endif
                                    </td>
                                    <td>{{ $department->created_at }}</td>
                                    <td>
                                        <a href="{{ route('departments.show', $department->id) }}"><i class="fa fa-eye text-green"></i></a>
                                    </td>
                                    <td>
                                        <a href="{{ route('departments.edit', $department->id) }}"><i class="fa fa-pencil"></i></a>
                                    </td>
                                    <td>
                                        <form action="{{ url('departments/'.$department->id) }}" method="POST" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button type="submit" id="delete-group-{{ $department->id }}" title="O`chirish">
                                                <i class="fa fa-btn fa-trash text-red"></i>
                                            </button>
                                        </form>
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
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>

        <script>
            $(function () {
                $("#example1").DataTable();

            });
        </script>

    </section>
    <!-- /.content -->
@endsection
