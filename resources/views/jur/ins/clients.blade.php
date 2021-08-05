@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Yuridik mijozlar
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">juridical</a></li>
            <li class="active">index</li>
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

    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div>
                    <a href="{{ url('jur/client/create') }}" class="btn bg-olive-active btn-flat margin">
                        <i class="fa fa-plus-circle"></i> @lang('blade.add')
                    </a>
                    <a href="{{ url('jur/clients/1') }}" class="btn bg-maroon btn-flat margin">
                        <i class="fa fa-undo"></i> Yangi arizalar
                    </a>
                    <a href="{{ url('jur/clients/2') }}" class="btn bg-purple btn-flat margin">
                        <i class="fa fa-send-o"></i> Yuborilgan
                    </a>
                    <a href="{{ url('jur/clients/3') }}" class="btn bg-navy btn-flat margin">
                        <i class="fa fa-check-circle"></i> Tasdiqlangan
                    </a>
                    <a href="{{ url('jur/clients/0') }}" class="btn bg-orange btn-flat margin">
                        <i class="fa fa-pencil"></i> Taxrirlashda
                    </a>
                </div>

                <div class="box box-primary">

                    <div class="box-body">

                        <form action="{{url('/jur/clients/'.$status.'')}}" method="POST" role="search">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="u" class="form-control select2" style="width: 100%;">
                                            @if(!empty($searchUser))
                                                <option value="{{$searchUser->id}}" selected>
                                                    {{$searchUser->branch_code??''}} - {{ $searchUser->personal->l_name??'' }} {{$searchUser->personal->f_name??'-'}}
                                                </option>
                                            @else
                                                <option value="" selected>
                                                    @lang('blade.select_employee')
                                                </option>
                                            @endif

                                            @if(!empty($users))
                                                @foreach($users as $key => $value)
                                                    <option value="{{$value->currentWork->id}}">
                                                        {{$value->currentWork->branch_code??''}} - {{$value->personal->l_name??''}} {{$value->personal->f_name??''}}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="t" value="{{ $t }}"
                                               placeholder="% IABS, ARIZA, STIR, SUMMA, FILIAL">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <div class="input-group input-daterange">
                                                <input type="text" name="d" id="out_date" value="{{ $d }}"
                                                       class="form-control" placeholder="@lang('blade.date')"
                                                       readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{url('/jur/clients/'.$status.'')}}" class="btn btn-flat border-success">
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
                    <div class="box-body">
                        <b>@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center"><i class="fa fa-bank"></i></th>
                                <th>IABS</th>
                                <th>Ariza</th>
                                <th>Mijoz nomi</th>
                                <th>Summa</th>
                                <th class="text-center">@lang('blade.status')</th>
                                <th class="text-center">@lang('blade.update')</th>
                                <th>Filial (BXO)</th>
                                <th class="text-center">Inspektor</th>
                                <th>Sana</th>
                            </tr>
                            </thead>
                            <tbody id="roleTable">
                            <?php $i = 1 ?>
                            @if($models->count())
                                @foreach ($models as $key => $model)
                                    <tr id="rowId_{{ $model->id }}">
                                        <td>{{ $i++ }}</td>
                                        <td><span class="badge bg-light-blue">{{ $model->department->branch_code??'-' }}</span></td>
                                        <td>{{ $model->client_code }}</td>
                                        <td>{{ $model->claim_id }}</td>
                                        <td class="text-uppercase">
                                            <a href="{{ route('client.show', ['id' => $model->id]) }}">
                                                {{ $model->jur_name}}
                                            </a>
                                        </td>
                                        <td><b>{{ number_format($model->summa) }}</b></td>

                                        <td>
                                            @if($model->status == 0)
                                                <span class="badge bg-red-active">Taxrirlashda</span>
                                            @elseif($model->status == 1)
                                                <span class="badge bg-yellow-active">Yangi</span>
                                            @elseif($model->status == 2)
                                                <span class="badge bg-aqua-active">Yuborilgan</span>
                                            @elseif($model->status == 3)
                                                <span class="badge bg-aqua-active">Tasdiqlangan</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('client.edit', ['id' => $model->id]) }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                        <td class="text-sm">{{ $model->department->title_ru??'' }}</td>
                                        <td class="text-sm text-center text-bold text-blue">
                                            {{ $model->user->personal->l_name??'-' }}
                                            {{ mb_substr($model->user->personal->f_name??'-', 0, 1) }}.</td>
                                        <td class="text-sm" style="min-width: 50px">
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d.m.Y H:i')  }}<br>
                                            <span class="text-maroon text-sm"> ({{$model->created_at->diffForHumans()}})</span>
                                        </td>
                                    </tr>
                                @endforeach @else
                                <td class="text-red text-center" colspan="12"><i class="fa fa-search"></i>
                                    <b>@lang('blade.not_found')</b></td>
                            @endif
                            </tbody>
                        </table>
                        <span class="paginate">{{ $models->links() }}</span>
                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

        <script>

            $(function () {
                $("#example1").DataTable();
                //Initialize Select2 Elements
                $(".select2").select2();

                //Date picker
                $('#datepicker').datepicker({
                    autoclose: true
                });
                $('.input-datepicker').datepicker({
                    todayBtn: 'linked',
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    forceParse: false,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            });

        </script>
    </section>
    <!-- /.content -->
@endsection
