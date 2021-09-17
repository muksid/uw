@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Jismoniy shaxslar
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">barcha arizalar</a></li>
            <li class="active">clients</li>
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

                        <form action="{{url('/phy/krd/index')}}" method="POST" role="search">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <select name="status" class="form-control select2" style="width: 100%;">
                                            @if($status_name)
                                                <option value="{{ $status_name->status_code }}"
                                                        selected>{{ $status_name->name }}</option>
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
                                <input name="date_s" id="s_start" value="{{ $date_s }}" hidden>
                                <input name="date_e" id="s_end" value="{{ $date_e }}" hidden>

                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="text" value="{{ $text }}"
                                               placeholder="SEARCH %">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="user" class="form-control select2" style="width: 100%;">

                                            <option value="">
                                                Inspektor Barchasi
                                            </option>

                                            @if(!empty($inspectors))
                                                @foreach($inspectors as $key => $value)
                                                    @if($value->user_id == $user)
                                                        <option value="{{$value->user_id }}" selected>
                                                            {{ $value->full_name }}
                                                        </option>
                                                    @endif
                                                    <option value="{{$value->user_id }}">
                                                        {{ $value->full_name }}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{url('/phy/krd/index')}}" class="btn btn-flat border-success">
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
                            <input name="mfo" value="{{ $mfo??'' }}" hidden>
                            <input name="status" value="{{ $status_name->status_code??null }}" hidden>
                            <input name="text" value="{{ $text }}" hidden>
                            <input name="user" value="{{ $user }}" hidden>
                            <input name="date_s" id="s_start" value="{{ $date_s }}" hidden>
                            <input name="date_e" id="s_end" value="{{ $date_e }}" hidden>
                            <button type="submit" class="btn btn-success hidden">
                                <i class="fa fa-file-excel-o"></i> Excel Export
                            </button>
                        </form>
                    </div>

                    <div class="box-body">
                        <b>@lang('blade.overall'){{': '. number_format($models->total()) }} @lang('blade.group_edit_count').</b>

                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kredit Turi</th>
                                <th>IABS</th>
                                <th>Ariza #</th>
                                <th>Mijoz FIO</th>
                                <th>Summa</th>
                                <th>Status</th>
                                <th>Filial</th>
                                <th>Inspektor</th>
                                <th>Sana</th>
                            </tr>
                            </thead>
                            <tbody id="roleTable">
                            <?php $i = 1; $price = 0; $branch = '09011'; ?>
                            @if($models->count())
                                @foreach ($models as $key => $model)
                                    <tr id="rowId_{{ $model->id }}" class="text-sm">
                                        <td>{{ $i++ }}</td>
                                        <td class="text-maroon">
                                            {!! \Illuminate\Support\Str::words($model->loanType->title??'', '2') !!}
                                        </td>
                                        <td>{{ $model->iabs_num??'-' }}</td>
                                        <td>{{ $model->claim_id }}</td>
                                        <td>
                                            <a href="{{ url('/phy/krd/view',
                                                    ['id' => $model->id,
                                                    'claim_id' => $model->claim_id]) }}">
                                               {{ $model->family_name. ' '.$model->name. ' '.$model->patronymic}}
                                            </a>
                                        </td>
                                        <td>{{ number_format($model->summa) }}</td>
                                        <td>
                                            <span class="badge {{ $model->statusIns->bg_style??'-' }}">
                                                {{ $model->statusIns->name??'-' }}
                                            </span>
                                        </td>
                                        <td><span class="badge bg-light-blue-active">{{ $model->branch_code??'' }}</span>
                                            - {!! \Illuminate\Support\Str::words($model->department->title??'Филиал', '3') !!}
                                        </td>
                                        <td class="text-green">{{ $model->inspector->personal->l_name??'-' }} {{ $model->inspector->personal->f_name??'-' }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d.m.Y H:i')  }}<br>
                                        </td>
                                    </tr>
                                @endforeach

                            @else
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
