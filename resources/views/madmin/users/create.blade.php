@extends('layouts.dashboard')

@section('content')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice{
            color: #000 !important;
        }
    </style>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.create_user')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.users')</a></li>
            <li class="active">@lang('blade.create_user')</li>
        </ol>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('blade.error')</strong> @lang('blade.exist').<br><br>
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

        <form id="storeForm" data-bv-live="enabled" role="form" method="POST" action="{{ route('users.store')}}">
            @csrf

            <!-- Login & Password -->
            <div  class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-11">
                            <h3>Login & Password</h3>
                        </div>
                        <div class="col-sm-1 text-right">
                            <span class="toggleButton">
                                <i id="login_password_toggle_button" data-value="1" class="fa fa-minus"></i>
                            </span>
                        </div>
                    </div>

                    <div id="login_password_div" class="row">
                        <div class="col-sm-2">
                            <div class="form-group has-feedback" id="usernameFeedback">
                                <label for="username">Username <span class="text-red username">*</span></label>
                                <input type="text" id="username" class="form-control" name="username" data-username="" minlength="3" maxlength="15" placeholder="Login || Username" value="{{ old('username') }}" autofocus required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block"><b>Must be unique</b> </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group has-feedback">
                                <label for="exampleInputPassword1">Password <span class="text-red">*</span></label>
                                <input type="password" id="inputPassword" class="form-control" name="password" data-minlength="6" placeholder="Password" required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block"><b>Minimum of 6 characters</b> </div>

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group has-feedback">
                                <label for="exampleInputPassword1">Password Confirm <span class="text-red">*</span></label>
                                <input type="password" id="inputPasswordConfirm" class="form-control" name="password_confirmation" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm" required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block"><b></b> </div>
                            </div>
                        </div>

                        <!-- Login Status -->
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-4">
                            <div class="row text-center">
                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <label for="" class="text-bold">Login Status </label>
                                </div>

                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="1" id="status1" checked>
                                        <label class="form-check-label text-success" for="status1">
                                            Active
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="0" id="status0">
                                        <label class="form-check-label text-orange" for="status0">
                                            Passive
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center" align="center">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="2" id="status2" >
                                        <label class="form-check-label text-red" for="status2">
                                            Deleted
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Personal info -->
            <div class="box box-primary" >
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-11">
                            <h3>Personal Info</h3>
                        </div>
                        <div class="col-sm-1 text-right">
                            <span class="toggleButton">
                                <i id="personal_info_toggle_button" data-value="2" class="fa fa-minus"></i>
                            </span>
                        </div>
                    </div>
                    <div id="personal_info_div">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="f_name" class="control-label">First Name <span class="text-red">*</span></label>
                                    <input type="text" id="f_name" class="form-control" name="f_name" maxlength="15" placeholder="First Name" value="{{ old('f_name') }}" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="l_name" class="control-label">Last Name <span class="text-red">*</span></label>
                                    <input type="text" id="l_name" class="form-control" name="l_name" maxlength="15" placeholder="Last Name" value="{{ old('l_name') }}" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="m_name" class="control-label">Middle Name <span class="text-red">*</span></label>
                                    <input type="text" id="m_name" class="form-control" name="m_name" maxlength="15" value="{{ old('m_name') }}" placeholder="Middle Name" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group input-daterange has-feedback">
                                    <label for="birthday" class="control-label">Birthday </label>
                                    <input type="date" id="birthday" class="form-control" name="birthday" placeholder="Middle Name" value="{{ old('birthday') }}">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b></b></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="address" class="control-label">Address</label>
                                    <input type="text" id="address" class="form-control" name="address" maxlength="60" value="{{ old('address') }}" placeholder="Address">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 60 characters</b> </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <label for="email" class="control-label">Email <span class="text-red">*</span></label>
                                    <input type="email" id="email" class="form-control" name="email" data-email="" maxlength="25" data-email="" value="{{ old('email') }}" placeholder="example@tb.uz" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block"><b>Maximum of 25 characters</b> </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!--Position -->
            <div class="box box-primary" id="positionBox" >
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-11">
                            <h3>Position</h3>
                        </div>
                        <div class="col-sm-1 text-right">
                            <span class="toggleButton">
                                <i id="position_toggle_button" data-value="5" class="fa fa-minus"></i>
                            </span>
                        </div>
                    </div>
                    <div id="position_div" class="row">

                        <!-- List departments -->
                        <div class="col-md-6">
                            <div class="form-group" style="overflow-y: scroll; max-height:500px">
                                <label>Department List</label>

                                <ul id="tree1" style="list-style: none">

                                    @foreach($filials as $department)

                                        <li class="department_class text-black text-bold" data-id="{{$department->id}}">

                                            {{ $department->branch_code }} - {{ $department->title }}

                                            @if(count($department->childs))

                                                @include('madmin.departments.departChild',['childs' => $department->childs])

                                            @endif

                                        </li>

                                    @endforeach

                                </ul>
                            </div>
                        </div>

                        <div class="col-sm-6">
                                <!-- Dpeart ID -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group has-feedback">
                                            <label for="branch_id">Branch <span class="text-red">*</span> <span id="branch"></span></label>
                                            <input type="text" id="branch_id_title" class="form-control" placeholder="Pick a branch from department list" disabled required>
                                            <input type="hidden" id="branch_id" class="form-control" name="depart_id" placeholder="Pick a branch from department list" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block"><b></b></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Job Title -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group has-feedback">
                                            <label for="job_title" class="control-label">Job Title <span class="text-red">*</span></label>
                                            <input type="text" id="job_title" class="form-control" name="job_title" maxlength="50" value="{{ old('job_title') }}" placeholder="Job title" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block"><b>Maximum of 50 characters</b> </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sort adn Card number -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group has-feedback">
                                            <label for="sort"> Sort <span class="text-red">*</span></label>
                                            <input type="number" id="sort" class="form-control" name="sort" min="0" max="1000" value="0" value="{{ old('sort') }}" placeholder="Order number in position" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block"> <b> Enter a number. Maximum of 1000</b></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group has-feedback">
                                            <label for="tab_num" class="control-label">Card Number <span class="text-red">*</span></label>
                                            <input type="number" id="tab_num" class="form-control" name="tab_num" maxlength="6" min="0" max="999999" data-tab_num="" value="{{ old('tab_num') }}" placeholder="Tabel Number" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block"><b>Maximum of 6 characters</b> </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ip and Mobile phone -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group has-feedback">
                                            <label for="ip_phone" class="control-label">Ip phone </label>
                                            <input type="tel" id="ip_phone" class="form-control" name="ip_phone" maxlength="15" value="{{ old('ip_phone') }}" placeholder="Ip phone number">
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group has-feedback">
                                            <label for="mob_phone" class="control-label">Mobile phone </label>
                                            <input type="tel" id="mob_phone" class="form-control" name="mob_phone" maxlength="15" value="{{ old('mob_phone') }}" placeholder="Mobile phone number">
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block"><b>Maximum of 15 characters</b> </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Start and End date -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group input-daterange has-feedback">
                                            <label for="date_begin" class="control-label">Start Date <span class="text-red">*</span></label>
                                            <input type="date" id="date_begin" class="form-control" name="date_begin" value="{{ old('date_begin') }}" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block"><b></b></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group input-daterange has-feedback">
                                            <label for="date_end" class="control-label">End Date</label>
                                            <input type="date" id="date_end" class="form-control" name="date_end" value="{{ old('date_end') }}">
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block"><b></b></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Roles -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group has-feedback">
                                            <label for="roles">Select Roles<span class="text-red">*</span></label>
                                            <select class="form-select select2" id="roles" aria-label="Default select example" style="width:100%" name="roles[]" multiple="multiple" required>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}"> {{ $role->title }} </option>
                                                @endforeach
                                            </select>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block"><b>You must pick at least one role </b></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- isActive -->
                                <div class="row">
                                    <div class="col-sm-2 text-center" align="center">
                                        <label for="" class="text-bold">isActive <span class="text-red">*</span></label>
                                    </div>

                                    <div class="col-sm-2 text-center" align="center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="isActive" value="A" id="active1" checked>
                                            <label class="form-check-label text-success" for="active1">
                                                Active(A)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 text-center" align="center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="isActive" value="P" id="active2">
                                            <label class="form-check-label text-orange" for="active2">
                                                Passive(P)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 text-center" align="center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="isActive" value="D" id="active3">
                                            <label class="form-check-label text-danger" for="active3">
                                                Deleted(D)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                        </div>

                    </div>
                </div>
            </div>

            <!-- Submit & Reset form -->
            <div class="box box-success">
                <div class="box-body">
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-warning" onClick="clearPosition()">Clear</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </div>

        </form>

        <script type="text/javascript">
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            function clearPosition() {
                $('#username').val("")
                $('#inputPassword').val("")
                $('#inputPasswordConfirm').val("")
                $('#f_name').val("")
                $('#l_name').val("")
                $('#m_name').val("")
                $('#birthday').val("")
                $('#address').val("")
                $('#email').val("")
                $('#branch_id').val("")
                $('#branch_id_title').val("")
                $('#job_title').val("")
                $('#sort').val(0)
                $('#tab_num').val("")
                $('#ip_phone').val("")
                $('#mob_phone').val("")
                $('#date_begin').val("")
                $('#date_end').val("")
                $('#branch').text("")
                $('#roles').val(3).trigger('change')

            }

            $(document).ready(function() {


                $('.select2').select2()

                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    forceParse: false,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                })

                $('#username').keyup(function() {
                    let username = $(this).val()
                    if(username == ""){
                        username = 1
                    }
                    if(username.length > 3){
                        $.ajax({

                            url: '/madmin/users-username-check/'+username,
                            type: 'GET',
                            data: {_token: CSRF_TOKEN},
                            dataType: 'JSON',
                            success: function(res){

                                if(!jQuery.isEmptyObject(res)){

                                    $("#username").closest('.form-group').removeClass('has-success').addClass('has-error');

                                    $('#username').data("username",username )

                                }else{
                                    $("#username").closest('.form-group').removeClass('has-error').addClass('has-success');
                                }
                                    // true
                            },
                            error: function (err) {
                                console.log(err)
                            }

                        })
                    }
                })

                $('#email').keyup(function() {
                    let email = $(this).val()
                    if(email == ""){
                        email = null
                    }

                    if(email.length > 3){
                        $.ajax({

                            url: '/madmin/personal-users-email-check/'+email,
                            type: 'GET',
                            data: {_token: CSRF_TOKEN},
                            dataType: 'JSON',
                            success: function(res){

                                if(!jQuery.isEmptyObject(res)){
                                    $("#email").closest('.form-group').removeClass('has-success').addClass('has-error');
                                    $('#email').data("email",email )

                                }else{
                                    $("#email").closest('.form-group').removeClass('has-error').addClass('has-success');
                                } // true
                            },
                            error: function (err) {
                                console.log(err)
                            }

                        })
                    }
                })

                $('#tab_num').keyup(function() {
                    let tab_num = $(this).val()
                    if(tab_num == ""){
                        tab_num = null
                    }

                    if(tab_num.length > 1){

                        $.ajax({

                            url: '/madmin/work-users-tab_num-check/'+tab_num,
                            type: 'GET',
                            data: {_token: CSRF_TOKEN},
                            dataType: 'JSON',
                            success: function(res){

                                if(!jQuery.isEmptyObject(res)){
                                    $("#tab_num").closest('.form-group').removeClass('has-success').addClass('has-error');
                                    $('#tab_num').data("tab_num",tab_num )

                                }else{
                                    $("#tab_num").closest('.form-group').removeClass('has-error').addClass('has-success')
                                } // true
                            },
                            error: function (err) {
                                console.log(err)
                            }

                        })
                    }
                })

                $('#storeForm').validator({
                    custom: {
                        username: function($el) {
                            var matchValue = $el.data("username") // foo
                            if ($el.val() == matchValue) {


                                return "Hey, that's not valid! It's gotta be " + matchValue
                            }
                        },
                        email: function($el) {
                            var matchValue = $el.data("email") // foo
                            if ($el.val() == matchValue) {

                                return "Hey, that's not valid! It's gotta be " + matchValue
                            }
                        },
                        tab_num: function($el) {
                            var matchValue = $el.data("tab_num") // foo
                            if ($el.val() == matchValue) {

                                return "Hey, that's not valid! It's gotta be " + matchValue
                            }
                        }
                    }
                })

                document.getElementById("date_begin").valueAsDate = new Date()

                $('#roles').val(3).trigger('change')

            })

            $('.department_class').on("click",function(e) {

                e.stopPropagation()

                let id = $(this).data("id")
                $('#branch_id').val(id)

                $.ajax({

                    url: '/madmin/users-get-branch/'+id,
                    type: 'GET',
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function(res){
                        let branch = res.branch.branch_code + " - " + res.branch.title
                        $('#branch').text(branch)
                        $('#branch_id_title').val(res.depart.title)

                    },
                    error: function (err) {
                        console.log(err)
                    }
                })

            })

            $('.toggleButton').on('click', function() {

                let div_type = $(this).children('i').data('value')
                let class_name = $(this).children('i')[0].className
                let child = $(this).children('i')

                if(class_name === 'fa fa-minus'){
                    child.removeClass(class_name)
                    child.addClass('fa fa-plus')
                }else{
                    child.removeClass(class_name)
                    child.addClass('fa fa-minus')
                }

                switch (div_type) {
                    // Login & Password div
                    case 1:
                        $('#login_password_div').slideToggle()

                        break;

                    // Personal Info div
                    case 2:
                        $('#personal_info_div').slideToggle()

                        break;

                    // Department div
                    case 3:
                        $('#position_div').slideToggle()

                        break;

                    // Role div
                    case 4:
                        $('#role_div').slideToggle()

                        break;
                    default:
                        break;
                }


            })


            // Mouse over effects
            $('.department_class').hover(function() {
                $(this).css('cursor','pointer')
                $(this).css({
                    "background-color" : "#e2e2e2",
                    "color":"#505050"
                },1000)
            })

            $(".department_class").mouseout(function(){
                $(this).css({
                    "background-color" : "transparent",
                    "cursor" : "default",
                    "color":"#337ab7"
                })
            })

        </script>

    </section>
    <!-- /.content -->

@endsection
