@extends('layouts.dashboard')

@section('content')
    <section class="content-header">
        <h1>
            Filial departament va bo`linmalari
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">filial</a></li>
            <li class="active">view</li>
        </ol>

    </section>

    <div class="container">

        <div class="box box-success">
            <div class="box-header with-border">
                <a href="{{route('filial.index')}}" class="btn btn-xs btn-primary margin">
                    <i class="fa fa-bank"></i> Filials
                </a>
                <h3 class="box-title margin"><span class="badge badge-secondary">{{ $filial->filial_code }}</span> {{ $filial->title }}
                    <a href="{{ url('/madmin/update-role-department', $filial->filial_code) }}"
                       class="btn btn-sm btn-success"><i class="fa fa-refresh"></i> @lang('blade.refresh')</a>
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default" style="overflow-y: scroll; max-height: 600px">

                            <h3 class="margin">{{ $filial->title }}</h3>

                            <ul>
                                @foreach ($models as $parent)

                                    <li>
                                        {{ $parent->getName->getReplace($parent->getName->title??'-')??'-' }}

                                        @if (count($parent->children))

                                            @include('madmin.departments.departChildOra',['children' => $parent->children, 'filial_code' => $filial->filial_code])

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
