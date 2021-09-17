@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Jismoniy shaxlar
            <small>personal info</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">physical</a></li>
            <li class="active">create</li>
        </ol>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Xatolik!</strong> xatolik bor.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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

    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title text-maroon"><i class="fa fa-camera-retro"></i> Mijoz fotosuratini olish bo`yicha talablar</h3>
                    </div>
                <div class="box-body">
                    <img class="img-responsive pad pull-left" src="../../../images/myid_photo2.png" alt="Photo">
                </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-solid" style="background-color: #03a9f4">
                    <div class="box-header with-border">
                        <h3 class="box-title">Client face control</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body text-center">
                        <form id="imageUploadForm" action="javascript:void(0)" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="job_id" id="job_id" hidden/>

                            <div class="row pakainfo">

                                <div class="col-md-12 pakainfo">
                                    <div id="live_camera"></div>
                                    <button type="button" class="btn btn-app cam-btn" onClick="capture_web_snapshot()"><i class="fa fa-camera text-primary"></i></button>

                                    <input type="hidden" name="image" class="image-tag">
                                </div>

                                <div class="col-md-12">

                                    <div id="preview" class="bg-primary border-success bg-demo"></div>
                                    <div id="loading-gif" class="loading-gif" style="display: none"></div>

                                    <div class="row form-inputs">
                                        <div class="col-md-4 col-md-offset-4">
                                            <div class="box-body">
                                                <div class="form-group has-error">
                                                    <label style="color: #fff">Passport: AA1112233 <span class="text-red">*</span></label>
                                                    <input type="text" name="pass_data" id="pass_data" class="form-control" maxlength="9" placeholder="AB4259865" required>
                                                </div>
                                                <div class="form-group has-error">
                                                    <label style="color: #fff">Tug`ilgan yili: (22.01.1991) </label><sup class="text-red">*</sup>
                                                    <input type="date" name="birth_date"
                                                           class="form-control" placeholder="21.05.1991" required/>

                                                </div>
                                            </div>
                                            <div class="text-center margin button-save">
                                                <button type="submit" class="btn btn-success btn-flat pakainfo"><i class="fa fa-search"></i> Search Client</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="modal modal-danger" id="messageModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Mijoz Passport ma`lumotlari</h4>
                            </div>
                            <div class="modal-body">
                                <p id="messageText"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

            </div>
            <div class="col-md-2">1</div>
        </div>
    </section>

    <section class="content">

        <script>
            Webcam.set({
                width: 420,
                height: 340,
                image_format: 'jpeg',
                jpeg_quality: 90
            });

            $('.form-inputs').hide();
            $('#preview').hide();

            Webcam.attach( '#live_camera' );

            function capture_web_snapshot() {
                Webcam.snap( function(site_url) {
                    $('.form-inputs').show();
                    $('#preview').show();
                    $(".image-tag").val(site_url);
                    document.getElementById('preview').innerHTML = '<img src="'+site_url+'"/>';
                } );
            }
            $('#pass_data').keyup(function() {
                this.value = this.value.toUpperCase();
            });

            $(document).ready(function (e) {

                $('#imageUploadForm').on('submit',(function(e) {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    e.preventDefault();

                    let formData = new FormData(this);

                    $(".image-tag").empty();

                    $.ajax({

                        type:'POST',
                        url: "{{ url('/phy/client/create/personal')}}",
                        data:formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        beforeSend: function(){
                            $('#messageText').empty();
                            $("#loading-gif").show();
                        },
                        success:function(data){

                            console.log(data);
                            $('#messageText').html('Code: '+data.code+' ('+data.result_code+'), '+data.data);
                            if (data.code === 'myid'){

                                window.location.href = "/phy/client/form/"+data.id;

                            } else if (data.code === 'model') {
                                $('#messageText').html('(ip: 247) Mijoz tizimda mavjud MFO: ('+data.message+')');
                                $('#messageModal').modal('show');
                            }
                            $('#messageModal').modal('show');

                        },
                        complete:function(){
                            $("#loading-gif").hide();
                        },
                        error: function(data){
                            console.log(data);
                        }

                    });

                }));

            });

        </script>
    </section>
@endsection
