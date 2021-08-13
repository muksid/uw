@extends('layouts.dashboard')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            App Templetes
            <small>lists</small>
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

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div>
                    <a href="{{ url('/madmin/app/create') }}" class="btn bg-olive-active btn-flat margin">
                        <i class="fa fa-plus"></i> @lang('blade.add')
                    </a>
                </div>

                <div class="box box-primary">


                    <div class="box-body">
                    @if($models->count())
                        <b>@lang('blade.overall'): {{ $models->count()??''}} @lang('blade.group_edit_count').</b>
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
                                        <td><a href="{{ url('/madmin/app/'.$model->id)}}">{{ $model->title}}</a></td>
                                        <td>
                                            @if($model->type == 'P')
                                                <span class="text-orange text-bold">Physical</span>
                                            @elseif($model->type == 'J')
                                                <span class="text-green text-bold">Juridical</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($model->status == 'A')
                                                <span class="badge bg-green-active"><i class="fa fa-check"></i></span>
                                            @elseif($model->status == 'P')
                                                <span class="badge bg-red-active"><i class="fa fa-ban"></i></span>
                                            @endif
                                        </td>
                                        <td>{{ $model->created_at}}</td>
                                        <td class="text-orange text-center"> <a href="{{ url('/madmin/app/'.$model->id.'/edit')}}"><i class="fa fa-pencil"></i></a></td>
                                        <td class="text-red">
                                            <a type="button" class="btn btn-danger deleteButton" data-id="{{$model->id}}" data-toggle="modal" data-target="#ConfirmModal">
                                                <i class="fa fa-trash" ></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                    <h4 class="text-red">@lang('blade.not_found')</h4>
                    @endif
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
                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">
                                            @lang('blade.cancel')
                                        </button>
                                        <button type="button" class="btn btn-outline" id="yesDelete" value="create">Ha, O`chirish
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

                    <!--Response Modal -->
                    <div class="modal fade modal-success" id="responseModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="responseModalLabel"></h5>
                                    
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" data-dismiss="modal" aria-label="Close">
                                        @lang('blade.close')
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



        <script>
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

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


            // crud form
            $(document).ready(function () {
                $("#example1").DataTable()

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

            })

            $('.deleteButton').on('click',function () {
                let id = $(this).data('id')
                $('#yesDelete').on('click',function () {
                    $.ajax({
                        url: '/madmin/app/'+id,
                        type: 'DELETE',
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        beforeSend: function(){
                            $("#loading").show();
                        },
                        success: function(res){
                            
                            $('#ConfirmModal').modal('toggle')
                            $('#responseModalLabel').text(res.message)
                            $('#responseModal').modal('toggle')

                            $('#responseModal').on('hidden.bs.modal', function () {
                                location.reload();
                            })

                            setTimeout(function() {
                                location.reload();
                            }, 2500);

                        },
                        complete:function(res){
                            $("#loading").hide();
                        },

                        error: function (data) {
                            console.log(data)
                        }
                    });
                })
            })

        </script>
    </section>
    <!-- /.content -->
@endsection
