@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Departamentlar
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">departments</a></li>
            <li class="active">create</li>
        </ol>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif

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

        <!-- SELECT2 EXAMPLE -->
        <div class="box box-success">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group" style="overflow-y: scroll; max-height: 600px">
                            <label>Department List</label>

                            <ul id="tree1">

                                @foreach($filial as $department)

                                    <li>

                                        {{ $department->branch_code }} - {{ $department->title }}

                                        @if(count($department->childs))

                                            @include('madmin.departments.departChild',['childs' => $department->childs])

                                        @endif

                                    </li>

                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form role="form" method="POST" action="{{ route('departments.store') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Filial</label>
                                <select
                                        id="branch_code"
                                        class="form-control select2"
                                        name="branch_code"
                                        style="width: 100%;">

                                    <option disabled selected value>Filialni tanlang</option>
                                    @foreach($filial as $value)
                                        <option value="{{$value->branch_code}}">{{$value->branch_code. ' - ' .$value->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="departDiv" hidden>
                                <label>Departament</label>
                                <select
                                        id="depart"
                                        class="form-control select2"
                                        name="parent_id"
                                        style="width: 100%;">

                                    <option selected="selected" value=""></option>
                                </select>
                            </div>

                            <div class="form-group" id="departDiv2" hidden>
                                <label>Sub Departament</label>
                                <select
                                        id="sub_depart"
                                        class="form-control select2"
                                        name="parent_id"
                                        style="width: 100%;">

                                    <option selected="selected" value=""></option>
                                </select>
                            </div>

                            <div class="form-group" id="departDiv3" hidden>
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

                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


                $("#branch_code").change(function () {

                    let branch_code = $(this).val();

                    $.ajax({
                        type: "POST",
                        url: "/madmin/get-department",
                        data: {_token: CSRF_TOKEN, branch_code: branch_code},
                        dataType: 'JSON',
                        success: function (res) {
                            let data = '';
                            let obj = res;

                            if (res) {

                                $("#depart").empty();

                                if(obj['models'] !== 0){
                                    $('#departDiv').show();

                                    $('#departDiv2').hide();

                                    $('#departDiv3').hide();

                                    $("#depart").append('<option value='+res.depart_id+' disabled selected> Departamentni tanlang </option>');

                                    $("#sub_depart").append('<option value='+res.depart_id+' selected></option>');

                                    $("#sub_sub_depart").append('<option value='+res.depart_id+' selected></option>');

                                    $.each(obj['models'], function (key, val) {
                                        data += '<option value="' + val.id + '"> --- ' + val.title + '</option>';
                                    });
                                }
                                else{
                                    $('#departDiv').hide();
                                }
                                $("#depart").append(data);
                            }
                        },

                        error: function () {
                            console.log('error');
                        }
                    });
                }); // End of #filial_code

                $("#depart").change(function () {

                    let id = $(this).val();

                    $.ajax({
                        type: "POST",
                        url: "/madmin/get-sub-department",
                        data: {_token: CSRF_TOKEN, depart_id: id},
                        dataType: 'JSON',
                        success: function (res) {
                            let data = '';

                            let obj = res;

                            if (res) {
                                $("#sub_depart").empty();

                                $("#sub_depart").append('<option value='+res.depart_id+' disabled selected> Sub Departamentni tanlang</option>');

                                $("#sub_sub_depart").append('<option value='+res.depart_id+' selected> Sub Departamentni tanlang </option>');

                                if(obj['subDepartment'] !== 0){
                                    $('#departDiv2').show();

                                    $('#departDiv3').hide();

                                    $.each(obj['subDepartment'], function (key, val) {
                                        data += '<option value="' + val.id + '">' + val.title + '</option>';

                                    });
                                }
                                else{
                                    $('#departDiv2').hide();

                                    $('#departDiv3').hide();
                                }
                                $("#sub_depart").append(data);
                            }
                        },
                        error: function () {
                            console.log('error');
                        }
                    });
                }); // End Of #depart

                $("#sub_depart").change(function () {

                    let id = $(this).val();

                    $.ajax({
                        type: "POST",
                        url: "/madmin/get-sub-department",
                        data: {_token: CSRF_TOKEN, depart_id: id},
                        dataType: 'JSON',
                        success: function (res) {
                            let data = '';
                            let obj = res;

                            if (res) {
                                $("#sub_sub_depart").empty();

                                $("#sub_sub_depart").append('<option  value='+res.req+' disabled selected> Sub Sub Departamentni tanlang </option>');

                                if(obj['subDepartment'] !== 0){

                                    $('#departDiv3').show();

                                    $.each(obj['subDepartment'], function (key, val) {
                                        data += '<option value="' + val.id + '">' + val.title + '</option>';
                                    });
                                }
                                else{
                                    $('#departDiv3').hide();
                                }

                                $("#sub_sub_depart").append(data);
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

@endsection
