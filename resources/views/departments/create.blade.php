<?php ?>
@extends('layouts.uw.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Create Department
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">Departments</a></li>
            <li class="active">Create Department</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- SELECT2 EXAMPLE -->
        <div class="box box-success">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <!-- Message Succes -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif
                <!-- Display Validation Errors -->
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Xatolik!</strong> Ma`lumotlarni qaytadan tekshiring.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                @endif
                <!-- List departments -->
                    <div class="col-md-6">
                        <div class="form-group" style="overflow-y: scroll; max-height: 600px">
                            <label>Department List</label>

                            <ul id="tree1">

                                @foreach($filial as $department)

                                    <li>

                                        {{ $department->branch_code }} - {{ $department->title }}

                                        @if(count($department->childs))

                                            @include('departments.departChild',['childs' => $department->childs])

                                        @endif

                                    </li>

                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form role="form" method="POST" action="{{ url('/departments') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Filial</label>
                                <select
                                        id="filial_code"
                                        class="form-control select2"
                                        name="branch_code"
                                        style="width: 100%;">

                                    <option disabled selected value>Filialni tanlang</option>
                                    @foreach($filial as $value)
                                        <option value="{{$value->branch_code}}">{{$value->branch_code. ' - ' .$value->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="departdiv" hidden>
                                <label>Departament</label>
                                <select
                                        id="depart"
                                        class="form-control select2"
                                        name="parent_id"
                                        style="width: 100%;">

                                    <option selected="selected" value=""></option>
                                </select>
                            </div>

                            <div class="form-group" id="departdiv2" hidden>
                                <label>Sub Departament</label>
                                <select
                                        id="sub_depart"
                                        class="form-control select2"
                                        name="parent_id"
                                        style="width: 100%;">

                                    <option selected="selected" value=""></option>
                                </select>
                            </div>

                            <div class="form-group" id="departdiv3" hidden>
                                <label>Sub Sub Departament</label>
                                <select
                                        id="sub_sub_depart"
                                        class="form-control select2"
                                        name="parent_id"
                                        style="width: 100%;">

                                    <option selected="selected" value=""></option>
                                </select>
                            </div>
                        <!-- /.form-group -->
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter title">
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Title Ru</label>
                                <input type="text" name="title_ru" class="form-control" placeholder="Enter title Ru">
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Local Code</label>
                                <input type="text" name="local_code" class="form-control" placeholder="Local Code">
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Statusni belgilang</label>
                                <select class="form-control select2" name="status">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Passive</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <a href="/home" class="btn btn-default">Bekor qilish </a>
                                        <button type="submit" class="btn btn-primary">Saqlash</button>
                                    </div>

                                </div>
                            </div>
                            <!-- /.col -->
                        </form>
                        <!-- /.form-group -->
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <script type="text/javascript">
            $(document).ready(function () {


                $(".btn-success").click(function () {
                    var html = $(".clone").html();
                    $(".increment").after(html);
                });

                $("body").on("click", ".btn-danger", function () {
                    $(this).parents(".control-group").remove();
                });

                $("#loding1").hide();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


                $("#filial_code").change(function () {
                    $("#loding1").show();
                    var branch_code = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "/get-department",
                        data: {_token: CSRF_TOKEN, branch_code: branch_code},
                        dataType: 'JSON',
                        success: function (res) {
                            var user_input = "";
                            var obj = res;

                            console.log(res);
                            console.log(res.req);
                            console.log(res.branch);
                            if (res) {
                                $("#loding1").hide();
                                $("#depart").empty();
                                if(obj['msg'] != 0){
                                    $('#departdiv').show();
                                    $('#departdiv2').hide();
                                    $('#departdiv3').hide();
                                    $("#depart").append('<option value='+res.req+' disabled selected> Departamentni tanlang </option>');
                                    $("#sub_depart").append('<option value='+res.req+' selected></option>');
                                    $("#sub_sub_depart").append('<option value='+res.req+' selected></option>');

                                    $.each(obj['msg'], function (key, val) {
                                        user_input += '<option value="' + val.id + '"> --- ' + val.title + '</option>';
                                    });
                                }
                                else{
                                    $('#departdiv').hide();
                                }
                                $("#depart").append(user_input); //// For Append
                            }
                        },

                        error: function () {
                            console.log('error');
                        }
                    });
                }); // End of #filial_code
                $("#depart").change(function () {
                    $("#loding1").show();
                    var dataString = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "/postbranch",
                        data: {_token: CSRF_TOKEN, name: dataString},
                        dataType: 'JSON',
                        success: function (res) {
                            var user_input = "";
                            var obj = res;
                            console.log(res);
                            console.log(res.req);

                            if (res) {
                                $("#loding1").hide();
                                $("#sub_depart").empty();
                                $("#sub_depart").append('<option value='+res.req+' disabled selected> Sub Departamentni tanlang</option>');
                                $("#sub_sub_depart").append('<option value='+res.req+' selected> Sub Departamentni tanlang </option>');
                                //  **************  Jamshid Edited from here    ******************
                                if(obj['msg'] != 0){
                                    $('#departdiv2').show();
                                    $('#departdiv3').hide();
                                    $.each(obj['msg'], function (key, val) {
                                        user_input += '<option value="' + val.id + '">' + val.title + '</option>';

                                    });
                                }
                                else{
                                    $('#departdiv2').hide();
                                    $('#departdiv3').hide();
                                }
                                $("#sub_depart").append(user_input); //// For Append
                            }
                        },
                        error: function () {
                            console.log('error');
                        }
                    });
                }); // End Of #depart
                $("#sub_depart").change(function () {
                    $("#loding1").show();
                    var dataString = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "/postbranch",
                        data: {_token: CSRF_TOKEN, name: dataString},
                        dataType: 'JSON',
                        success: function (res) {
                            var user_input = "";
                            var obj = res;

                            console.log(res);
                            console.log(res.req);

                            if (res) {
                                $("#loding1").hide();
                                $("#sub_sub_depart").empty();
                                $("#sub_sub_depart").append('<option  value='+res.req+' disabled selected> Sub Sub Departamentni tanlang </option>');

                                if(obj['msg'] != 0){
                                    $('#departdiv3').show();
                                    $.each(obj['msg'], function (key, val) {
                                        user_input += '<option value="' + val.id + '">' + val.title + '</option>';
                                    });
                                }
                                else{
                                    $('#departdiv3').hide();
                                }

                                $("#sub_sub_depart").append(user_input); //// For Append
                            }
                        },
                        error: function () {
                            console.log('error');
                        }
                    });
                }); // End #sub_depart
            }); //End of $(document).ready(function ()

        </script>
    </section>
    <!-- /.content -->

    <script src="{{ asset('js/treeview.js') }}"></script>
    <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
@endsection
