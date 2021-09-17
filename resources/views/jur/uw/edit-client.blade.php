@extends('layouts.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Yuridik Mijoz taxrirlash
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">underwriter</a></li>
            <li class="active">underwriter</li>
        </ol>
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

        @if ($errors->any())
            <div class="alert alert-danger">
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
                <div class="box box-success">
                    <form action="{{ url('/jur/uw/edit-client-post') }}" method="POST">
                        @csrf
                        <input name="id" value="{{ $model->id }}" hidden>

                        <div class="box-body">
                            <h3 class="box-title text-green text-bold">
                                <i class="fa fa-pencil"></i> Ma`lumotlarni taxrirlash
                            </h3>
                            <div class="col-md-6">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Inspector <span class="text-red">*</span></label>
                                        <select class="form-control select2" name="work_user_id" required>
                                            @foreach ($inspectors as $key => $value)
                                                @if($value->work_user_id === $model->work_user_id)
                                                    <option value="{{ $value->work_user_id}}" selected>
                                                        {{ $value->full_name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $value->work_user_id}}">
                                                        {{ $value->full_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Holati <span class="text-red">*</span></label>
                                        <select class="form-control select2" name="status" required>
                                            @foreach ($status_names as $key => $value)
                                                @if($value->code === $model->status)
                                                    <option value="{{ $value->code}}" selected>
                                                        {{ $value->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $value->code}}">
                                                        {{ $value->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Kredit turi <span class="text-red">*</span></label>
                                        <select class="form-control select2" name="loan_type_id" required>
                                            @foreach ($loans as $key => $loan)
                                                @if($loan->id === $model->loan_type_id)
                                                    <option value="{{ $loan->id}}" selected>
                                                        {{ $loan->title.' ('.$loan->procent.'%, '.$loan->credit_duration.'oy, imt:'.$loan->credit_exemtion.'oy)' }}
                                                    </option>
                                                @else
                                                    <option value="{{ $loan->id}}">
                                                        {{ $loan->title.' ('.$loan->procent.'%, '.$loan->credit_duration.'oy, imt:'.$loan->credit_exemtion.'oy)' }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Izox <span class="text-red">*</span></label>
                                        <input type="text" name="text" class="form-control" maxlength="100"
                                               required>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-flat btn-success prg">
                                    <i class="fa fa-save"></i> @lang('blade.save')</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>

    </section>
    <!-- /.content -->
    <script type="text/javascript">
        $(function () {
            $("#example1").DataTable();
            //Initialize Select2 Elements
            $(".select2").select2();

        });

    </script>
@endsection
