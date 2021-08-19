@extends('layouts.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            @lang('blade.edit_user')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.sidebar_users')</a></li>
            <li class="active">edit</li>
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

        <div id="loading" class="loading-gif" style="display: none"></div>

        <div class="row">
            <div class="col-md-5">
                <div class="box box-success">
                    <h3 class="margin">Username</h3>
                    <form method="POST" action="{{ url('/madmin/user/username-update',$user->id) }}" role="form">
                        @csrf
                        <div class="row">
                            <div class="col-md-7">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" class="form-control" value="{{ $user->username??'-' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('cb_id') ? 'has-error' : '' }}">
                                        <label for="username">CB ID</label>
                                        <input type="text" class="form-control" value="{{ $user->cb_id??'-' }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Enter password">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box-body">
                                    <div class="form-group {{ $errors->has('passwordConf') ? 'has-error' : '' }}">
                                        <label for="passwordConf">Password Conf</label>
                                        <input type="password" name="passwordConf" class="form-control" id="passwordConf" placeholder="Enter password conf">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-3 text-center">
                                                <br>
                                                <label for="" class="text-bold">Is Active?</label>
                                            </div>

                                            <div class="col-sm-3 text-center">
                                                <br>
                                                <div class="form-group">
                                                    <input class="flat-red" type="radio" name="isActive" value="A" {{ ($user->isActive == 'A') ? 'checked':'' }}>
                                                    <label class="text-success" for="A">
                                                        Active
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 text-center">
                                                <br>
                                                <div class="form-group">
                                                    <input class="flat-red" type="radio" name="isActive" value="P" {{ ($user->isActive == 'P') ? 'checked':'' }}>
                                                    <label class="text-yellow" for="P">
                                                        Passive
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 text-center">
                                                <br>
                                                <div class="form-group">
                                                    <input class="flat-red" type="radio" name="isActive" value="D" {{ ($user->isActive == 'D') ? 'checked':'' }}>
                                                    <label class="text-red" for="D">
                                                        Deleted
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-pencil"></i> @lang('blade.update')</button>
                        </div>

                    </form>

                </div>

            </div>

            <div class="col-md-7">
                <div class="box box-primary">
                    <h3 class="margin">Personal info</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" value="{{ $personal_user->getReplace($personal_user->l_name??'-')??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" value="{{ $personal_user->getReplace($personal_user->f_name??'-')??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Middle Name</label>
                                    <input type="text" class="form-control" value="{{ $personal_user->getReplace($personal_user->m_name??'-')??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>PINFL</label>
                                    <input type="number" class="form-control" value="{{ $personal_user->pinfl??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>STIR</label>
                                    <input type="number" class="form-control" value="{{ $personal_user->inn??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Pas.Ser</label>
                                    <input type="text" class="form-control"
                                           value="{{ $personal_user->doc_series??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Pas.Num</label>
                                    <input type="number" class="form-control"
                                           value="{{ $personal_user->doc_number??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Pas.Address</label>
                                    <input type="text" class="form-control"
                                           value="{{ $personal_user->doc_address??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Live.Address</label>
                                    <input type="text" class="form-control" value="{{ $personal_user->address??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Birthday</label>
                                    <input type="date" class="form-control" value="{{ $personal_user->birthday??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control"
                                           value="{{ $personal_user->mobile_phone??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Tabel Number</label>
                                    <input type="text" class="form-control" value="{{ $current_work_user->tab_num??'-' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-5">
                <div class="box box-success">
                    <h3 class="margin">Current Position Roles</h3>

                    <div class="box-body">
                        @foreach($userWorkCurrentRoles as $item)
                            <span class="label label-success">{{ $item->title }}</span>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ url('/madmin/user/role-update',$current_work_user->id??0) }}" role="form">
                        @csrf
                        <div class="box-body">
                            <label for="roles">Select Roles<span class="text-red">*</span></label>
                            <select class="form-select select2" id="roles" aria-label="Default select example" style="width:100%" name="roles[]" multiple="multiple" required>
                                @foreach($userWorkRoles as $role)
                                    <option value="{{ $role->id }}"> {{ $role->title }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-pencil"></i> @lang('blade.update')</button>
                        </div>

                    </form>

                </div>
            </div>

            <div class="col-md-7">
                <div class="box box-danger">
                    <h3 class="margin">Current Position</h3>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Tabel Number</label>
                                    <input type="text" class="form-control" value="{{ $current_work_user->tab_num??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Branch Code</label>
                                    <input type="text" class="form-control" value="{{ $current_work_user->branch_code??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Filial Name</label>
                                    <input type="text" class="form-control" value="{{ $current_work_user->filial->title??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Date Begin</label>
                                    <input type="date" class="form-control" value="{{ $current_work_user->date_begin??'-' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Department Parent</label>
                                    <textarea class="form-control" rows="2">{{ $current_work_user->parent->title??'-' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Department</label>
                                    <textarea class="form-control" rows="2">{{ $current_work_user->department->title??'-' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Job title</label>
                                    <textarea class="form-control" rows="2">{{ $current_work_user->job_title??'-' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="#"
                           class="btn btn-danger pull-right">
                            <i class="fa fa-pencil"></i> @lang('blade.update')
                        </a>
                    </div>

                </div>
            </div>

            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-footer">
                        <h4 class="margin">Works History
                            <a href="{{ url('/madmin/update-user-work',[$user->id]) }}"
                               class="btn btn-info pull-right">
                                <i class="fa fa-refresh"></i> @lang('blade.refresh')
                            </a>
                        </h4>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>BranchCode</th>
                            <th>Filial</th>
                            <th>LocalCode</th>
                            <th>Parent</th>
                            <th>Department</th>
                            <th>TabNum</th>
                            <th>Job Title</th>
                            <th>Roles</th>
                            <th>DateBegin</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach ($user_history_works as $key => $model)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $model->branch_code }}</td>
                                <td class="text-sm text-info">{{ $model->filial->title??'-' }}</td>
                                <td>{{ $model->local_code }}</td>
                                <td>{{ $model->parent_code }} - <span class="text-sm text-purple">{{ $model->parent->title??'-' }}</span></td>
                                <td>{{ $model->depart_code }} - <span class="text-sm text-purple">{{ $model->department->title??'-' }}</span></td>
                                <td>{{ $model->tab_num }}</td>
                                <td>{{ $model->job_title }}</td>
                                <td>
                                    @if($model->roleId)
                                        @foreach($model->roleId as $role)
                                            <span class="label label-success">{{ $role->getRoleName->title??'-' }}</span>

                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $model->date_begin }}</td>
                                <td class="text-center">
                                    {{ $model->isActive }}
                                </td>
                                <td>{{ $model->created_at->format('d.M.Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>


        <script type="text/javascript">
            $(function () {
                $(".select2").select2();

                //iCheck for checkbox and radio inputs
                $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue'
                });
                //Red color scheme for iCheck
                $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                    checkboxClass: 'icheckbox_minimal-red',
                    radioClass: 'iradio_minimal-red'
                });
                //Flat red color scheme for iCheck
                $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-green',
                    radioClass: 'iradio_flat-green'
                });

            })
        </script>

    </section>
@endsection
