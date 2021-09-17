@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1 class="text-maroon"><i class="fa fa-user"></i>
            <small>-%</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li class="active">create</li>
        </ol>

    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">

                    <form action="{{ url('/phy/client/create/app') }}" method="POST">
                        @csrf
                        <input name="myid_id" value="{{ $model->id }}" hidden>

                        <div class="box-header with-border">
                            <h3 class="box-title text-aqua text-bold">
                                <i class="fa fa-user"></i> Mijoz ma`lumotlari
                            </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <i class="fa fa-text-width"></i>

                                            <h3 class="box-title">Passport ma`lumotlari</h3>
                                        </div>

                                        <div class="col-xs-4">
                                            <img src="{{ url('/phy/client/image',$model->id) }}" alt=""
                                                 class="img-responsive img-rounded img-thumbnail" style="max-width: 260px;">
                                        </div>

                                        <div class="col-xs-8">
                                            <div class="box-body">
                                                <dl class="dl-horizontal">
                                                    <dt>F.I.O. :</dt>
                                                    <dd>{{ $model->first_name.' '.$model->last_name.' '.$model->middle_name }}</dd>
                                                    <dt>PINFL :</dt>
                                                    <dd>{{ $model->pinfl }}</dd>
                                                    <dt>STIR :</dt>
                                                    <dd>{{ $model->inn??'-' }}</dd>
                                                    <dt>PASSPORT :</dt>
                                                    <dd>{{ $model->pass_data.' - '.$model->issued_by }}</dd>
                                                    <dt>TUG'ILGAN YILI :</dt>
                                                    <dd>{{ date('d.m.Y', strtotime($model->birth_date)) }} YIL.</dd>
                                                    <dt>TUG'ILGAN JOYI :</dt>
                                                    <dd>{{ $model->birth_place.', '.$model->birth_country }}</dd>
                                                    <dt>MILLATI :</dt>
                                                    <dd>{{ $model->nationality }}</dd>
                                                    <dt>YASHASH MANZILI :</dt>
                                                    <dd>{{ $address['region'].', '.$address['district'].', '.$model->permanent_address }}</dd>
                                                    <dd><b>KADASTR:</b> {{ $address['cadastre'] }}</dd>
                                                    <dd><b>RO'YHATGA QO'YILGAN
                                                            SANA:</b> {{ date('d.m.Y', strtotime($address['registration_date'])) }}
                                                        YIL.
                                                    </dd>
                                                </dl>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Kredit turi<span class="text-red">*</span></label>
                                            <select class="form-control select2"
                                                    id="credit_type" required>
                                                <option disabled selected value>@lang('blade.select')</option>
                                                @foreach ($loans as $key => $value)
                                                    <option value="{{ $value->credit_type }}">{!! \Illuminate\Support\Str::words($value->title, '1') !!}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Kredit turini tanlang <span class="text-red">*</span></label>
                                            <select id="sub_credit" class="form-control select2"
                                                    name="credit_type" required>
                                            </select>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Kredit summasi <span class="">*</span></label>
                                            <input type="text" id="summa" name="summa"
                                                   class="form-control price-summa" value="{{ $model->summa ?? '' }}"
                                                   placeholder="16000000.00" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Hujjat turi <span class="text-red">*</span></label>
                                            <select name="document_type" class="form-control" required>
                                                <option value="" selected>Tanlang</option>
                                                <option value="6">Passport</option>
                                                <option value="0">ID karta</option>
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
                                                   class="form-control"
                                                   placeholder="(99) 894-123-45-67">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Oila a`zolari soni <span class="text-red">*</span></label>
                                            <select class="form-control"
                                                    name="live_number" required>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5+</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Oylik daromadi <span class="text-red">*</span></label>
                                            <select class="form-control"
                                                    name="is_inps" required>
                                                <option value="">Tanlang</option>
                                                <option value="1">Bor</option>
                                                <option value="2">Yo`q</option>
                                            </select>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Ish joy manzili<span class="text-red">*</span></label>
                                            <input type="text" name="job_address"
                                                   class="form-control"
                                                   placeholder="Ish joy manzili" required>

                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-flat btn-primary prg">
                                    <i class="fa fa-save"></i> @lang('blade.save')</button>
                            </div>
                        </div>

                    </form>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

    </section>

    <section>

        <script type="text/javascript">
            $(document).ready(function () {

                $(function () {
                    //Initialize Select2 Elements
                    $(".select2").select2();

                    $('#credit_security').on('change', function () {

                        var value = $(this).find(':selected').html();

                        $('.credit_security_name').html(value);
                    });

                    $("[data-mask]").inputmask();
                });

                $("#credit_type").change(function() {

                    let code = $(this).val();

                    $.ajax({
                        type: "get",
                        url: "/madmin/get-loans/"+code,
                        success: function(res) {
                            console.log(res);
                            let creditData = '';

                            if (res) {
                                $("#sub_credit").empty();

                                if (res !== 0) {

                                    $.each(res, function (key, val) {
                                        creditData += '<option value="' + val.id + '">' + val.title +' ---('+val.procent+'% '+', '+val.credit_duration+'oy)'+ '</option>';
                                    });

                                }

                                $("#sub_credit").append(creditData);

                            }
                        }
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

            });

        </script>
    </section>

@endsection

