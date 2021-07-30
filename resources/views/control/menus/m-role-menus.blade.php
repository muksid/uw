@extends('layouts.uw.dashboard')
<link rel="stylesheet" href="/admin-lte/plugins/select2/select2.min.css">

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Role Menus
            <?php
            $start = strtotime('12:01:00');
            $end = strtotime('13:16:00');
            $mins = ($end - $start) / 3600;
            echo $mins;
            //output 9 hours
            ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i>@lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.administrator')</a></li>
            <li class="active">Role Menus</li>
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

        @if(session('errors'))
            <div class="box box-default">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <h4 class="modal-title"> {{ session('errors') }}</h4>
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
                <div class="box box-primary">
                    <div class="box-header with-border">

                        <form action="{{ route('admin-role_menus-search') }}" method="POST" role="search">
                            {{ csrf_field() }}
                            <div class="col-md-1">
                                <button type="button" class="btn btn-flat btn-primary" id="createMenu" data-toggle="modal" data-target="#createModal">
                                    <i class="fa fa-plus"></i> @lang('blade.add')
                                </button>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <input type="text" class="form-control " name="input" value="{{ $input??''}}" placeholder="search ...">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="button" class="btn btn-default btn-flat" onclick="location.href='/admin/role_menus-search';">
                                        <i class="fa fa-refresh"></i> @lang('blade.reset')
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-flat">
                                        <i class="fa fa-search"></i> @lang('blade.search')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Space for filter -->


                    <div id="loading" class="loading-gif" style="display: none"></div>

                    <!-- Space for filter END -->
                    <div class="box-body mailbox-messages" id="search_table">
                        <b id="search_total">@lang('blade.overall'){{': '. $models->count()}} @lang('blade.group_edit_count').</b>
                        <table id="example1" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><i class="fa fa-anchor" aria-hidden="true"></i> User role</th>
                                    <th><i class="fa fa-text-height" aria-hidden="true"></i> Menu </th>
                                    <th><i class="fa fa-text-height" aria-hidden="true"></i> Type</th>
                                    <th><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Sort</th>
                                    <th><i class="fa fa-text-height" aria-hidden="true"></i> Parent</th>
                                    <th><i class="fa fa-star-half-o" aria-hidden="true"></i> Status</th>
                                    <th><i class="fa fa-clock-o" aria-hidden="true"></i> Created</th>
                                    <th class="text-center">
                                        <a href="#" type="button" class="btn btn-outline-danger checkbox-toggle">
                                            <i class="fa fa-trash-o text-red fa-square-o"></i>
                                        </a>

                                        <div class="pull-left">
                                            <button type="button" class="btn btn-danger btn-flat deleteMessage" data-url="">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </div>
                                    </th>
                                    <th><i class="fa fa-edit"></i>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($models->count())
                                @foreach ($models as $key => $model)
                                    <tr id="tr_{{$model->id}}">
                                        <td class="text-bold">{{ $models->firstItem() + $key }}.</td>
                                        <td>{{ $model->userRole->title??'' }}          </td>
                                        <td>{{ $model->menuRole->title??'' }}         </td>
                                        <td>
                                            @if($model->menu_type == 'M')
                                                <span class="text-primary text-bold">Menu</span>
                                            @else
                                                <span class="text-orange text-bold">Category</span>
                                            @endif
                                        </td>
                                        <td><span class="">{{ $model->sort }}</span></td>
                                        <td>
                                            @if($model->parent_id != 0)
                                                {{ $model->parentMenuRole->title??"Not Found" }}
                                            @else
                                                <span class='text-red text-bold'>-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($model->isActive === 'A')
                                                <i class="fa fa-check text-green" aria-hidden="true"></i>
                                            @else
                                                <i class="fa fa-ban text-red" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d M, Y H:i') }}
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="checkbox checkbox-checked" data-id="{{$model->id}}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary editButton" data-toggle="modal" data-target="#editModal" data-id="{{ $model->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td class="text-red text-center"><i class="fa fa-search"></i>
                                    <b>@lang('blade.not_found')</b></td>
                            @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th><i class="fa fa-anchor" aria-hidden="true"></i> @lang('blade.role')</th>
                                    <th><i class="fa fa-text-height" aria-hidden="true"></i> Menu </th>
                                    <th><i class="fa fa-text-height" aria-hidden="true"></i> Type</th>
                                    <th><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Sort</th>
                                    <th><i class="fa fa-text-height" aria-hidden="true"></i> Parent</th>
                                    <th><i class="fa fa-star-half-o" aria-hidden="true"></i> Status</th>
                                    <th><i class="fa fa-clock-o" aria-hidden="true"></i> Created</th>
                                    <th class="text-center">
                                        <a href="#" type="button" class="btn btn-outline-danger checkbox-toggle">
                                            <i class="fa fa-trash-o text-red fa-square-o"></i>
                                        </a>

                                        <div class="pull-left">
                                            <button type="button" class="btn btn-danger btn-flat deleteMessage" data-url="">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </div>
                                    </th>
                                    <th><i class="fa fa-edit"></i>Edit</th>
                                </tr>
                            </tfoot>
                        </table>
                        <span>{{ $models->links() }}</span>
                    </div>
                </div>
            </div>
        </div>


    </section>

    <!-- Add Modal -->
    <div class="modal fade" id="createModal" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createForm" action="">
                        @csrf
                        <table id="" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <td class="text-center" colspan="2"><h3 class="modal-title" id="createModalLabel">Create menu role</h3></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><label for="create-role_id">User role: <span class="text-red">*</span> </label> </td>
                                    <td>
                                        <select id="create-role_id" class="form-control select2 create-model-input-role_id" name="role_id" style="width: 100%" required>
                                            <option value=""> Select user role </option>
                                            @foreach($roles as $value)
                                                <option value="{{ $value->id }}"> {{ $value->title }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="create-menu_id">Menu: <span class="text-red">*</span> </label></td>
                                    <td>
                                        <select id="create-menu_id" class="form-control select2 create-model-input-menu_id" name="menu_id" style="width: 100%" required>
                                            <option value=""> Select menu </option>

                                            @foreach($menu_roles as $value)
                                                <option value="{{ $value->id }}"> {{ $value->title }} - ({{ $value->coreMenu->title??'-' }}) {{ $value->url_path }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="create-menu_type">Menu Type: <span class="text-red">*</span> </label></td>
                                    <td>
                                        <label class="form-check-label" for="createMenuType1">Menu</label>
                                        <input class="form-check-input" type="radio" name="menu_type" id="createMenuType1" value="M" checked>
                                        <label class="form-check-label" for="createMenuType2">Category</label>
                                        <input class="form-check-input" type="radio" name="menu_type" id="createMenuType2" value="C">
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="create-sort">Sort: <span class="text-red">*</span></label></td>
                                    <td><input type="number" name="sort"    id="create-sort" class="form-control create-model-input-sort" min="0" max="10000" value="0"></td>
                                </tr>
                                <tr>
                                    <td><label for="create-parent_id">Parent: <span class="text-red">*</span></label></td>
                                    <td>
                                        <select id="create-parent_id" class="form-control select2 create-model-input-parent_id" name="parent_id" style="width: 100%" required>
                                            <option value="0"> No parent </option>

                                        </select>
                                    </td>
                                </tr>
                                <tr class="text-center">
                                    <td colspan="2">
                                        <label class="form-check-label" for="createIsActive1">Active</label>
                                        <input class="form-check-input" type="radio" name="isActive" id="createIsActive1" value="A" checked>
                                        <label class="form-check-label" for="createIsActive2">Passive</label>
                                        <input class="form-check-input" type="radio" name="isActive" id="createIsActive2" value="P">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="">
                        @csrf
                        <input type="text" name="id"    id="edit-menu_id" hidden>

                        <table id="" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <td class="text-center" colspan="2"><h3 class="modal-title" id="editModalLabel">Editing</h3></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><label for="edit-role_id">User role: <span class="text-red">*</span> </label> </td>
                                    <td>
                                        <select id="edit-role_id" class="form-control select2 edit-model-input-role_id" name="role_id" style="width: 100%" required>
                                            @foreach($roles as $value)
                                                <option value="{{ $value->id }}"> {{ $value->title }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="edit-menu_id">Menu: <span class="text-red">*</span> </label></td>
                                    <td>
                                        <select id="edit-menu_id" class="form-control select2 edit-model-input-menu_id" name="menu_id" style="width: 100%" required>
                                            @foreach($menu_roles as $value)
                                                <option value="{{ $value->id }}"> {{ $value->title }} - ({{ $value->coreMenu->title??'-' }}) {{ $value->url_path }} </option>
                                            @endforeach
                                        </select>
                                    </td>

                                </tr>
                                <tr>
                                    <td><label for="edit-menu_type">Menu Type: <span class="text-red">*</span> </label></td>
                                    <td>
                                        <label class="form-check-label" for="editMenuType1">Menu</label>
                                        <input class="form-check-input" type="radio" name="menu_type" id="editMenuType1" value="M">
                                        <label class="form-check-label" for="editMenuType2">Category</label>
                                        <input class="form-check-input" type="radio" name="menu_type" id="editMenuType2" value="C">
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="edit-sort">Sort: <span class="text-red">*</span></label></td>
                                    <td><input type="number" name="sort"    id="edit-sort" class="form-control edit-model-input-sort" min="0" max="10000" value="0"></td>
                                </tr>
                                <tr>
                                    <td><label for="edit-parent_id">Parent: <span class="text-red">*</span></label></td>
                                    <td>
                                        <select id="edit-parent_id" class="form-control select2 edit-model-input-parent_id" name="parent_id" style="width: 100%" required>
                                            <option value="0"> No parent </option>
                                        </select>
                                    </td>
                                </tr>

                                <tr class="text-center">
                                    <td colspan="2">
                                        <label class="form-check-label" for="editIsActive1">Active</label>
                                        <input class="form-check-input" type="radio" name="isActive" id="editIsActive1" value="A">
                                        <label class="form-check-label" for="editIsActive2">Passive</label>
                                        <input class="form-check-input" type="radio" name="isActive" id="editIsActive2" value="P">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="ConfirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">

            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                </div>

                <div class="modal-body">
                    <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span>
                        Siz, tanlagan xatlar o`chiriladi!
                    </h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('blade.cancel')</button>
                    <button type="button" class="btn btn-danger" id="yesDelete">Ha, O`chirish</button>
                </div>
            </div>
        </div>
    </div>

    <!--Response Modal -->
    <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="responseModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-footer">
                    <button id="responseCloseButton" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $(function () {

            $('select').select2();


            $('#edit-role_id').on("select2:select", function (e) {
                $('#edit-parent_id option[value!="0"]').remove()

                let selectedValue = $("#edit-role_id").select2("val")
                $.ajax({
                    url: '/admin/role_menus/user_role/'+selectedValue,
                    type: 'GET',
                    data: {_token: CSRF_TOKEN},
                    dataType: 'json',
                    success: function(res){
                        $.each(res.model, function(index, value){

                            let newOption = new Option(value.title, value.menu_id, false, false)
                            $('#edit-parent_id').append(newOption).trigger('change')
                        })
                    },
                    error: function(e) {
                        console.log(e)
                    }
                })
            })

            $('#create-role_id').on("select2:select", function (e) {
                let selectedValue = $("#create-role_id").select2("val")

                $('#create-parent_id option[value!="0"]').remove()

                $.ajax({
                    url: '/admin/role_menus/user_role/'+selectedValue,
                    type: 'GET',
                    data: {_token: CSRF_TOKEN},
                    dataType: 'json',
                    success: function(res){
                        console.log(res)
                        $.each(res.model, function(index, value){

                            let newOption = new Option(value.title, value.menu_id, false, false)
                            $('#create-parent_id').append(newOption).trigger('change')
                        })
                    },
                    error: function(e) {
                        console.log(e)
                    }
                })
            })


            $('.mailbox-messages input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });

            $(".deleteMessage").hide();

            if ($('input:checkbox:checked').length > 0){
                alert($('input:checkbox:checked').length)
            }

            $(".checkbox-toggle").click(function () {

                var clicks = $(this).data('clicks');

                if (clicks) {
                    $(".deleteMessage").hide(200);
                    //Uncheck all checkboxes
                    $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                    $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');

                } else {
                    //if ($('input:checkbox:checked').length !== 0){
                        $(".deleteMessage").show(300);
                    //}
                    //Check all checkboxes
                    $(".mailbox-messages input[type='checkbox']").iCheck("check");
                    $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                }
                $(this).data("clicks", !clicks);
            });

            $(".iCheck-helper").click(function() {
                var numberNotChecked = $('input:checkbox:checked').length;
                if(numberNotChecked > 0) {
                    $(".deleteMessage").show(300);
                } else {
                    $(".deleteMessage").hide(200);
                }
            });

        });


        $('#createForm').on('submit', function(e){

            e.preventDefault();

            var $this = $(this);


            $.ajax({
                url: '/admin/role_menus',
                method: 'POST',
                data: $this.serialize(),
                beforeSend: function(){
                    $("#loading").show()
                },
            }).done(function(response){

                $('#createModal').modal('toggle');
                $("#loading").hide()
                $('#responseModalLabel').html(response.message);

                $('#responseModal').modal('toggle');

                $('#responseModal').on('hidden.bs.modal', function () {
                    location.reload();
                })

                setTimeout(function() {
                    location.reload();
                }, 2500);

            }).error(function(err){
                console.log(err)
            })

        })

        $('.editButton').unbind().on('click', function(){
            let id = $(this).data('id')

            $('#edit-parent_id option[value!="0"]').remove()

            $.ajax({
                url: '/admin/role_menus/'+id+'/edit',
                type: 'GET',
                data: {_token: CSRF_TOKEN},
                dataType: 'json',
                beforeSend: function(){
                    $("#loading").show()
                    $( "#editMenuType1" ).prop( "checked", false )
                    $( "#editMenuType2" ).prop( "checked", false )
                    $( "#editIsActive1" ).prop( "checked", false )
                    $( "#editIsActive2" ).prop( "checked", false )

                },
                success: function(res){

                    $('#edit-menu_id').val(res.model.id)
                    $('.edit-model-input-role_id').val(res.model.role_id).trigger('change')
                    $('.edit-model-input-menu_id').val(res.model.menu_id).trigger('change')
                    $('.edit-model-input-sort').val(res.model.sort)

                    $.each(res.parent, function(index, value){

                        let newOption = new Option(value.title, value.menu_id, false, false)
                        $('#edit-parent_id').append(newOption).trigger('change')

                    })
                    $('#edit-parent_id').val(res.model.parent_id).trigger('change')

                    // $('.edit-model-input-parent_id').val(res.model.parent_id);
                    // $('.edit-model-input-parent_id').trigger('change');


                    if(res.model.menu_type == 'M'){
                        $( "#editMenuType1" ).prop( "checked", true )
                    }
                    else{
                        $( "#editMenuType2" ).prop( "checked", true )
                    }

                    if(res.model.isActive == 'A'){
                        $( "#editIsActive1" ).prop( "checked", true )
                    }
                    else{
                        $( "#editIsActive2" ).prop( "checked", true )
                    }
                },
                complete:function(res){
                    $("#loading").hide()
                }

            })
        })

        $('#editForm').on('submit', function(e){
            e.preventDefault();

            var $this = $(this);
            let menu = $('#edit-menu_id').val()


            $.ajax({
                url: '/admin/role_menus/'+menu,
                method: 'PATCH',
                data: $this.serialize(),
                beforeSend: function(){
                    $("#loading").show()
                },
            }).done(function(res){
                console.log(res)
                $('#editModal').modal('toggle');
                $("#loading").hide()

                $this = $('#tr_' + res.model.id).find("td")

                $this.eq(1).html(res.user_role.title)
                $this.eq(2).html(res.menu.title)
                if(res.model.menu_type == 'M'){
                    $this.eq(3).html("<span class='text-primary text-bold'>Menu</span>")
                }else{
                    $this.eq(3).html("<span class='text-orange text-bold'>Category</span>")
                }
                $this.eq(4).html(res.model.sort)

                $this.eq(5).html(res.parent)

                if(res.model.isActive == 'A'){
                    $this.eq(6).html("<i class='fa fa-check text-green' aria-hidden='true'></i>")
                }else{
                    $this.eq(6).html("<i class='fa fa-ban text-red' aria-hidden='true'></i>")
                }

                $('#responseModalLabel').html('Successfully updated!')

                $('#responseModal').modal('show')

                setTimeout(function() {
                    $('#responseModal').modal('hide')
                }, 2000)

            }).error(function(err){
                console.log(err)
            });
        });



        $(".deleteMessage").click(function () {

            $('#ConfirmModal').modal('toggle')
        })

        $('#yesDelete').on('click', function(e) {

            let idsArr = [];

            $(".checkbox:checked").each(function() {

                idsArr.push($(this).attr('data-id'));

            });
            let strIds = idsArr.join(",");


            $.ajax({

                url: '/admin/role_menus/'+idsArr,
                type: 'DELETE',
                data: {_token: CSRF_TOKEN},
                dataType: 'JSON',
                beforeSend: function(){
                    $("#loading").show();
                },
                success: function(res){

                    $('#ConfirmModal').modal('toggle')
                    $('#responseModalLabel').text(res)
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

        });



    </script>

    <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
    <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

@endsection
