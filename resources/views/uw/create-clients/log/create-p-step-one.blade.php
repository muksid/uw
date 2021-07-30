@extends('layouts.uw.dashboard')

@section('content')
    <section class="content-header">
        <h1 class="text-maroon"><i class="fa fa-home"></i> {{ $loan->title }}
            <small>{{ $loan->procent }}%</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li class="active">Mijoz kiritish</li>
        </ol>

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary" style="clear: both;">

                    <div class="box-header with-border">
                        <h2>Step 1 <span class="badge bg-danger">30%</span></h2>
                        <div class="progress progress-xs progress-striped active" style="height: 20px">
                            <div class="progress-bar progress-bar-danger" style="width: 30%;"></div>
                        </div>
                    </div>

                    <form action="{{ route('uw.create.step.one.post') }}" method="POST">
                        @csrf
                        <input name="loan_type" value="{{ $loan->id }}" hidden />
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="box-header with-border">
                            <h3 class="box-title text-aqua text-bold"><i class="fa fa-user"></i> Passport ma`lumotlari
                            </h3>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Familya <span class="text-red">*</span></label>
                                            <input type="text" name="family_name"
                                                   class="form-control latin-only" value="{{ $model->family_name ?? '' }}"
                                                   placeholder="Familiya" required>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Ismi <span class="text-red">*</span></label>
                                            <input type="text" name="name"
                                                   class="form-control latin-only" value="{{ $model->name ?? '' }}"
                                                   placeholder="Ismi" required>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Otasining ismi <span class="*"></span></label>
                                            <input type="text" name="patronymic"
                                                   class="form-control latin-only" value="{{ $model->patronymic ?? '' }}"
                                                   placeholder="Otasining ismi" required>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Tug`ilgan yili </label><sup class="text-red">*</sup>
                                            <div class="input-group date">
                                                <div class="input-group">
                                                    <input type="date" name="birth_date"
                                                           value="{{ $model->birth_date??'', date('d.m.Y') }}"
                                                           class="form-control" placeholder="19.01.1991" required/>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Jinsi <span class="text-red">*</span></label>
                                            <select name="gender" class="form-control" required>
                                                <option value="" selected disabled>Tanlang</option>
                                                <option value="1" @if(1 == ($model->gender ?? '')) selected @endif>Erkak</option>
                                                <option value="2" @if(2 == ($model->gender ?? '')) selected @endif>Ayol</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Pasport turi <span class="text-red">*</span></label>
                                            <select name="document_type" class="form-control" required>
                                                <option value="" selected disabled>Tanlang</option>
                                                <option value="6" @if(6 == ($model->document_type ?? '')) selected @endif>Pasport</option>
                                                <option value="0" @if(7 == ($model->document_type ?? '')) selected @endif>ID karta</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>AB <span class="text-red">*</span></label>
                                            <input type="text" name="document_serial"
                                                   data-inputmask='"mask": "AA"' data-mask
                                                   class="form-control" value="{{ $model->document_serial ?? '' }}"
                                                   placeholder="AB" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Raqami <span class="text-red">*</span></label>
                                            <input type="text" name="document_number"
                                                   data-inputmask='"mask": "9999999"' data-mask
                                                   class="form-control" value="{{ $model->document_number ?? '' }}"
                                                   placeholder="1234567" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Viloyat<span class="text-red">*</span></label>
                                            <select class="form-control select2" name="document_region" id="document_region" required>
                                                <option disabled selected value>@lang('blade.select')</option>
                                                @foreach ($regions as $key => $value)
                                                    <option value="{{ $value->code }}" @if($value->code == ($model->document_region ?? '')) selected @endif>{{ $value->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Tuman <span class="text-red">*</span></label>
                                            <select id="district" class="form-control select2"
                                                    name="document_district" required>
                                                <option value="{{ $model->document_district ?? '' }}">{{ $model->docDistrict->name ?? '' }}</option>
                                            </select>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Berilgan sana </label><sup class="text-red">*</sup>
                                            <div class="input-group date">
                                                <div class="input-group">
                                                    <input type="date" name="document_date"
                                                           value="{{ $model->document_date?? '', date('d.m.Y') }}"
                                                           class="form-control" placeholder="26.06.2013" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Passport PINFL <span class="*"></span></label>
                                            <input type="text" name="pin"
                                                   data-inputmask='"mask": "99999999999999"' data-mask
                                                   class="form-control" value="{{ $model->pin ?? '' }}"
                                                   placeholder="30106911571417" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('inn') ? 'has-error' : '' }}">
                                            <label>INN <span class="text-red">*</span></label>
                                            <input type="text" name="inn" value="{{ $model->inn ?? '' }}"
                                                   data-inputmask='"mask": "999 999 999"' data-mask
                                                   class="form-control" placeholder="123 456 890">

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>INPSni tanlang <span class="text-red">*</span></label>
                                            <select class="form-control"
                                                    name="is_inps" required>
                                                <option value="" disabled>Tanlang</option>
                                                <option value="1" @if (1 == ($model->is_inps ?? '')) {{ 'selected' }} @endif>Tashkilot xodimi (INPS bor)</option>
                                                <option value="2" @if (2 == ($model->is_inps ?? '')) {{ 'selected' }} @endif>Organ xodimi (INPS yo`q)</option>
                                                <option value="3" @if (3 == ($model->is_inps ?? '')) {{ 'selected' }} @endif>Nafaqada (INPS yo`q)</option>
                                            </select>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="box-header with-border bg-gray-light">
                            <h3 class="box-title text-green text-bold"><i class="fa fa-map-marker"></i> Yashash joy
                                manzil ma`lumotlari</h3>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Viloyat <span class="text-red">*</span></label>
                                            <select class="form-control select2" name="registration_region" id="registration_region" required>
                                                <option disabled selected value>@lang('blade.select')</option>
                                                @foreach ($regions as $key => $filial)
                                                    <option value="{{ $filial->code}}" @if($filial->code == ($model->registration_region ?? '')) selected="selected" @endif>
                                                        {{ $filial->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Tuman <span class="text-red">*</span></label>
                                            <select id="reg_district" class="form-control select2"
                                                    name="registration_district" required>
                                                <option value="{{ $model->registration_district ?? '' }}">{{ $model->district->name ?? '' }}</option>
                                            </select>

                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Manzili (Propiska)<span class="text-red">*</span></label>
                                            <input type="text" name="registration_address"
                                                   class="form-control" value="{{ $model->registration_address ?? ''}}"
                                                   placeholder="Manzili" required>

                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Ish joy manzili<span class="text-red">*</span></label>
                                            <input type="text" name="job_address"
                                                   class="form-control" value="{{ $model->job_address ?? '' }}"
                                                   placeholder="Ij joy manzili" required>

                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Oila a`zolari soni <span class="text-red">*</span></label>
                                            <select class="form-control"
                                                    name="live_number" required>
                                                <option value="1" @if (1 == ($model->live_number ?? '')) {{ 'selected' }} @endif>1</option>
                                                <option value="2" @if (2 == ($model->live_number ?? '')) {{ 'selected' }} @endif>2</option>
                                                <option value="3" @if (3 == ($model->live_number ?? '')) {{ 'selected' }} @endif>3</option>
                                                <option value="4" @if (4 == ($model->live_number ?? '')) {{ 'selected' }} @endif>4</option>
                                                <option value="5" @if (5 == ($model->live_number ?? '')) {{ 'selected' }} @endif>5+</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Telefon raqami <span class="text-red">*</span></label>
                                            <input type="text" name="phone" required
                                                   data-inputmask='"mask": "(99) 999-999-99-99"' data-mask
                                                   class="form-control" value="{{ $model->phone ?? '' }}"
                                                   placeholder="(99) 894-123-45-67">

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-flat btn-primary prg">
                                    @lang('blade.next') <i class="fa fa-fast-forward"></i></button>
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
        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <!-- Select2 -->
        <link href="{{ asset("/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css">
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>
        <!-- InputMask -->
        <script src="{{ asset('/admin-lte/plugins/input-mask/jquery.inputmask.js') }}"></script>
        <script src="{{ asset('/admin-lte/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $(function () {
                    //Initialize Select2 Elements
                    $(".select2").select2();

                    $('#credit_security').on('change', function() {

                        var value=$(this).find(':selected').html();

                        $('.credit_security_name').html(value);
                    });

                    $("[data-mask]").inputmask();
                });

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $("#document_region").change(function () {
                    var region_code = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "/get-districts",
                        data: {_token: CSRF_TOKEN, region_code: region_code},
                        dataType: 'JSON',
                        success: function (res) {
                            var districtData = "";
                            var obj = res;
                            if (res) {
                                $("#district").empty();

                                if (obj['msg'] != 0) {

                                    $('#document_district').show();

                                    $("#district").append('<option value="" disabled selected>Tumanni tanlang</option>');

                                    $.each(obj['msg'], function (key, val) {
                                        districtData += '<option value="' + val.code + '">' + val.name + '</option>';
                                    });

                                } else {
                                    $('#document_district').hide();
                                }

                                $("#district").append(districtData); //// For Append
                            }
                        },

                        error: function () {
                            console.log('error');
                        }
                    });
                });

                $("#registration_region").change(function () {
                    var reg_region_code = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "/get-reg-districts",
                        data: {_token: CSRF_TOKEN, reg_region_code: reg_region_code},
                        dataType: 'JSON',
                        success: function (res) {
                            console.log(res);
                            var districtRegData = "";
                            var obj = res;
                            if (res) {
                                $("#reg_district").empty();

                                if (obj['msg'] != 0) {

                                    $('#registration_district').show();

                                    $("#reg_district").append('<option value="" disabled selected>Tumanni tanlang</option>');

                                    $.each(obj['msg'], function (key, val) {
                                        districtRegData += '<option value="' + val.code + '">' + val.name + '</option>';
                                    });

                                } else {
                                    $('#document_district').hide();
                                }

                                $("#reg_district").append(districtRegData); //// For Append
                            }
                        },

                        error: function () {
                            console.log('error');
                        }
                    });
                });

                //Date picker
                $('#datepicker').datepicker({
                    autoclose: true
                });
                $('.input-datepicker').datepicker({
                    todayBtn: 'linked',
                    todayHighlight: true,
                    format: 'dd.mm.yyyy',
                    autoclose: true
                });
                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    forceParse: false,
                    todayHighlight: true,
                    format: 'dd.mm.yyyy',
                    autoclose: true
                });
                $(function () {
                    $(".latin-only").keypress(function (event) {
                        if (!((event.which >= 65 && event.which <= 90) ||
                            (event.which >= 97 && event.which <= 122) || event.which === 32)) {
                            event.preventDefault();
                        }
                        //$(".latin-only").val(($(".latin-only").val()).toUpperCase());
                    });
                });

            });

        </script>
    </section>
    <!-- /.content -->

@endsection

