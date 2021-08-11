@extends('uw_log.uw.dashboard')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Mijoz ma`lumotlari
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">underwriter</a></li>
            <li class="active">underwriter</li>
        </ol>
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
                    <form action="{{ route('client.store') }}" method="POST">
                        @csrf

                        <div class="box-header with-border">
                            <h3 class="box-title text-aqua text-bold"><i class="fa fa-user"></i> yuridik ma`lumotlari
                            </h3>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>STIR<span class="text-red">*</span></label>
                                            <input type="text" name="inn"
                                                   class="form-control latin-only" value="{{ $model['inn'] }}"
                                                   readonly>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Code filial</label>
                                            <input type="text" class="form-control" value="{{ $model['code_filial']??'' }}"
                                                   readonly>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Юрид номер заявки</label>
                                            <input type="text" name="claim_number" class="form-control" value="{{ $model['registration_document_number']??'' }}"
                                                   readonly>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-5">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Mijoz nomi<span class="text-red">*</span></label>
                                            <input type="text" name="jur_name"
                                                   class="form-control latin-only" value="{{ trim(str_replace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $model['name'])) }}"
                                                   readonly>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('oked') ? 'has-error' : '' }}">
                                            <label>ОКЭД</label>
                                            <input type="text" name="oked" class="form-control" value="{{ $model['oked']??'' }}">

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('okpo') ? 'has-error' : '' }}">
                                            <label>ОКПО</label>
                                            <input type="text" name="okpo" class="form-control" value="{{ $model['organ_directive_code'] }}">

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('code_juridical_person') ? 'has-error' : '' }}">
                                            <label>ОКПО (OnlineReg)</label>
                                            <input type="text" name="code_juridical_person" class="form-control" value="{{ $model['code_juridical_person'] }}">

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('owner_form') ? 'has-error' : '' }}">
                                            <label>Код формы собственности</label>
                                            <input type="text" name="owner_form" class="form-control" value="{{ $model['property_form_code'] }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('hbranch') ? 'has-error' : '' }}">
                                            <label>Код вышестоящей организации</label>
                                            <input type="text" name="hbranch" class="form-control" value="{{ $model['organization_head_code']??'' }}">

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="box-header with-border bg-success">
                            <h3 class="box-title text-green text-bold"><i class="fa fa-map-marker"></i> Manzil ma`lumotlari</h3>
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="box-body">
                                        <p><i class="fa fa-bank"></i> Main</p>

                                        <p class="text-bold">{{ $model['code_filial'] }}</p>
                                    </div>

                                </div>
                                <div class="col-md-1">
                                    <div class="box-body">
                                        <p><i class="fa fa-bank"></i> Filial</p>

                                        <p class="text-bold">{{ $model['main_filial'] }}</p>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <p><i class="fa fa-map-marker margin-r-5"></i> Viloyat</p>

                                        <p class="text-bold">{{ trim($model['reg_name']) }}</p>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">
                                        <p><i class="fa fa-map-marker margin-r-5"></i> Tuman (shahar)</p>

                                        <p class="text-bold">{{ trim($model['dis_name']) }}</p>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Address<span class="text-red">*</span></label>
                                            <input type="text" name="registration_address" class="form-control" value="{{ $model['address'] }}">

                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Telefon raqami <span class="text-red">*</span></label>
                                            <input type="text" name="phone"
                                                   data-inputmask='"mask": "(99) 999-999-99-99"' data-mask
                                                   class="form-control" value="{{ $model['phone'] }}"
                                                   placeholder="(99) 894-123-45-67">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="box-body">

                                        <strong><i class="fa fa-pencil margin-r-5"></i> Juridical Resident</strong>

                                        <p><br>
                                            @if($model['resident_code'] == 1)
                                                <span class="label label-primary">Rezident</span>
                                            @else
                                                <span class="label label-warning">No Rezident</span>
                                            @endif
                                        </p>
                                    </div>

                                </div>
                                <input type="text" name="client_id" value="{{ $model['id'] }}" hidden>
                                <input type="text" name="client_code" value="{{ $model['code'] }}" hidden>
                                <input type="text" name="client_type" value="{{ $model['typeof'] }}" hidden>
                                <input type="text" name="registration_region" value="{{ $model['region_code'] }}" hidden>
                                <input type="text" name="registration_district" value="{{ $model['district_code'] }}" hidden>
                                <input type="text" name="resident" value="{{ $model['resident_code'] }}" hidden>

                            </div>
                        </div>

                        <div class="box-body bg-danger">
                            <h3 class="box-title text-danger text-bold">
                                <i class="fa fa-credit-card"></i> Kredit ma`lumotlari
                            </h3>
                            <div class="col-md-5">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Kredit turi <span class="text-red">*</span></label>
                                        <select class="form-control select2" name="loan_type_id" id="loan_type_id" required>
                                            @foreach ($loans as $key => $loan)
                                                <option value="{{ $loan->id}}">
                                                    {{ $loan->title.' ('.$loan->procent.'%, '.$loan->credit_duration.'oy, imt:'.$loan->credit_exemtion.'oy)' }}
                                                </option>
                                            @endforeach
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
            $("#loading").hide();

            $("#search").click(function () {

                let tin = $('#search_tin').val();

                $.ajax({
                    url: '/jur/ora-search',
                    type: 'GET',
                    data: {tin: tin},
                    dataType: 'json',
                    beforeSend: function(){
                        $("#loading").show();
                    },
                    success: function(res){
                        console.log(res)
                        let table = '';
                        let key = 1;
                        for (let i = 0; i < res.length; i++){
                            let val = res[i];

                            table+=
                                '<tr>' +
                                '<td>'+ key++ +'.</td>' +
                                '<td>'+val.code_filial+'</td>' +
                                '<td>'+val.code+'</td>' +
                                '<td><a href="view-form/'+val.inn+'">'+val.name+'</a></td>' +
                                '<td>'+val.inn+'</td>' +
                                '<td>'+val.director_name+'</td>' +
                                '<td class="text-sm">'+val.reg_name+'</td>' +
                                '<td class="text-sm">'+val.dis_name+'</td>' +
                                '<td class="text-sm">'+val.address+'</td>' +
                                '<td>'+formatDate(val.date_open)+'</td>' +
                                '</tr>';
                        }
                        $('.data-table').html(table);

                    },
                    complete:function(res){
                        $("#loading").hide();
                    }

                });

            });

            $('#search_tin').keydown(function(event){

                var keyCode = (event.keyCode ? event.keyCode : event.which);
                if (keyCode === 13) {

                    $('#search').trigger('click');

                }
            });

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

                $('#credit_security').on('change', function() {

                    var value=$(this).find(':selected').html();

                    $('.credit_security_name').html(value);
                });

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
