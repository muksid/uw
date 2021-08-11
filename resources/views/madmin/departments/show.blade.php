@extends('layouts.dashboard')

@section('content')
    <section class="content-header">
        <h1>
            Departamentlar
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">departments</a></li>
            <li class="active">view</li>
        </ol>

    </section>

    <div class="container">

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title margin">{{ $department->title }} </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default" style="overflow-y: scroll; max-height: 600px">

                                <h3 class="margin">{{ $department->title }}</h3>

                                <ul id="tree1">

                                    @foreach($department->childs as $department)

                                        <li>

                                            {{ $department->title }}

                                            @if(count($department->childs))

                                                @include('madmin.departments.departChild',['childs' => $department->childs])

                                            @endif

                                        </li>

                                    @endforeach

                                </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
