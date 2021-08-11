@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Jismoniy shaxlar
            <small>ariza yaratish</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">physical</a></li>
            <li class="active">create</li>
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
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Kredit turlari</h3>
                        <div class="box-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="credit_type" id="credit_type" class="form-control select2" style="width: 100%;">
                                    <option selected>Kredit turini tanlang</option>
                                    @foreach($loan_models as $key => $value)

                                        <option value="{{ $value->credit_type }}">{{ $value->title }}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <table class="table table-hover" id="loan_tables">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <th>Kredit turi</th>
                                <th>Foiz %</th>
                                <th>Kredit Davri</th>
                                <th>Imtiy davr</th>
                                <th>Valyuta</th>
                                <th>Qarz yuki %</th>
                            </tr>
                            @foreach($models as $key => $model)

                                <tr class="clickable-row tr-cursor" data-href="{{ route('phy.create.step.one',['id' => $model->id]) }}">
                                    <td>{{ $key+= 1 }}</td>
                                    <td>{{ $model->title }}</td>
                                    <td class="text-green">{{ $model->procent }} %</td>
                                    <td>{{ $model->credit_duration }} oy</td>
                                    <td>{{ $model->credit_exemtion }} oy</td>
                                    <td>{{ $model->currency }}</td>
                                    <td class="text-maroon">{{ $model->dept_procent }} %</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                                            <input type="text" id="calcLoanInterest" name="calcLoanInterest"
                                                   class="form-control" value="{{ old('calcLoanInterest') }}">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="slider-label">Kredit muddati (oy)<sup
                                                        class="text-red">*</sup></label>
                                            <input type="text" id="calcLoanMonth" name="calcLoanMonth"
                                                   class="form-control" value="{{ old('calcLoanMonth') }}">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="slider-label">Kredit Turi<sup
                                                        class="text-red">*</sup></label>
                                            <select name="calcLoanType" id="calcLoanType" class="form-control select2"
                                                    style="width: 100%;">
                                                <option value="1">Differentsial (Oddiy)</option>
                                                <option value="2">Annuitet</option>
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
                                <tbody class="calc-table">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /. box -->
            </div>
        </div>
        <!-- /.row -->

        <script>
            $(document).ready(function() {

                $("#credit_type").change(function() {
                    var id = $(this).val();
                    $.ajax({
                        type: "get",
                        url: "{{ route('uw.get.loan.type') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'credit_type': id
                        },
                        success: function(data) {
                            console.log(data);
                            $('#loan_tables').html(data);
                        }
                    });
                });

            });

            jQuery(document).ready(function($) {
                $(".clickable-row").click(function() {
                    window.location = $(this).data("href");
                });
            });

            $('#result_calc_div').hide();

            $("#calcForm").on('submit', function (event) {
                event.preventDefault();

                calcSumma = $('#calcSumma').val();
                calcLoanInterest = $('#calcLoanInterest').val();
                calcLoanMonth = $('#calcLoanMonth').val();
                calcLoanType = $('#calcLoanType').val();

                $.ajax({
                    url: '/phy/calc-form',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        calcSumma: calcSumma,
                        calcLoanInterest: calcLoanInterest,
                        calcLoanMonth: calcLoanMonth,
                        calcLoanType: calcLoanType
                    },
                    success: function (res) {
                        $('#result_calc_div').show();
                        $('.calc-table').html(res.table_data);
                        $('#total_summ').html(res.total_summ);
                        $('#total_month').html(res.total_month);
                        $('#total_interest').html(res.total_interest);
                        $('#loanInterset').html(curr + ' %');

                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });

            $('.price-summa').on('keydown', function (e) {

                if (this.selectionStart || this.selectionStart == 0) {
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
@endsection
