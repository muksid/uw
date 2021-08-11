@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Ta`minot turlari
            <small>@lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">Ta`minot turlari</a></li>
            <li class="active">create</li>
        </ol>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('blade.error')</strong> @lang('blade.exist').<br><br>
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
            <form method="POST" action="{{ route('guar-type.store')}}">
                @csrf
                <div class="col-md-6 col-md-offset-2">
                    <div class="box box-primary">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('title_ru') ? 'has-error' : '' }}">
                                        <label>Title Ru</label>
                                        <input type="text" name="title_ru" class="form-control" value="{{ old('title_ru') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                                        <label>Code</label>
                                        <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('isActive') ? 'has-error' : '' }}">
                                        <label>Status</label><sup class="text-red"> *</sup>
                                        <select class="form-control select2" name="isActive">
                                            <option value="1">Active</option>
                                            <option value="0">Passive</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="box-footer">
                            <div class="pull-right">
                                <a href="{{ route('guar-type.index') }}" class="btn btn-default"><i class="fa fa-ban"></i> Bekor
                                    qilish
                                </a>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                    @lang('blade.save')
                                </button>
                            </div>
                        </div>

                    </div>
                    <!-- /. box -->
                </div>

            </form>
        </div>
    </section>
    <!-- /.content -->

@endsection
