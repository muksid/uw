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
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-body table-responsive no-padding">

                        <form method="POST" action="do_upload.php">
                            <div class="row pakainfo">
                                <div class="col-md-6 pakainfo">
                                    <div id="live_camera"></div>
                                    <hr/>
                                    <input type=button value="Take Snapshot" onClick="capture_web_snapshot()">
                                    <input type="hidden" name="image" class="image-tag">
                                </div>
                                <div class="col-md-6">
                                    <div id="preview">Your captured image will appear here...</div>
                                </div>
                                <div class="col-md-12 text-center pakainfo">
                                    <br/>
                                    <button class="btn btn-primary pakainfo">Submit</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <script>
            Webcam.set({
                width: 490,
                height: 390,
                image_format: 'jpeg',
                jpeg_quality: 90
            });

            Webcam.attach( '#live_camera' );

            function capture_web_snapshot() {
                Webcam.snap( function(site_url) {
                    $(".image-tag").val(site_url);
                    document.getElementById('preview').innerHTML = '<img src="'+site_url+'"/>';
                } );
            }
        </script>
    </section>
@endsection
