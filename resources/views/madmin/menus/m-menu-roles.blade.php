@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Menu Roles
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">menu-roles</a></li>
            <li class="active">index</li>
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
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                        <form action="{{ url('/madmin/menu-roles-search') }}" method="POST" role="search">
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
                                    <button type="button" class="btn btn-default btn-flat" onclick="location.href='/madmin/menu-roles-search';">
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
                                    <th><i class="fa fa-text-height" aria-hidden="true"></i>    Title</th>
                                    <th><i class="fa fa-link" aria-hidden="true"></i>           URL path</th>
                                    <th><i class="fa fa-language" aria-hidden="true"></i>       Lang code</th>
                                    <th><i class="fa fa-fonticons" aria-hidden="true"></i>      Icon class</th>
                                    <th><i class="fa fa-font" aria-hidden="true"></i>           Text class</th>
                                    <th><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i> Count</th>
                                    <th><i class="fa fa-star-half-o" aria-hidden="true"></i>    Status</th>
                                    <th><i class="fa fa-bars" aria-hidden="true"></i>           Core menu</th>
                                    <th><i class="fa fa-clock-o" aria-hidden="true"></i>        Created</th>
                                    <th>
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
                                        <td>{{ $model->title }}             </td>
                                        <td>{{ $model->url_path }}          </td>
                                        <td>{{ $model->lang_code }}         </td>
                                        <td>
                                            @if($model->icon_code)
                                                <span class="">{{ $model->icon_code }}</span>
                                            @else
                                                <span class="text-red text-bold">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($model->text_class)
                                                <span class="">{{ $model->text_class }}</span>
                                            @else
                                                <span class="text-red text-bold">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-bold">
                                            @if($model->count)
                                                <span class="text-green">Yes</span>
                                            @else
                                                <span class="text-red">-</span>
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
                                            @if($model->core_menu_id == 0)
                                                <span class='text-red text-bold'>-</span>
                                            @else
                                                {{ $model->coreMenu->title??"Not Found" }}
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
                                    <th><i class="fa fa-text-height" aria-hidden="true"></i>    Title</th>
                                    <th><i class="fa fa-link" aria-hidden="true"></i>           URL path</th>
                                    <th><i class="fa fa-language" aria-hidden="true"></i>       Lang code</th>
                                    <th><i class="fa fa-fonticons" aria-hidden="true"></i>      Icon class</th>
                                    <th><i class="fa fa-font" aria-hidden="true"></i>           Text class</th>
                                    <th><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i> Count</th>
                                    <th><i class="fa fa-star-half-o" aria-hidden="true"></i>    Status</th>
                                    <th><i class="fa fa-bars" aria-hidden="true"></i>           Core menu</th>
                                    <th><i class="fa fa-clock-o" aria-hidden="true"></i>        Created</th>
                                    <th>
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
                                    <td><label for="create-title">Title: <span class="text-red">*</span> </label> </td>
                                    <td><input type="text" name="title"        id="create-title" class="form-control create-model-input-title" required></td>
                                </tr>
                                <tr>
                                    <td><label for="create-lang_code">URL path: <span class="text-red">*</span> </label></td>
                                    <td><input type="text" name="url_path"    id="create-url_path" class="form-control create-model-input-url_path" required></td>
                                </tr>
                                <tr>
                                    <td><label for="create-lang_code">Lang code: <span class="text-red">*</span> </label></td>
                                    <td><input type="text" name="lang_code"    id="create-lang_code" class="form-control create-model-input-lang_code" required></td>
                                </tr>
                                <tr>
                                    <td><label for="create-icon_code"> Icon class: </label></td>
                                    <td><input type="text" name="icon_code"    id="create-icon_code" class="form-control create-model-input-icon_code"></td>
                                </tr>
                                <tr>
                                    <td><label for="create-text_class">Text class: </label></td>
                                    <td><input type="text" name="text_class"   id="create-text_class" class="form-control create-model-input-text_class"></td>
                                </tr>
                                <tr>
                                    <td><label for="create-core_menu">Core menu: <span class="text-red">*</span></label></td>
                                    <td>
                                        <select id="create-core_menu" class="form-control select2 create-model-input-core_menu" name="core_menu_id" style="width: 100%" required>
                                            <option value="0"> Empty </option>
                                            @foreach($core_menus as $value)
                                                <option value="{{ $value->id }}"> {{ $value->title }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="create-count">Count: <span class="text-red">*</span></label></td>
                                    <td>
                                        <label class="form-check-label" for="create-count1">Yes</label>
                                        <input class="form-check-input" type="radio" name="count" id="create-count1" value="1" checked>
                                        <label class="form-check-label" for="create-count2">No</label>
                                        <input class="form-check-input" type="radio" name="count" id="create-count2" value="0">
                                </td>
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
                                    <td><label for="edit-title">Title: <span class="text-red">*</span> </label> </td>
                                    <td><input type="text" name="title"        id="edit-title" class="form-control edit-model-input-title" required></td>
                                </tr>
                                <tr>
                                    <td><label for="edit-lang_code">URL path: <span class="text-red">*</span> </label></td>
                                    <td><input type="text" name="url_path"    id="edit-url_path" class="form-control edit-model-input-url_path" required></td>
                                </tr>
                                <tr>
                                    <td><label for="edit-lang_code">Lang code: <span class="text-red">*</span> </label></td>
                                    <td><input type="text" name="lang_code"    id="edit-lang_code" class="form-control edit-model-input-lang_code" required></td>
                                </tr>
                                <tr>
                                    <td><label for="edit-icon_code">Icon class: </label></td>
                                    <td><input type="text" name="icon_code"    id="edit-icon_code" class="form-control edit-model-input-icon_code"></td>
                                </tr>
                                <tr>
                                    <td><label for="edit-text_class">Text class: </label></td>
                                    <td><input type="text" name="text_class"   id="edit-text_class" class="form-control edit-model-input-text_class"></td>
                                </tr>
                                <tr>
                                    <td><label for="edit-core_menu">Core menu: <span class="text-red">*</span></label></td>
                                    <td>
                                        <select class="form-control select2 edit-model-input-core_menu" id="edit-core_menu" name="core_menu_id" style="width:100%" required>
                                            <option value="0"> Empty </option>

                                            @foreach($core_menus as $value)
                                                <option value="{{ $value->id }}"> {{ $value->title }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="edit-count">Count: <span class="text-red">*</span></label></td>
                                    <td>
                                        <label class="form-check-label" for="edit-count1">Yes</label>
                                        <input class="form-check-input" type="radio" name="count" id="edit-count1" value="1">
                                        <label class="form-check-label" for="edit-count2">No</label>
                                        <input class="form-check-input" type="radio" name="count" id="edit-count2" value="0">
                                        <!-- <input type="number" name="count"   id="edit-count" class="form-control edit-model-input-count" min="0" max="100000" required> -->
                                </td>
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

        $("#responseCloseButton").click(function(){
            $(this).data('clicked', true);
        })

        $('#createForm').on('submit', function(e){

            e.preventDefault();

            var $this = $(this);


            $.ajax({
                url: '/madmin/menu-roles',
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

            $.ajax({
                url: '/madmin/menu-roles/'+id+'/edit',
                type: 'GET',
                data: {_token: CSRF_TOKEN},
                dataType: 'json',
                beforeSend: function(){
                    $("#loading").show()
                    $( "#edit-count1" ).prop( "checked", false )
                    $( "#edit-count2" ).prop( "checked", false )
                    $( "#editIsActive1" ).prop( "checked", false )
                    $( "#editIsActive2" ).prop( "checked", false )

                },
                success: function(res){

                    $('#edit-menu_id').val(res.id)
                    $('.edit-model-input-title').val(res.title)
                    $('.edit-model-input-url_path').val(res.url_path)
                    $('.edit-model-input-lang_code').val(res.lang_code)
                    $('.edit-model-input-icon_code').val(res.icon_code)
                    $('.edit-model-input-text_class').val(res.text_class)
                    $('.edit-model-input-core_menu').val(res.core_menu_id).trigger('change');

                    if(res.count == 1){
                        $( "#edit-count1" ).prop( "checked", true )
                    }
                    else{
                        $( "#edit-count2" ).prop( "checked", true )
                    }

                    if(res.isActive == 'A'){
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
                url: '/madmin/menu-roles/'+menu,
                method: 'PATCH',
                data: $this.serialize(),
                beforeSend: function(){
                    $("#loading").show()
                },
            }).done(function(res){

                $('#editModal').modal('toggle');
                $("#loading").hide()

                $this = $('#tr_' + res.model.id).find("td")

                $this.eq(1).html(res.model.title)
                $this.eq(2).html(res.model.url_path)
                $this.eq(3).html(res.model.lang_code)
                if(res.model.icon_code){
                    $this.eq(4).html(res.model.icon_code)
                }else{
                    $this.eq(4).html('<span class="text-red text-bold">-</span>')
                }
                if(res.model.text_class){
                    $this.eq(5).html(res.model.text_class)
                }else{
                    $this.eq(5).html('<span class="text-red text-bold">-</span>')
                }
                if(res.model.count == 1){
                    $this.eq(6).html('<span class="text-green">Yes</span>')
                }else{
                    $this.eq(6).html('<span class="text-red">-</span>')
                }
                if(res.model.isActive == 'A'){
                    $this.eq(7).html("<i class='fa fa-check text-green' aria-hidden='true'></i>")
                }else{
                    $this.eq(7).html("<i class='fa fa-ban text-red' aria-hidden='true'></i>")
                }
                $this.eq(8).html(res.core_menu_title)

                $('#responseModalLabel').html('Successfully updated!')

                $('#responseModal').modal('show')

                setTimeout(function() {
                    $('#responseModal').modal('hide')
                }, 1500)

            }).error(function(err){
                console.log(err)
            });
        });

        $(function () {

            $('select').select2();

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

                url: '/madmin/menu-roles/'+idsArr,
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

@endsection
