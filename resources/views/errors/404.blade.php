@extends('layouts.error')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <ol class="breadcrumb">
                <li><a href="/home"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
                <li><a href="#">Xato</a></li>
                <li class="active">404 xato</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-yellow"> 404</h2>

                <div class="error-content">
                    <h3><i class="fa fa-warning text-yellow"></i> Sahifa topilmadi!</h3>

                    <p>
                        Siz qidirayotgan sahifani topa olmadik. <a href="\home" class="form-group">Bosh sahifa</a>
                    </p>
                </div>
                <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection