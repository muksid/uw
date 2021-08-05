@extends('uw_log.uw.dashboard')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Mijoz passport ma`lumotlari
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">personal</a></li>
            <li class="active">edit</li>
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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <form action="{{ url('/jur/client-personal',['id' => $model->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="client_type" value="{{ $ora_personal['typeof'] }}" hidden>
                        <div class="box-header with-border">
                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('family_name') ? 'has-error' : '' }}">
                                        <label>Familiya</label>
                                        <input type="text" name="family_name" class="form-control" value="{{ $ora_personal['surname_inter'] }}">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <label>Ismi</label>
                                        <input type="text" name="name" class="form-control" value="{{ $ora_personal['first_name_inter'] }}">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('patronymic') ? 'has-error' : '' }}">
                                        <label>Otasining ismi</label>
                                        <input type="text" name="patronymic" class="form-control" value="{{ $ora_personal['patronymic_inter'] }}">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Jinsi <span class="text-red">*</span></label>
                                        <select class="form-control select2" name="gender">
                                            @if($ora_personal['gender'] == 1)
                                                <option value="1">Erkak</option>
                                                <option value="2">Ayol</option>
                                            @else
                                                <option value="2">Ayol</option>
                                                <option value="1">Erkak</option>
                                            @endif
                                        </select>

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Rezidentligi <span class="text-red">*</span></label>
                                        <select class="form-control select2" name="resident">
                                            @if($ora_personal['resident_code'] == 1)
                                                <option value="1">Rezident</option>
                                                <option value="2">no Rezident</option>
                                            @else
                                                <option value="2">no Rezident</option>
                                                <option value="1">Rezident</option>
                                            @endif
                                        </select>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Passport Turi</label>
                                        <input type="text" name="document_type" class="form-control" value="{{ $ora_personal['passport_type'] }}">

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('document_serial') ? 'has-error' : '' }}">
                                        <label>P.seriya</label>
                                        <input type="text" name="document_serial" class="form-control" value="{{ $ora_personal['passport_serial'] }}" maxlength="2">

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('document_number') ? 'has-error' : '' }}">
                                        <label>P.raqami</label>
                                        <input type="text" name="document_number" class="form-control" value="{{ $ora_personal['passport_number'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('document_date') ? 'has-error' : '' }}">
                                        <label>P.sana</label>
                                        <input type="date" name="document_date" class="form-control" value="{{ date('Y-m-d', strtotime($ora_personal['passport_registration_date'])) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Passport Berilgan Viloyat <span class="text-red">*</span></label>
                                        <select class="form-control select2" name="document_region">
                                            @foreach ($regions as $key => $value)
                                                @if($value->code == $ora_personal['region_code'])
                                                    <option value="{{ $value->code}}"
                                                            selected>{{ $value->name }}</option>
                                                @else
                                                    <option value="{{ $value->code}}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Passport Berilgan Tuman <span class="text-red">*</span></label>
                                        <select class="form-control select2" name="document_district">
                                            @foreach ($districts as $key => $value)
                                                @if($value->code == $ora_personal['district_code'])
                                                    <option value="{{ $value->code}}"
                                                            selected>{{ $value->name }}</option>
                                                @else
                                                    <option value="{{ $value->code}}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Tug`ilgan yili</label>
                                        <input type="date" name="birth_date" class="form-control"
                                               value="{{ date('Y-m-d', strtotime($ora_personal['birthday'])) }}">

                                    </div>
                                </div>

                            </div>

                                <div class="col-md-4">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('registration_address') ? 'has-error' : '' }}">
                                            <label>Passport berilgan joy</label>
                                            <input type="text" name="registration_address" class="form-control" value="{{ $ora_personal['passport_registration_place'] }}">
                                        </div>
                                    </div>

                                </div>

                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Yashash joyi Viloyat <span class="text-red">*</span></label>
                                        <select class="form-control select2" name="registration_region">
                                            @foreach ($regions as $key => $value)
                                                @if($value->code == $ora_personal['region_code'])
                                                    <option value="{{ $value->code}}"
                                                            selected>{{ $value->name }}</option>
                                                @else
                                                    <option value="{{ $value->code}}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-2">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Yashash joyi Tuman <span class="text-red">*</span></label>
                                        <select class="form-control select2" name="registration_district">
                                            @foreach ($districts as $key => $value)
                                                @if($value->code == $ora_personal['district_code'])
                                                    <option value="{{ $value->code}}"
                                                            selected>{{ $value->name }}</option>
                                                @else
                                                    <option value="{{ $value->code}}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                            </div>
                                <div class="col-md-4">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('live_address') ? 'has-error' : '' }}">
                                            <label>Yashash manzili</label>
                                            <input type="text" name="live_address" class="form-control" value="{{ $ora_personal['temporary_address'] }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('pin') ? 'has-error' : '' }}">
                                            <label>PINFL</label>
                                            <input type="number" name="pin" class="form-control" value="{{ $ora_personal['pinfl'] }}">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Telefon raqami <span class="text-red">*</span></label>
                                            <input type="text" name="phone" required
                                                   data-inputmask='"mask": "(99) 999-999-99-99"' data-mask
                                                   class="form-control" value="{{ $ora_personal['phone'] }}"
                                                   placeholder="(99) 894-123-45-67">

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
            </div>
            <!-- /.col -->

        </div>

        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>

        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <!-- InputMask -->
        <script src="{{ asset('/admin-lte/plugins/input-mask/jquery.inputmask.js') }}"></script>
        <script src="{{ asset('/admin-lte/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
        <script>
            function formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2)
                    month = '0' + month;
                if (day.length < 2)
                    day = '0' + day;

                return [day, month, year].join('.');
            }

            $(function () {
                //Initialize Select2 Elements
                $(".select2").select2();

                $("[data-mask]").inputmask();
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

            function validate(evt) {
                var theEvent = evt || window.event;

                // Handle paste
                if (theEvent.type === 'paste') {
                    key = event.clipboardData.getData('text/plain');
                } else {
                    // Handle key press
                    var key = theEvent.keyCode || theEvent.which;
                    key = String.fromCharCode(key);
                }
                var regex = /[0-9]|\./;
                if( !regex.test(key) ) {
                    theEvent.returnValue = false;
                    if(theEvent.preventDefault) theEvent.preventDefault();
                }
            }

        </script>
    </section>
    <!-- /.content -->
@endsection
