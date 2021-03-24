<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="{{ url('admin-lte/bootstrap/css/bootstrap.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ url('admin-lte/dist/css/AdminLTE.min.css') }}">
<!-- iCheck -->
<link rel="stylesheet" href="{{ url('admin-lte/plugins/iCheck/square/blue.css') }}">
<style>
    body {
        overflow-y: hidden;
    }

</style>
<body class="hold-transition login-page">

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Web</b> EDO</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">@lang('blade.login_seans')</p>

        <form action="{{ route('login') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}  has-feedback">
                <input id="username" type="text" class="form-control" placeholder="@lang('blade.username')" name="username"
                       value="{{ old('username') }}" required autofocus>

                @if ($errors->has('username'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                @endif
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}  has-feedback">
                <input id="password" type="password" class="form-control" placeholder="@lang('blade.password')" name="password"
                       required>

                @if ($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"  name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('blade.remember')
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('blade.login')</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="{{ url('/admin-lte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ url('/admin-lte/plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>


