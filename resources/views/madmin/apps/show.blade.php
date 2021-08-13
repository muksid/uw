@extends('layouts.dashboard')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Create Templete
            <small>Talabnoma</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">Talabnoma</a></li>
            <li class="active">Create</li>
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
    </section>
    <div id="loading" class="loading-gif" style="display: none"></div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">
                    <div class="box-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4>@lang('blade.template_name'): {!! $model->title !!}</h4> 
                                </div>
                                <div class="col-sm-2 text-right">
                                    <a href="{{url('/madmin/app/'.$model->id.'/edit')}}" class="btn bg-orange btn-flat margin">
                                        <i class="fa fa-pencil"></i></i> @lang('blade.update')
                                    </a>
                                </div>
                                <div class="col-sm-2 text-right">
                                    <input type='button' class="btn btn-success" id='btn' style="margin: 10px" value="@lang('blade.print')" onclick='printDiv();'>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-xs-offset-2 with-border">
                                    <div id='DivIdToPrint' class="box box-solid box-default" style="width: 210mm; height: auto; padding: 4em">
                                        {!! $model->body !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

        
                    <div class="modal fade modal-success" id="successModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <h4 class="modal-title">
                                        Client <i class="fa fa-check-circle"></i>
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <h5>Client Successfully deleted</h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" data-dismiss="modal">@lang('blade.close')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>

        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <!-- ckeditor -->

        <script src="{{ asset ('/admin-lte/plugins/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset ('/admin-lte/plugins/ckeditor/samples/js/sample.js') }}"></script>


        <script>

            $(function () {
                //Initialize Select2 Elements
                $(".select2").select2()

                //Date picker
                $('#datepicker').datepicker({
                    autoclose: true
                })

                $('.input-datepicker').datepicker({
                    todayBtn: 'linked',
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                })

                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    forceParse: false,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                })
            })

            $(document).ready(function () {
                $("#example1").DataTable()
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

            })

            function printDiv() {
                var divToPrint=document.getElementById('DivIdToPrint');

                var newWin=window.open('','Print-Window');

                newWin.document.open();

                newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

                newWin.document.close();

                setTimeout(function(){newWin.close();},10);
            }


        </script>
    </section>
    <!-- /.content -->
@endsection
