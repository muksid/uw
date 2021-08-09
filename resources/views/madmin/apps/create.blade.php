@extends('layouts.dashboard')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Barcha Yuridik mijozlar
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">juridical</a></li>
            <li class="active">index</li>
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

                <div>
                
                    <button class="btn bg-olive-active btn-flat margin" data-toggle="modal" data-target="#modalFormCreate">
                        <i class="fa fa-plus-circle"></i> @lang('blade.add')
                    </button>
                </div>

                <div class="box box-primary">


                    <div class="box-body">
                    @if($models->count())
                        <b>@lang('blade.overall'): {{ $models->total()??''}} @lang('blade.group_edit_count').</b>
                        <table id="example1" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody id="roleTable">
                            <?php $i = 1 ?>
                                @foreach ($models as $key => $model)
                                    <tr id="rowId_{{ $model->id }}">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $model->title}}</td>
                                        <td>{{ $model->type}}</td>
                                        <td>
                                            @if($model->status == 0)
                                                <span class="badge bg-red-active">Taxrirlashda</span>
                                            @elseif($model->status == 1)
                                                <span class="badge bg-yellow-active">Yangi</span>
                                            @elseif($model->status == 2)
                                                <span class="badge bg-aqua-active">Yuborilgan</span>
                                            @elseif($model->status == 3)
                                                <span class="badge bg-aqua-active">Tasdiqlangan</span>
                                            @endif
                                        </td>
                                        <td>{{ $model->created_at}}</td>
                                        <td class="text-orange">edit</td>
                                        <td class="text-red">delete</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                    <h4 class="text-red">@lang('blade.not_found')</h4>
                    @endif
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

                    {{--create modal--}}
                    <div class="modal fade modal-primary" id="modalFormCreate" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="modalHeader"></h4>
                                </div>
                                <div class="box-body">
                                    <form id="createForm" method="post" action="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="create_title">Title</label>
                                            <input type="text" class="form-control" id="create_title" name="title" aria-describedby="emailHelp" placeholder="Templete title ..." required>
                                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                        </div>

                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="create_type">Type of Guarantor</label>
                                                <select class="form-control" id="create_type">
                                                    <option class="text-muted">Select Guarantor</option>
                                                    <option value="K">Kafil</option>
                                                    <option value="S">Sug`urta</option>
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
                            </div>
                        </div>
                    </div>

                    {{--edit modal--}}
                    <div class="modal fade modal-primary" id="modalForm" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="modalHeader"></h4>
                                </div>
                                <form id="roleForm" name="roleForm">
                                    <div class="modal-body">
                                        <input type="hidden" name="model_id" id="model_id">
                                        <div class="form-group">
                                            <label for="name" class="control-label">Inn</label>
                                            <input type="text" class="form-control" style="width: 100%" id="inn"
                                                   name="inn" value="" required="">
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="control-label">INPS</label>
                                            <input type="text" class="form-control" style="width: 100%" id="pin"
                                                   name="pin" value="" required="">
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="control-label">summa</label>
                                            <input type="text" class="form-control" style="width: 100%" id="summa"
                                                   name="summa" value="" required="">
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left"
                                                data-dismiss="modal">@lang('blade.cancel')</button>
                                        <button type="submit" class="btn btn-outline" id="btn-save"
                                                value="create">@lang('blade.save')
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{--delete modal--}}
                    <div id="ConfirmModal" class="modal fade modal-danger" role="dialog">
                        <div class="modal-dialog modal-sm">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                                </div>

                                <div class="modal-body">
                                    <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> Client serverdan o`chiriladi!</h4>
                                </div>

                                <div class="modal-footer">
                                    <center>
                                        <button type="button" class="btn btn-outline pull-left"
                                                data-dismiss="modal">@lang('blade.cancel')</button>
                                        <button type="button" class="btn btn-outline" id="yesDelete"
                                                value="create">Ha, O`chirish
                                        </button>
                                    </center>
                                </div>
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
                CKEDITOR.instances.editor1.setData('');                // $('#createForm').find("input[type=text], textarea").val("");
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
                    baseFloatZIndex: 10005
                });

            })


        </script>
    </section>
    <!-- /.content -->
@endsection
