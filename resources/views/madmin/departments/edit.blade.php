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
            <li class="active">edit</li>
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

    <section class="content">

        <div class="box box-success">
            <div class="box-header with-border">
                <h4>{{ $department->branch_code }} - <b>{{ $department->title }}</b></h4>
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

                                            @include('madmin.departments.departChild',['childs' => $d->childs])

                                        @endif

                                    </li>

                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <form role="form" method="POST" action="{{ url('madmin/departments', ['id' => $department->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Filial</label>
                                <select id="branch_code" name="branch_code" class="form-control select2"
                                        style="width: 100%;">

                                    <option value="{{$department->branch_code}}" selected="selected">@lang('blade.select')</option>
                                    @foreach($filials as $value)
                                        <option value="{{$value->branch_code}}">
                                            {{$value->branch_code. ' - ' .$value->title}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" id="departDiv" hidden>
                                <label>Departament</label>
                                <select
                                        id="depart"
                                        class="form-control select2"
                                        name="parent_id"
                                        style="width: 100%;">

                                    <option selected="selected" value="{{$department->parent_id}}"></option>
                                </select>
                            </div>

                            <div class="form-group" id="departDiv2" hidden>
                                <label>Sub Departament</label>
                                <select
                                        id="sub_depart"
                                        class="form-control select2"
                                        name="parent_id"
                                        style="width: 100%;">

                                    <option selected="selected" value="{{$department->parent_id}}"></option>
                                </select>
                            </div>

                            <div class="form-group" id="departDiv3" hidden>
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
                                <label>@lang('blade.title_uz')</label>
                                <input type="text" name="title" class="form-control" value="{{$department->title}}">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('blade.title_ru')</label>
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
                                    <a href="{{ route('departments.index') }}" class="btn btn-default">@lang('blade.cancel') </a>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-pencil"></i> @lang('blade.update')</button>
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

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $("#branch_code").change(function () {

                let branch_code = $(this).val();

                $.ajax({
                    type: "POST",
                    url: "/madmin/get-department",
                    data: {_token: CSRF_TOKEN, branch_code: branch_code},
                    dataType: 'JSON',
                    success: function (res) {
                        let data = ''
                        let obj = res;

                        if (res) {

                            $("#depart").empty();

                            if(obj['models'] !== 0){

                                $('#departDiv').show();

                                $('#departDiv2').hide();

                                $('#departDiv3').hide();

                                $("#depart").append('<option value='+res.depart_id+'> Departamentni tanlang </option>');

                                $("#sub_depart").append('<option value='+res.depart_id+' selected></option>');

                                $("#sub_sub_depart").append('<option value='+res.depart_id+' selected></option>');

                                $.each(obj['models'], function (key, val) {
                                    data += '<option value="' + val.id + '"> --- ' + val.title + '</option>';
                                });
                            }
                            else{
                                $('#departDiv').hide();
                            }
                            $("#depart").append(data); //// For Append
                        }
                    },

                    error: function () {
                        console.log('error');
                    }
                });
            }); // End of #branch_code

            $("#depart").change(function () {

                let id = $(this).val();

                $.ajax({
                    type: "POST",
                    url: "/madmin/get-sub-department",
                    data: {_token: CSRF_TOKEN, depart_id: id},
                    dataType: 'JSON',
                    success: function (res) {
                        let data = "";
                        let obj = res;
                        if (res) {

                            $("#sub_depart").empty();

                            $("#sub_depart").append('<option value='+res.depart_id+'> Sub Departamentni tanlang</option>');

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

                            $("#sub_sub_depart").append('<option  value='+res.depart_id+' selected> Sub Sub Departamentni tanlang </option>');

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
        });
    </script>
@endsection
