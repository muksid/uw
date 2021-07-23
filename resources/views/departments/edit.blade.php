<?php ?>
@extends('layouts.uw.dashboard')

@section('content')
    <section class="content-header">
        <h1>
            Edit department
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">Department</a></li>
            <li class="active">Edit department</li>
        </ol>
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

    </section>
    <!-- Main content -->
    <section class="content">

        <div class="box box-success">
            <div class="box-header with-border">
                <h4>Branch code: {{ $department->branch_code }}  -   Title: <b>{{ $department->title }}</b></h4>
                <h4></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="overflow-y: scroll; max-height: 600px">
                            <label>Department List</label>

                            <ul id="tree1">

                                @foreach($filials as $d)

                                    <li>

                                        {{ $d->branch_code }} - {{ $d->title }}
                                        @if(count($d->childs))

                                            @include('departments.departChild',['childs' => $d->childs])

                                        @endif

                                    </li>

                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <form role="form" method="POST" action="{{ url('/departments/' . $department->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Filial</label>
                                <select
                                        id="filial_code"
                                        class="form-control select2"
                                        name="branch_code"
                                        style="width: 100%;">

                                    <option value="{{$department->branch_code}}" selected="selected">Filialni tanlang</option>
                                    @foreach($filials as $value)
                                        <option value="{{$value->branch_code}}">{{$value->branch_code. ' - ' .$value->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" id="departdiv" hidden>
                                <label>Departament</label>
                                <select
                                        id="depart"
                                        class="form-control select2"
                                        name="parent_id"
                                        style="width: 100%;">

                                    <option selected="selected" value="{{$department->parent_id}}"></option>
                                </select>
                            </div>
                            <!-- Jamshid added new div for sub deps -->
                            <div class="form-group" id="departdiv2" hidden>
                                <label>Sub Departament</label>
                                <select
                                        id="sub_depart"
                                        class="form-control select2"
                                        name="parent_id"
                                        style="width: 100%;">

                                    <option selected="selected" value="{{$department->parent_id}}"></option>
                                </select>
                            </div>
                            <div class="form-group" id="departdiv3" hidden>
                                <label>Sub sub Departament</label>
                                <select
                                        id="sub_sub_depart"
                                        class="form-control select2"
                                        name="parent_id"
                                        style="width: 100%;">

                                    <option selected="selected" value="{{$department->parent_id}}"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" value="{{$department->title}}">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Title Ru</label>
                                <input type="text" name="title_ru" class="form-control"
                                       value="{{$department->title_ru}}" >
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Local Code</label>
                                <input type="text" name="local_code" class="form-control" maxlength="10"
                                       value="{{$department->local_code}}" >
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Statusni belgilang</label>
                                <select class="form-control select2" name="status">
                                    <option selected="selected"
                                            value="{{$department->status}}">@if($department->status == 1)
                                            Active @else Passive @endif </option>
                                    <option value="1">Active</option>
                                    <option value="0">Passive</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-md-6" style="float: right">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <a href="/departments" class="btn btn-default">Bekor qilish </a>
                                    <button type="submit" class="btn btn-primary">O`zgartirish</button>
                                </div>

                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </section>

    <!-- /.content -->
    <script type="text/javascript">
        $(document).ready(function () {

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
                                $("#depart").append('<option value='+res.req+'> Departamentni tanlang </option>');
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
                            $("#sub_depart").append('<option value='+res.req+'> Sub Departamentni tanlang</option>');
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
                            $("#sub_sub_depart").append('<option  value='+res.req+' selected> Sub Sub Departamentni tanlang </option>');

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
        });
    </script>
    <script src="{{ asset('js/treeview.js') }}"></script>
    <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
@endsection
