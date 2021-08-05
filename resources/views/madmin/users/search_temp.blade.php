<table class="table table-hover table-bordered table-striped" id="search_table">
    <thead>
    <tr>
        <th style="max-width: 10px">#</th>
        <th>@lang('blade.full_name')</th>
        <th>@lang('blade.username')</th>
        <th>@lang('blade.branch')</th>
        <th>Office (BXO)-@lang('blade.position')</th>
        <th>@lang('blade.role')</th>
        <th>@lang('blade.status')</th>
        <th><i class="fa fa-pencil-square-o text-blue"></i></th>
        <th><i class="fa fa-trash-o text-red"></i></th>
        <th>@lang('blade.date')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($models as $key => $model)
        <tr>
            <td style="width: 20px">{{ $models->firstItem()+$key }}</td>
            <td>
                {{ $model->personal->l_name??'-' }} {{ $model->personal->f_name??'-' }}
            </td>
            <td>{{ $model->username }}</td>
            <td>
                {{ $model->currentWork->filial->title??'-' }}
            </td>
            <td>
                {{ $model->currentWork->department->title??'-' }}<br>
                <span class="text-sm text-muted">
                                                {{ $model->currentWork->job_title??'-' }}
                                            </span>
            </td>
            <td>
                @if($model->currentWork->roleId??'')
                    @foreach($model->currentWork->roleId as $value)
                        <span class="label label-info margin-r-5">{{ $value->getRoleName->title??'-' }}</span>
                    @endforeach
                @endif
            </td>
            <td>
                @switch($model->status)
                    @case(0)
                    <span class="label label-warning">passive</span>
                    @break
                    @case(1)
                    <span class="label label-success">active</span>
                    @break
                    @case(2)
                    <span class="label label-danger">deleted</span>
                    @break
                    @default
                    <span class="label label-default">unknown</span>
                @endswitch
            </td>
            <td class="text-center">
                <a href="{{ route('users.edit', $model->id) }}" class="btn btn-xs btn-info">
                    <i class="fa fa-pencil"></i>
                </a>
            </td>
            <td>
                @if($model->status == 2)

                    <a href="javascript:;" data-toggle="modal"
                       data-target="#DeleteModal" class="btn btn-xs btn-danger disabled">
                        <span class="glyphicon glyphicon-trash"></span></a>
                @else

                    <a href="javascript:;" data-toggle="modal"
                       onclick="deleteUrl({{$model->id}})"
                       data-target="#DeleteModal" class="btn btn-xs btn-danger">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                @endif

            </td>
            <td style="min-width: 110px">
                {{ \Carbon\Carbon::parse($model->created_at)->format('d.M.Y')}}
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
<span class="paginate">{{ $models->links() }}</span>
