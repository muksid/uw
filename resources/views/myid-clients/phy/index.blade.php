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

                        <h4 class="box-title">
                            <a href="{{ url('/phy/client/create/new') }}" class="btn btn-info btn-flat" style="margin-right: 5px;">
                                <i class="fa fa-camera"></i> ARIZA YARATISH
                            </a>
                            FILTER
                            @if($date_s)
                                <span class="text-sm text-green">{{ \Carbon\Carbon::parse($date_s)->format('d.m.Y')  }} dan {{ \Carbon\Carbon::parse($date_e)->format('d.m.Y') }} gacha</span>
                            @endif

                        </h4>

                        <form action="{{url('/myid/adm/phy/index')}}" method="POST" role="search">
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
                                            @if($status == 'A')
                                                <option value="{{ $status }}" selected>FAOL</option>
                                                <option value="P">FAOL EMAS</option>
                                                <option value="">Holati barchasi</option>
                                            @elseif($status == 'P')
                                                <option value="{{ $status }}" selected>FAOL EMAS</option>
                                                <option value="A">FAOL</option>
                                                <option value="">Holati barchasi</option>
                                            @else
                                                <option value="" selected>Holati barchasi</option>
                                                <option value="A">FAOL</option>
                                                <option value="P">FAOL EMAS</option>
                                            @endif

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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{url('/myid/adm/phy/index')}}" class="btn btn-flat border-success">
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

                        <form action="{{ route('phy-export') }}" method="POST" role="search">
                            {{ csrf_field() }}
                            <input name="mfo" value="{{ $mfo }}" hidden>
                            <input name="status" value="{{ $status }}" hidden>
                            <input name="text" value="{{ $text }}" hidden>
                            <input name="date_s" id="s_start" value="{{ $date_s }}" hidden>
                            <input name="date_e" id="s_end" value="{{ $date_e }}" hidden>
                            <button type="submit" class="btn btn-success hide">
                                <i class="fa fa-file-excel-o"></i> Excel Export
                            </button>
                        </form>
                    </div>

                    <div class="box-body">
                        <b>@lang('blade.overall'){{': '. number_format($models->total()) }} @lang('blade.group_edit_count').</b>

                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr class="text-aqua">
                                <th><i class="fa fa-list-ol"></i></th>
                                <th><i class="fa fa-user"></i> MIJOZ F.I.O.</th>
                                <th><i class="fa fa-credit-card"></i> PASSPORT</th>
                                <th><i class="fa fa-map-marker"></i> MANZIL</th>
                                <th><i class="fa fa-check-circle-o"></i> HOLATI</th>
                                <th><i class="fa fa-link"></i> HARAKAT</th>
                                <th><i class="fa fa-bank"></i> FILIAL</th>
                                <th><i class="fa fa-calendar"></i> YARATILDI</th>
                            </tr>
                            </thead>
                            <tbody id="roleTable">
                            <?php $i = 1; ?>
                            @if($models->count())
                                @foreach ($models as $key => $model)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $model->full_name}}</td>
                                        <td>{{ $model->pass_data }}</td>
                                        <td>{{ $model->permanent_address }}</td>
                                        <td>
                                            @if($model->isActive === 'A')
                                            <span class="badge bg-aqua-active">
                                                FAOL
                                            </span>
                                            @else
                                                <span class="badge bg-danger">
                                                FAOL EMAS
                                            </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('/myid/adm/phy/view',
                                                    ['id' => $model->id,
                                                    'claim_id' => $model->pinfl]) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-link"></i> BATAFSIL..
                                            </a>
                                        </td>
                                        <td><span class="badge bg-light-blue-active">{{ $model->branch_code??'' }}</span>
                                            - {!! \Illuminate\Support\Str::words($model->department->title??'Филиал', '3') !!}
                                        </td>
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

                        $('#daterange-btn span').html(start.format('MM.DD.YYYY') + ' - ' + end.format('MM.DD.YYYY'));
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
