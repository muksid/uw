<?php ?>
@extends('layouts.uw.dashboard')

@section('content')
    <section class="content-header">
        <h1>
            Edit department
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">Department</a></li>
            <li class="active">Show department</li>
        </ol>

    </section>

    <div class="container">

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $department->title }} </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default" style="overflow-y: scroll; max-height: 800px">

                                <h3>{{ $department->title }}</h3>

                                <ul id="tree1">

                                    @foreach($department->childs as $department)

                                        <li>

                                            {{ $department->title }}

                                            @if(count($department->childs))

                                                @include('departments.departChild',['childs' => $department->childs])

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
