@extends('layouts.table')

@section('content')

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Xodimlar
                <small>Xodimlar jadvali</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/home"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
                <li><a href="#">Users</a></li>
                <li class="active">Users table</li>
            </ol>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Xatolik!</strong> xatolik bor.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
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

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header">
                            <div class="col-md-1">
                                <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> <b> Yangi xodim qo`shish</b></a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            {{ $users->links() }}
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Login</th>
                                    <th>FIO</th>
                                    <th>Filial</th>
                                    <th>Depart</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Last login</th>
                                    <th><i class="fa fa-trash-o text-red"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                {{$i = 1}}
                                @foreach ($users as $key => $user)
                                    <tr style="font-size: small">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td><a href="{{ route('users.edit', $user->id) }}">{{ $user->full_name }}</a></td>
                                        <td>{{ $user->branch_code.' '.$user->branch }}</td>
                                        <td>{{ $user->depart.' '.$user->job_title }}</td>
                                        <td>{{ $user->roles }}</td>
                                        <td>
                                            @if($user->status == 1)
                                                <i class="fa fa-check-circle text-green"></i>
                                            @elseif($user->status == 2)
                                                <i class="fa fa-trash text-red"></i>
                                            @else
                                                <i class="fa fa-ban text-yellow"></i>
                                            @endif
                                        </td>
                                        <td>{{ $user->last_login }}</td>
                                        <td>
                                            <form action="{{ url('users/'.$user->user_gen) }}" method="POST" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}

                                                <button type="submit" id="delete-group-{{ $user->user_gen }}" title="O`chirish">
                                                    <i class="fa fa-btn fa-trash text-red"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $users->links() }}
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
        <!-- /.content -->
        <!-- jQuery 2.2.3 -->
        <script src="admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="admin-lte/bootstrap/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script src="admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="admin-lte/plugins/datatables/dataTables.bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="admin-lte/dist/js/app.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script>
            $(function () {
                $("#example1").DataTable();
            });
        </script>
@endsection
