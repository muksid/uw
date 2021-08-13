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
                        <form id="createForm" method="POST" action="{{ url('/madmin/app')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="create_title">Title</label>
                                <input type="text" class="form-control" id="create_title" name="title" aria-describedby="emailHelp" placeholder="Templete title ..." required>
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                            </div>

                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="create_type">Type of Guarantor</label>
                                    <select class="form-control" id="create_type" name="type">
                                        <option class="text-muted">Select Guarantor</option>
                                        <option value="P">Phy</option>
                                        <option value="J">Jur</option>
                                    </select>
                                </div>
                            </div>
                            
                            <h5 class="text-bold">Status</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="create_status_active" value="A" checked>
                                <label class="form-check-label" for="create_status_active">
                                    Active
                                </label>
                                <input class="form-check-input" type="radio" name="status" id="create_status_passive" value="P">
                                <label class="form-check-label" for="create_status_passive">
                                    Passive
                                </label>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="editor1">Body</label>
                                <textarea class="form-control"  class="text create_body" id="editor1" name="body" required></textarea>
                            </div>
                            <div class="col-md text-center">
                                <button type="button" class="btn btn-orange text-center" onclick="clearForm()">Clear</button>
                                <button type="submit" class="btn btn-success text-center">Save</button>
                            </div>
                        </form>
                    </div>

                    <div class="modal fade" id="resultKATMModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" style="width: auto; max-width: 1100px">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title text-center" id="success">Mijozning kredit tarixi (KATM)</h4>
                                </div>
                                <div id="reportBase64Modal"></div>
                                <form id="roleForm14" name="roleForm14">
                                    <div class="modal-body">
                                        <input type="hidden" name="claim_id" id="katmClaimId">
                                        <input type="hidden" name="katmSumm" id="katmSumm" value="">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-flat pull-right btn-default" data-dismiss="modal">@lang('blade.close')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

        
                    <div class="modal fade modal-success" id="successModal" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
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
                $(".select2").select2();

                //Date picker
                $('#datepicker').datepicker({
                    autoclose: true
                });
                $('.input-datepicker').datepicker({
                    todayBtn: 'linked',
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    forceParse: false,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            });

            function clearForm(){
                $("#createForm").trigger('reset');
                CKEDITOR.instances.editor1.setData('');             
                $( "#create_type" ).select();
            }

            // crud form
            $(document).ready(function () {
                $("#example1").DataTable();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                CKEDITOR.replace('editor1', {
                    height: 400,
                    baseFloatZIndex: 10005,
                    tabSpaces: 4,
                    extraPlugins : 'lineheight',
                    line_height:"1em;1.2em;1.4em;1.6em;1.8em;2em;2.5em;2.5em;",


                    // toolbar: [ [ 'Source', 'Bold' ], ['CreatePlaceholder'] ]
                });
                
                

            })


        </script>
    </section>
    <!-- /.content -->
@endsection
