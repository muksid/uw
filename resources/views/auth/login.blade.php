<head>
    <title>Web EDO</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="favicon.ico"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_temp/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_temp/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_temp/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_temp/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_temp/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_temp/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login_temp/css/util.css">
    <link rel="stylesheet" type="text/css" href="login_temp/css/main.css">
    <!--===============================================================================================-->
</head>
<style>
    body {
        overflow-y: hidden;
    }

</style>
<body>

<div class="limiter">
    <div class="container-login100" style="background-image: url('/login_temp/images/logo_back.svg');">
        <div class="wrap-login100 p-t-190 p-b-30">
            <form action="{{ route('login') }}" method="post" class="login100-form validate-form">
                {{ csrf_field() }}
                {{--<span class="login100-form-title p-t-20 p-b-45">
						@lang('blade.login_seans')
					</span>--}}
                <div class="wrap-input100 validate-input m-b-10" data-validate = "Username is required">
                    <input class="input100" type="text" id="username"  name="username"
                           placeholder="@lang('blade.username')" value="{{ old('username') }}" required autofocus>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-user"></i>
						</span>
                    @if ($errors->has('username'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="wrap-input100 validate-input m-b-10" data-validate = "Password is required">
                    <input id="password"  class="input100" name="password" type="password" name="pass"
                           placeholder="@lang('blade.password')">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-lock"></i>
						</span>
                </div>

                <div class="container-login100-form-btn p-t-10">
                    <button type="submit" class="login100-form-btn">
                        @lang('blade.login')
                    </button>
                </div>

                <div class="text-center w-full p-t-25 p-b-230">
                    <a href="#" class="txt1">
                        Forgot Username / Password?
                    </a>
                </div>

                <div class="text-center w-full">
                    {{--<a class="txt1" href="#">
                        Web EDO
                        --}}{{--<i class="fa fa-long-arrow-right"></i>--}}{{--
                    </a>--}}
                </div>
            </form>
        </div>
    </div>
</div>

<!--===============================================================================================-->
<script src="login_temp/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="login_temp/vendor/bootstrap/js/popper.js"></script>
<script src="login_temp/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="login_temp/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="login_temp/js/main.js"></script>

</body>