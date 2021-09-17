@extends('layouts.dashboard')
<?php
use Illuminate\Support\Carbon
?>
@section('content')

    <section class="content-header">
        <h1 class="text-maroon">Mijoz ma`lumotlari
            <small>batafsil</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li class="active">create</li>
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

    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header text-green">
                    <i class="fa fa-user"></i> {{ $model->last_name.' '.$model->first_name.' '.$model->middle_name }}
                    <small class="pull-right text-sm text-aqua">YARATILDI: {{ Carbon::parse($model->created_at)->format('d F, Y H:i') }}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-3 invoice-col">
                <img src="{{ url('/phy/client/image',$model->id) }}" alt=""
                     class="img-responsive img-rounded img-thumbnail" style="max-width: 260px;">
            </div>
            <div class="col-sm-3 invoice-col">
                <span class="text-green">PASSPORT MA`LUMOTLARI</span><br><br>
                <address>
                    <span class="text-aqua">PASSPORT:</span> {{ $model->pass_data }}<br>
                    <span class="text-aqua">JSHSHIR:</span> {{ $model->pinfl }}<br>
                    <span class="text-aqua">STIR:</span> {{ $model->inn }}<br>
                    <span class="text-aqua">TUG`ILGAN KUN:</span> {{ Carbon::parse($model->birth_date)->format('d.m.Y') }}<br>
                    <span class="text-aqua">KIM TOMONIDAN BERILGAN:</span> {{ $model->issued_by }}<br>
                    <span class="text-aqua">PASSPORT BERILGAN SANA:</span> {{ Carbon::parse($model->issued_date)->format('d.m.Y') }}<br>
                    <span class="text-aqua">JINS:</span> @if($model->gender == 1) ERKAK @else AYOL @endif<br>
                    <span class="text-aqua">MILLATI:</span> {{ $model->nationality }}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-3 invoice-col">
                <span class="text-green">MANZIL RO`YXAT MA`LUMOTLARI</span><br><br>
                <address>
                    <span class="text-aqua">DOIMIY RO`YXATGA OLINGAN:</span> {{ $model->permanent_address }}<br>
                    <span class="text-aqua">VAQTINCGALIK RO`YXATGA OLINGAN:</span> {{ $model->temporary_address }}<br>
                    <span class="text-aqua">DAVLAT:</span> {{ $model->citizenship }}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-3 invoice-col">
                <span class="text-green">BANK MA`LUMOTLARI</span><br><br>
                <address>
                    <span class="text-aqua">FILIAL:</span> {{ $model->branch_code }}<br>
                    <span class="text-aqua">BXO:</span> {{ $model->local_code }}<br>
                    <span class="text-aqua">INSPEKTOR:</span> {{ $model->inspector->personal->l_name??'-' }} {{ $model->inspector->personal->f_name??'-' }}<br>
                    <span class="text-aqua">HOLATI:</span> <SPAN class="badge bg-green-active">@if($model->isActive == 'A') FAOL @else FAOL EMAS @endif</SPAN>
                </address>
            </div>

        </div>
        <div class="box-body">
            <p>
                <input type="button" class="btn bg-olive-active btn-flat margin" id="open-form" value="TAXRIRLASH" />
            </p>
        </div>
        <div class="box box-success" id="edit-form">
            <form action="{{ url('/myid/adm/phy/edit') }}" method="POST">
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
                                                {{ $value->branch_code.' - '.$value->full_name }}
                                            </option>
                                        @else
                                            <option value="{{ $value->work_user_id}}">
                                                {{ $value->branch_code.' - '.$value->full_name }}
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
                                <select class="form-control select2" name="isActive" required>
                                    @if($model->isActive == 'A')
                                        <option value="A" selected>FAOL</option>
                                        <option value="P">FAOL EMAS</option>
                                    @else
                                        <option value="P" selected>FAOL EMAS</option>
                                        <option value="A">FAOL</option>
                                    @endif
                                </select>

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
        <hr>
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <h4 class="text-green"><i class="fa fa-list-ol"></i> MIJOZNING ARIZALARI</h4>
                <a href="{{ url('/phy/client/form', ['id' => $model->id]) }}" class="btn btn-info pull-right btn-flat" style="margin-right: 5px;">
                    <i class="fa fa-camera"></i> ARIZA YARATISH
                </a>
                <table class="table table-striped">
                    <thead>
                    <tr class="text-aqua">
                        <th><i class="fa fa-list-ol"></i></th>
                        <th><i class="fa fa-credit-card-alt"></i> KREDIT TURI</th>
                        <th><i class="fa fa-user"></i> MIJOZ F.I.O.</th>
                        <th><i class="fa fa-credit-card"></i> JSHSHIR</th>
                        <th><i class="fa fa-money"></i> SUMMA</th>
                        <th><i class="fa fa-check-circle-o"></i> HOLATI</th>
                        <th><i class="fa fa-link"></i> HARAKAT</th>
                        <th><i class="fa fa-bank"></i> FILIAL</th>
                        <th><i class="fa fa-bank"></i> BXO</th>
                        <th><i class="fa fa-bank"></i> INSPEKTOR</th>
                        <th><i class="fa fa-calendar"></i> YARATILDI</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($phy_clients->count())
                        @foreach($phy_clients as $key => $value)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td>{!! \Illuminate\Support\Str::words($value->loanType->title??'-', '2') !!}</td>
                                <td>{{ $value->family_name.' '.$value->name.' '.$value->patronymic }}</td>
                                <td>{{ $value->pin }}</td>
                                <td>{{ number_format($value->summa) }}</td>
                                <td>
                                    <span class="badge {{ $value->statusIns->bg_style??'-' }}">
                                                {{ $value->statusIns->name??'-' }}
                                            </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('/phy/uw/view-client',
                                                    ['id' => $value->id, 'claim_id' => $value->claim_id]) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-link"></i> BATAFSIL..
                                    </a>
                                </td>
                                <td><span class="badge bg-light-blue-active">{{ $value->branch_code??'' }}</span>
                                </td>
                                <td>{{ $value->local_code??'' }}
                                    - {!! \Illuminate\Support\Str::words($value->department->title??'Филиал', '3') !!}
                                </td>
                                <td class="text-green">{{ $value->inspector->personal->l_name??'-' }} {{ $value->inspector->personal->f_name??'-' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($value->created_at)->format('d.m.Y H:i')  }}<br>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11" class="text-center text-danger">Mijoz arizasi topilmadi</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>

    <section>

        <script type="text/javascript">
            //$("#edit-form").hide();
            $(function () {
                $("#example1").DataTable();
                //Initialize Select2 Elements
                $(".select2").select2();


                $("#open-form").on('click', function() {
                    $("#edit-form").toggle();
                    this.value = this.value === 'TAXRIRLASH' ? 'BEKOR QILISH' : 'TAXRIRLASH';
                });

            });

        </script>
    </section>

@endsection

