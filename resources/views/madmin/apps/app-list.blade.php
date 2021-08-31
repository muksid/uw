@extends('layouts.dashboard')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Mijozlar
            <small>Talabnomasi</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">physical</a></li>
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
                <div id="loading" class="loading-gif" style="display: none"></div>

                <div class="box box-primary">

                    <div class="box-body">

                        <form action="{{url('/madmin/app-list-search')}}" method="POST" role="search" >
                            {{ csrf_field() }}

                            <div class="row">

                                <div class="col-md-2">
                                    <button type="button" class="btn bg-olive-active btn-flat margin" data-toggle="modal" data-target="#modalForm">
                                        <i class="fa fa-plus"></i> @lang('blade.add')
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="search_t" value="{{ $search_t??'' }}"
                                               placeholder="(iabs, ariza#, fio, inn, summa, mfo)">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{url('/madmin/app-list')}}" class="btn btn-flat border-success">
                                            <i class="fa fa-refresh"></i> @lang('blade.reset')
                                        </a>
                                        <button type="submit" class="btn btn-success btn-flat">
                                            <i class="fa fa-search"></i> @lang('blade.search')
                                        </button>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </form>
                    </div>
                    <div class="box-body">
                        @if ($models)
                            <b>@lang('blade.overall'){{': '. ($models->total()??'')}} @lang('blade.group_edit_count').</b>
                        @else
                            <b>@lang('blade.overall'): 0 @lang('blade.group_edit_count').</b>
                        @endif
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Loan ID</th>
                                <th>Client code</th>
                                <th>Contract</th>
                                <th>Date</th>
                                <th>Sum</th>
                                <th>FIO</th>
                                <th>Type</th>
                                <th>Template</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Show</th>
                                <th class="text-bold text-center text-red"><i class="fa fa-trash"></i></th>
                            </tr>
                            </thead>
                            <tbody id="roleTable">
                            <?php $i = 1 ?>
                            @if($models)
                                @foreach ($models as $key => $model)
                                    <tr id="rowId_{{ $model->id }}">
                                        <td>{{ $models->firstItem() + $key }}</td>
                                        <td class="text-center">{{$model->loan_id}}</td>
                                        <td class="text-center">{{$model->client_code}}</td>
                                        <td class="text-center">{{$model->contract_code}}</td>
                                        <td class="text-center">{{$model->contract_date}}</td>
                                        <td class="text-center">{{$model->summ_loan}} sum</td>
                                        <td class="text-sm text-bold text-blue">{{ $model->client_name??'Not Found' }}</td>
                                        <td class="text-center">{{$model->subject}}</td>
                                        <td class="text-sm text-center text-bold">
                                            {{ $model->appTemplate->title??'' }}
                                        </td>
                                        <td class="text-center">
                                            @if($model->status == 'A')
                                                <i class="fa fa-check text-green"></i>
                                            @elseif($model->status == 'P')
                                                <i class="fa fa-ban text-red"></i>
                                            @else
                                                <span class="text-sm text-bold">Unknown</span>
                                            @endif
                                        </td>
                                        <td class="text-sm">
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d.m.Y H:i')  }}<br>
                                            <span class="text-maroon text-sm"> ({{$model->created_at->diffForHumans()}})</span>
                                        </td>
                                        <td>
                                            <a href="{{url('/madmin/app-get-template/'.$model->id.'/'.$model->template_id)}}" 
                                                target="_blank" class="btn btn-info text-bold appButton">
                                                App
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger text-bold deleteButton" data-id="{{$model->id}}" data-toggle="modal" data-target="#ConfirmModal">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach @else
                                <td class="text-red text-center" colspan="12"><i class="fa fa-search"></i>
                                    <b>@lang('blade.not_found')</b></td>
                            @endif
                            </tbody>
                        </table>
                        <span class="paginate">{{ $models->links() }}</span>
                    </div>

                    {{--create modal--}}
                    <div class="modal fade modal-info" id="modalForm" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="modalHeader"></h4>
                                </div>
                                <form id="createForm" name="createForm">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="text" name="loan_id"       id="loan_id" hidden>
                                        <input type="text" name="client_code"   id="client_code" hidden>
                                        <input type="text" name="contract_code" id="contract_code" hidden>
                                        <input type="text" name="contract_date" id="contract_date" hidden>
                                        <input type="text" name="summ_loan"     id="summ_loan" hidden>
                                        <input type="text" name="client_name"   id="client_name" hidden>
                                        <input type="text" name="address"       id="address" hidden>
                                        <input type="text" name="typeof"        id="typeof" hidden>
                                        <input type="text" name="subject"       id="subject" hidden>
                                        <input type="text" name="filial_code"   id="filial_code" hidden>
                                        <input type="text" name="saldo_in_5"    id="saldo_in_5" hidden>
                                        <input type="text" name="saldo_in_all"  id="saldo_in_all" hidden>

                                        <div class="form-group">
                                            <label for="unique_code">Select client <span class="text-red">*</span></label>
                                            <select class="form-control select_clients" id="unique_code" style="width: 100%" required>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="create_template_id">Select template <span class="text-red">*</span></label>
                                            <select class="form-control select_template" id="create_template_id" name="template_id" style="width: 100%" required>
                                                @foreach ($templates as $template)
                                                    <option value="{{$template->id}}">{{$template->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <input type="text" name="client_type" id="client_type" value="phy" required hidden>

                                        <div class="form-check">
                                            <label for="">Status <span class="text-red">*</span></label>
                                            <input class="form-check-input" type="radio" name="status" id="create_status_active" value="A" checked>
                                            <label class="form-check-label" for="create_status_active">
                                                Active
                                            </label>
                                            <input class="form-check-input" type="radio" name="status" id="create_status_passive" value="P">
                                            <label class="form-check-label" for="create_status_passive">
                                                Passive
                                            </label>
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

                    {{-- success modal --}}
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
                                    <h5 id="successMessage"></h5>
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
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>

        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <script>
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $(function () {
                $("#example1").DataTable();
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
                
                $('.select2').select2()
                $('.select_template').select2({
                    placeholder: 'Select a template',
                })
                $('.select_clients').select2({
                    minimumInputLength: 6,
                    placeholder: 'id',
                    ajax: {
                        url: '/madmin/app-list-get-client',
                        dataType: 'json',
                        delay: 300,
                        processResults: function (data) {
                            console.log(data)
                            $('#loan_id').val(data.loan_id)
                            $('#client_code').val(data.client_code)
                            $('#contract_code').val(data.contract_code)
                            $('#contract_date').val(data.contract_date)
                            $('#summ_loan').val(data.summ_loan)
                            $('#client_name').val(data.client_name)
                            $('#address').val(data.address)
                            $('#typeof').val(data.typeof)
                            $('#subject').val(data.subject)
                            $('#filial_code').val(data.filial_code)
                            $('#saldo_in_5').val(data.saldo_in_5)
                            $('#saldo_in_all').val(data.saldo_in_all)
                            $('#loan_id').val(data.loan_id)

                            return {
                                results: [
                                    {
                                        "id" : data.loan_id,
                                        "text" : data.client_name
                                    }
                                ]
                            }; 
                            
                        },
                        cache: true
                    }
                });
                
            })
            // 305761

            $('#createForm').on('submit', function(e){

                e.preventDefault();

                var $this = $(this);

                $.ajax({
                    url: '/madmin/app-list',
                    method: 'POST',
                    data: $this.serialize(),
                    beforeSend: function(){
                        $("#loading").show()
                    },
                }).done(function(response){

                    $('#createModal').modal('toggle');
                    $("#loading").hide()
                    $('#successMessage').text(response);
                    $('#successModal').modal('toggle');
                    $('#successModal').on('hidden.bs.modal', function () {
                        location.reload();
                    })

                    setTimeout(function() {
                        location.reload();
                    }, 2500);

                }).error(function(err){
                    console.log(err)
                })

            })

            $('.deleteButton').on('click',function () {
                let id = $(this).data('id')

                $('#yesDelete').on('click',function () {
                    $.ajax({
                    url: '/madmin/app-list/'+id,
                    method: 'DELETE',
                    data: {_token: CSRF_TOKEN},
                    beforeSend: function(){
                        $("#loading").show()
                    },
                    }).done(function(response){

                        $('#ConfirmModal').modal('toggle');
                        $("#loading").hide()
                        $('#successMessage').text(response);
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hidden.bs.modal', function () {
                            location.reload();
                        })

                        setTimeout(function() {
                            location.reload();
                        }, 2500);



                    }).error(function(err){
                        console.log(err)
                    })
                })
                console.log(id)
            })

        </script>
    </section>
    <!-- /.content -->
@endsection
