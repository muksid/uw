@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Barcha Yuridik mijozlar
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

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">

                    <div class="box-body">
                        <h4 class="box-title">FILTER
                            @if($date_s)
                                <span class="text-sm text-green">{{ \Carbon\Carbon::parse($date_s)->format('d.m.Y')  }} dan {{ \Carbon\Carbon::parse($date_e)->format('d.m.Y') }} gacha</span>
                            @endif
                        </h4>

                        <form action="{{url('/jur/uw/all-clients/')}}" method="POST" role="search">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="mfo" value="{{ $mfo }}"
                                               placeholder="MFO">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <select name="status" class="form-control select2" style="width: 100%;">
                                            @if($status_name)
                                                <option value="{{ $status_name->status_code }}" selected>{{ $status_name->name }}</option>
                                            @else
                                                <option value="" selected>Holati barchasi</option>
                                            @endif

                                            @foreach($status_names as $key => $value)

                                                <option value="{{$value->status_code}}">
                                                    {{ $value->name }}
                                                </option>

                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-default" id="daterange-btn">
                                                <span>
                                                  <i class="fa fa-calendar"></i> Davr oraliq
                                                </span>
                                                <i class="fa fa-caret-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input name="date_s" id="s_start" value="" hidden>
                                <input name="date_e" id="s_end" value="" hidden>

                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="text" value="{{ $text }}"
                                               placeholder="SEARCH %">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="user" class="form-control select2" style="width: 100%;">
                                            @if(!empty($user))
                                                <option value="{{$user->id}}" selected>
                                                    {{$user->branch_code??''}} - {{ $user->personal->l_name??'' }} {{$user->personal->f_name??'-'}}
                                                </option>
                                            @else
                                                <option value="" selected>
                                                    Inspektor Barchasi
                                                </option>
                                            @endif

                                            @if(!empty($users))
                                                @foreach($users as $key => $value)
                                                    <option value="{{$value->currentWork->id??0}}">
                                                        {{$value->currentWork->branch_code??''}} - {{$value->personal->l_name??''}} {{$value->personal->f_name??''}}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{url('/jur/uw/all-clients')}}" class="btn btn-flat border-success">
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
                        <form action="{{ route('export') }}" method="POST" role="search">
                        {{ csrf_field() }}
                            <input name="mfo" value="{{ $mfo }}" hidden>
                            <input name="status" value="{{ $status_name->status_code??null }}" hidden>
                            <input name="text" value="{{ $text }}" hidden>
                            <input name="user" value="{{ $user->id??'' }}" hidden>
                            <input name="date_s" id="s_start" value="{{ $date_s }}" hidden>
                            <input name="date_e" id="s_end" value="{{ $date_e }}" hidden>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i> Excel Export
                            </button>
                        </form>
                    </div>

                    <div class="box-body">
                        <b>@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kredit Turi</th>
                                <th>IABS #</th>
                                <th>Ariza #</th>
                                <th>Mijoz nomi</th>
                                <th>Summa</th>
                                <th class="text-center">@lang('blade.status')</th>
                                <th class="text-center"><i class="fa fa-pencil"></i></th>
                                <th class="text-center">MFO</th>
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
                                        <td class="text-sm">{{ $model->loanType->title??'' }}</td>
                                        <td>{{ $model->client_code }}</td>
                                        <td>{{ $model->claim_id }}</td>
                                        <td class="text-uppercase">
                                            <a href="{{ route('client.show', ['id' => $model->id]) }}">
                                                {{ $model->jur_name}}
                                            </a>
                                        </td>
                                        <td><b>{{ number_format($model->summa, 2) }}</b></td>

                                        <td>
                                            <span class="badge {{ $model->uwStatus->bg_style??'-' }}">{{ $model->uwStatus->name??'-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('/jur/uw/edit-client', $model->id) }}" class="btn btn-xs btn-info">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $model->branch_code??'-' }}</span></td>
                                        <td class="text-sm">{{ $model->filial->title??'' }}</td>
                                        <td class="text-sm text-center text-bold text-blue">
                                            {{ $model->inspector->personal->l_name??'-' }}
                                            {{ mb_substr($model->inspector->personal->f_name??'-', 0, 1) }}.</td>
                                        <td class="text-sm">
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d.m.y H:i')  }}
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

                //Date range as a button
                $('#daterange-btn').daterangepicker(
                    {
                        ranges: {
                            'Bugun': [moment(), moment()],
                            'Kecha': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Ohirgi 7 kun': [moment().subtract(6, 'days'), moment()],
                            'Ohirgi 30 kun': [moment().subtract(29, 'days'), moment()],
                            'Bu oyda': [moment().startOf('month'), moment().endOf('month')],
                            'O`tgan oyda': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment().subtract(29, 'days'),
                        endDate: moment()
                    },
                    function (start, end) {
                        var s_start = start.format('YYYY-MM-DD');

                        var s_end = end.format('YYYY-MM-DD');

                        $('#s_start').val(s_start);
                        $('#s_end').val(s_end);

                        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    }
                );

                //Date picker
                $('#datepicker').datepicker({
                    autoclose: true
                });

            });
        </script>
    </section>
    <!-- /.content -->
@endsection
