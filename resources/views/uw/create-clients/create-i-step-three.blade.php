@extends('layouts.uw.dashboard')

@section('content')
    <section class="content-header">
        <h1 class="text-maroon"><i class="fa fa-desktop"></i> {{ $loan->title }}
            <small>{{ $loan->procent }}%</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li class="active">Mijoz kiritish</li>
        </ol>

        @if (Session::has('message'))
            <div class="alert alert-{{ Session::get('status') }} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h3><i class="icon fa fa-{{ Session::get('status') }}"></i> Message!</h3>
                <h3 class="text-maroon">{{ Session::get('message') }}</h3>
                <h3>
                    @foreach(Session::get('data') as $key => $val)
                        {{ $key+=1 }}. <b>Inspektor:</b> {{ $val->currentWork->personal->l_name??'-' }} {{ $val->currentWork->personal->f_name??'-' }} (MFO: {{ $val->branch_code }})
                        <b>Ariza raqami:</b> {{ $val->claim_id }}<br>
                    @endforeach
                </h3>
            </div>
        @endif

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary" style="clear: both;">

                    <div class="box-header with-border">
                        <h2>Step 3 <span class="badge bg-green-active">90%</span></h2>
                        <div class="progress progress-xs progress-striped active" style="height: 20px">
                            <div class="progress-bar progress-bar-success" style="width: 90%;"></div>
                        </div>
                    </div>

                    <form action="{{ route('uw.create.step.three.post') }}" method="POST">
                        {{ csrf_field() }}
                        <input name="loan_type" value="{{ $loan->id }}" hidden />

                        <div class="box-body">
                            <div class="col-md-6 bg-aqua-active">
                                <div class="box-body">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th colspan="3"><i class="fa fa-user"></i> Mijoz pasport ma`lumotlari</th>
                                        </tr>
                                        <tr>
                                            <td>1.</td>
                                            <td>FIO</td>
                                            <td>{{ $model->family_name ?? ''}} {{ $model->name ?? ''}} {{ $model->patronymic??'' }}</td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>Tug`ilgan yili</td>
                                            <td><i class="fa fa-calendar"></i>
                                                {{ \Carbon\Carbon::parse($model->birth_date)->format('d.m.Y') }} yil.
                                            </td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>Jinsi</td>
                                            <td><i class="fa fa-child"></i>
                                                {{ $model->gender == 1 ? 'Erkak' : 'Ayol' }}
                                            </td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>Pasport ma`lumotlari</td>
                                            <td><i class="fa fa-user"></i>
                                                {{ $model->document_serial ?? ''}} {{ $model->document_number ?? ''}}
                                                {{ \Carbon\Carbon::parse($model->document_date)->format('d.m.Y') }} yil.
                                            </td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5.</td>
                                            <td>Pasport berilgan joyi</td>
                                            <td><i class="fa fa-globe"></i>
                                                {{ \Carbon\Carbon::parse($model->document_date)->format('d.m.Y') }} yilda
                                                {{ $model->region1->name?? '' }} {{ $model->docDistrict->name?? '1'}}  <br>
                                                IIB tomonidan berilgan
                                            </td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6.</td>
                                            <td>Yashash joy ma`lumotlari</td>
                                            <td><i class="fa fa-globe"></i>
                                                {{ $model->region->name?? ''}}, {{ $model->district->name?? ''}}
                                                {{ $model->registration_address ?? ''}}
                                            </td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>7.</td>
                                            <td>Telefon raqam</td>
                                            <td><i class="fa fa-phone"></i>
                                                {{ $model->phone }}
                                            </td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>8.</td>
                                            <td>Mijoz INPS</td>
                                            <td><i class="fa fa-user"></i>
                                                {{ $model->pin }}
                                            </td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                        </tbody></table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="box-body bg-danger">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th colspan="3"><i class="fa fa-credit-card"></i> Kredit ma`lumotlari</th>
                                        </tr>
                                        <tr>
                                            <td>1.</td>
                                            <td>Kredit turi</td>
                                            <td><i class="fa fa-desktop"></i> {{ $loan->title }}</td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>Kredit davri</td>
                                            <td><i class="fa fa-hourglass-1"></i> {{ $loan->credit_duration }} oy.</td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>Kredit imtiyozli davr</td>
                                            <td><i class="fa fa-hourglass-2"></i> {{ $loan->credit_exemtion?? '' }} oy.</td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>Kredit %</td>
                                            <td><i class="fa fa-balance-scale"></i> {{ $loan->procent?? '' }} %</td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>Kredit summasi</td>
                                            <td><i class="fa fa-cc"></i>
                                                {{ $model->summa ?? ''}} so`m.
                                            </td>
                                            <td>
                                                <i class="fa fa-check-circle text-info"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                        </tbody></table>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="pull-right">
                                <div class="col-md-6 text-left">
                                    <a href="{{ route('uw.create.step.two', ['id' => $id]) }}" class="btn btn-flat btn-warning pull-right">
                                        <i class="fa fa-fast-backward"></i> @lang('blade.previous')
                                    </a>
                                </div>

                                <button type="submit" class="btn btn-flat btn-primary">
                                    <i class="fa fa-save"></i> @lang('blade.save')
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
    <!-- /.content -->

    <section>
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet" />
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $(function () {
                    $("#example1").DataTable();
                });

            });

        </script>
    </section>
    <!-- /.content -->


@endsection

