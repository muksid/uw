@extends('layouts.uw.dashboard')

<link href="{{ asset("/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css">

@section('content')

    <section class="content-header">
        <h1>Mijoz kiritish
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li class="active">Mijoz kiritish</li>
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

        @if(session('error'))
            <div class="box box-default">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <h4 class="modal-title"> {{ session('error') }}</h4>
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

            <form action="{{ route('uw.create.step.one.client') }}" method="POST">
                @csrf
                <div class="col-md-12">
                    <div class="box box-primary">

                        <div class="card">
                            <div class="card-header">Step 1: Basic Info</div>

                            <div class="card-body">

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="box-body">
                                            <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
                                                <label>Kredit turini tanlang <span class="text-red">*</span></label>

                                                <select name="credit_type" class="form-control" id="credit_type">
                                                    <option value="">Tanlang</option>
                                                    <option value="3">Tanlang333</option>
                                                    <option value="1" @if (old('gender') == 1) {{ 'selected' }} @endif>Erkak
                                                    </option>
                                                    <option value="2" @if (old('gender') == 2) {{ 'selected' }} @endif>Ayol
                                                    </option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="title">Product Name:</label>
                                                <input type="text" value="{{ $model->name ?? '' }}" class="form-control"
                                                       id="taskTitle" name="name">
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                    <div class="form-group">
                                        <label for="title">Product family_name:</label>
                                        <input type="text" value="{{ $model->family_name ?? '' }}" class="form-control"
                                               id="taskTitle" name="family_name">
                                    </div>

                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>

    </section>

    <!-- Main content -->
    <section class="content">
        <h1>Kredit Calculator
            <small><i class="fa fa-calculator text-primary"></i></small>
        </h1>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="row">
                        <div class="col-md-8">
                            <form id="calcForm">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('calcSumma') ? 'has-error' : '' }}">
                                            <label>Kredit summa<span class=""></span></label>
                                            <input type="text" id="calcSumma" name="calcSumma"
                                                   class="form-control price-summa" value="{{ old('calcSumma') }}"
                                                   placeholder="16,000,000.00">
                                            @if ($errors->has('calcSumma'))
                                                <span class="text-red" role="alert">
                                                        <strong>{{ $errors->first('calcSumma') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="slider-label">Kredit %<sup
                                                        class="text-red">*</sup></label>
                                            <select name="calcLoanInterest" id="calcLoanInterest"
                                                    class="form-control select2" style="width: 100%;">
                                                <option value="23">23 %</option>
                                                <option value="24">24 %</option>
                                                <option value="25">25 %</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="slider-label">Kredit muddati<sup
                                                        class="text-red">*</sup></label>
                                            <select name="calcLoanMonth" id="calcLoanMonth" class="form-control select2"
                                                    style="width: 100%;">
                                                <option value="12">12 oy</option>
                                                <option value="24">24 oy</option>
                                                <option value="36">36 oy</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <button id="submit" class="btn btn-success"><i
                                                    class="fa fa-calculator"></i> Hisoblash
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="col-md-4">
                            <div id="result_calc_div">
                                <div>
                                    <div>
                                        <h2>
                                            <span class="text-bold">Jami qaytarish summasi:</span><br>
                                            <span id="total_summ" class="text-red"></span> so`m
                                        </h2>
                                        <h3>
                                            <span class="text-bold">Xar oylik o`rtacha to`lov:</span><br>
                                            <span id="total_month" class="text-green"></span> so`m
                                        </h3>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sana</th>
                                    <th>Kredit qoldig`i:</th>
                                    <th>Asosiy qarz summasi:</th>
                                    <th>Kredit foizi:</th>
                                    <th>Xar oylik to`lovi:</th>
                                    <th>Kun (1 oyda)</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /. box -->
            </div>
        </div>
        <!-- /.row -->
    </section>

    <section>
        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <!-- Select2 -->
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

                    $('#result_calc_div').hide();
                    $("#calcForm").on('submit', function (event) {
                        event.preventDefault();

                        calcSumma = $('#calcSumma').val();
                        calcLoanInterest = $('#calcLoanInterest').val();
                        calcLoanMonth = $('#calcLoanMonth').val();
                        $.ajax({
                            url: '/uw/calc-form',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                calcSumma: calcSumma,
                                calcLoanInterest: calcLoanInterest,
                                calcLoanMonth: calcLoanMonth
                            },
                            success: function (res) {
                                $('#result_calc_div').show();
                                $('tbody').html(res.table_data);
                                $('#total_summ').html(res.total_summ);
                                $('#total_month').html(res.total_month);
                                $('#loanInterset').html(curr + ' %');

                            },
                            error: function () {
                                console.log('error');
                            }
                        });
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

            $('.price-summa').on('keydown', function (e) {

                if (this.selectionStart || this.selectionStart == 0) {
                    // selectionStart won't work in IE < 9

                    var key = e.which;
                    var prevDefault = true;

                    var thouSep = " ";  // your seperator for thousands
                    var deciSep = ",";  // your seperator for decimals
                    var deciNumber = 2; // how many numbers after the comma

                    var thouReg = new RegExp(thouSep, "g");
                    var deciReg = new RegExp(deciSep, "g");

                    function spaceCaretPos(val, cPos) {
                        /// get the right caret position without the spaces

                        if (cPos > 0 && val.substring((cPos - 1), cPos) == thouSep)
                            cPos = cPos - 1;

                        if (val.substring(0, cPos).indexOf(thouSep) >= 0) {
                            cPos = cPos - val.substring(0, cPos).match(thouReg).length;
                        }

                        return cPos;
                    }

                    function spaceFormat(val, pos) {
                        /// add spaces for thousands

                        if (val.indexOf(deciSep) >= 0) {
                            var comPos = val.indexOf(deciSep);
                            var int = val.substring(0, comPos);
                            var dec = val.substring(comPos);
                        } else {
                            var int = val;
                            var dec = "";
                        }
                        var ret = [val, pos];

                        if (int.length > 3) {

                            var newInt = "";
                            var spaceIndex = int.length;

                            while (spaceIndex > 3) {
                                spaceIndex = spaceIndex - 3;
                                newInt = thouSep + int.substring(spaceIndex, spaceIndex + 3) + newInt;
                                if (pos > spaceIndex) pos++;
                            }
                            ret = [int.substring(0, spaceIndex) + newInt + dec, pos];
                        }
                        return ret;
                    }

                    $(this).on('keyup', function (ev) {

                        if (ev.which == 8) {
                            // reformat the thousands after backspace keyup

                            var value = this.value;
                            var caretPos = this.selectionStart;

                            caretPos = spaceCaretPos(value, caretPos);
                            value = value.replace(thouReg, '');

                            var newValues = spaceFormat(value, caretPos);
                            this.value = newValues[0];
                            this.selectionStart = newValues[1];
                            this.selectionEnd = newValues[1];
                        }
                    });

                    if ((e.ctrlKey && (key == 65 || key == 67 || key == 86 || key == 88 || key == 89 || key == 90)) ||
                        (e.shiftKey && key == 9)) // You don't want to disable your shortcuts!
                        prevDefault = false;

                    if ((key < 37 || key > 40) && key != 8 && key != 9 && prevDefault) {
                        e.preventDefault();

                        if (!e.altKey && !e.shiftKey && !e.ctrlKey) {

                            var value = this.value;
                            if ((key > 95 && key < 106) || (key > 47 && key < 58) ||
                                (deciNumber > 0 && (key == 110 || key == 188 || key == 190))) {

                                var keys = { // reformat the keyCode
                                    48: 0, 49: 1, 50: 2, 51: 3, 52: 4, 53: 5, 54: 6, 55: 7, 56: 8, 57: 9,
                                    96: 0, 97: 1, 98: 2, 99: 3, 100: 4, 101: 5, 102: 6, 103: 7, 104: 8, 105: 9,
                                    110: deciSep, 188: deciSep, 190: deciSep
                                };

                                var caretPos = this.selectionStart;
                                var caretEnd = this.selectionEnd;

                                if (caretPos != caretEnd) // remove selected text
                                    value = value.substring(0, caretPos) + value.substring(caretEnd);

                                caretPos = spaceCaretPos(value, caretPos);

                                value = value.replace(thouReg, '');

                                var before = value.substring(0, caretPos);
                                var after = value.substring(caretPos);
                                var newPos = caretPos + 1;

                                if (keys[key] == deciSep && value.indexOf(deciSep) >= 0) {
                                    if (before.indexOf(deciSep) >= 0) {
                                        newPos--;
                                    }
                                    before = before.replace(deciReg, '');
                                    after = after.replace(deciReg, '');
                                }
                                var newValue = before + keys[key] + after;

                                if (newValue.substring(0, 1) == deciSep) {
                                    newValue = "0" + newValue;
                                    newPos++;
                                }

                                while (newValue.length > 1 &&
                                newValue.substring(0, 1) == "0" && newValue.substring(1, 2) != deciSep) {
                                    newValue = newValue.substring(1);
                                    newPos--;
                                }

                                if (newValue.indexOf(deciSep) >= 0) {
                                    var newLength = newValue.indexOf(deciSep) + deciNumber + 1;
                                    if (newValue.length > newLength) {
                                        newValue = newValue.substring(0, newLength);
                                    }
                                }

                                newValues = spaceFormat(newValue, newPos);

                                this.value = newValues[0];
                                this.selectionStart = newValues[1];
                                this.selectionEnd = newValues[1];
                            }
                        }
                    }

                    $(this).on('blur', function (e) {

                        if (deciNumber > 0) {
                            var value = this.value;

                            var noDec = "";
                            for (var i = 0; i < deciNumber; i++)
                                noDec += "0";

                            if (value == "0" + deciSep + noDec)
                                this.value = ""; //<-- put your default value here
                            else if (value.length > 0) {
                                if (value.indexOf(deciSep) >= 0) {
                                    var newLength = value.indexOf(deciSep) + deciNumber + 1;
                                    if (value.length < newLength) {
                                        while (value.length < newLength) {
                                            value = value + "0";
                                        }
                                        this.value = value.substring(0, newLength);
                                    }
                                } else this.value = value + deciSep + noDec;
                            }
                        }
                    });
                }
            });

        </script>
    </section>
    <!-- /.content -->


@endsection

