<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
        <meta name="author" content="{{ env('APP_NAME') }}">
        <title>Login | {{ env('APP_NAME') }}</title>
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
        <!-- Stylesheets -->
        <link rel="stylesheet" href="{{ asset('global/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/css/bootstrap-extend.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/fonts/web-icons/web-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/site.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css?v=') }}{{ env('CSS_VERSION') }}">
        <!-- Plugins -->
        <link rel="stylesheet" href="{{ asset('assets/examples/css/pages/login-v2.min.css') }}">
        <!-- Fonts -->
        <script src="{{ asset('global/vendor/modernizr/modernizr.min.js') }}"></script>
    </head>
    <body class="page-login-v2 layout-full page-dark">
        <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <!-- Page -->
        <div class="page animsition" data-animsition-in="fade-in" data-animsition-out="fade-out">
            <div class="page-content">
                <div class="page-brand-info">
                    <div class="brand">
                        <h2 class="brand-text font-size-40">Welcome Back</h2>
                    </div>
                    <p class="font-size-20">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione vitae quas quaerat vero sed officiis eos molestias, sint animi, quibusdam repellendus id dolore? Iste similique suscipit vel? Repellat, harum officiis.</p>
                </div>
                <div class="page-login-main">
                    <div class="brand">
                        <img class="brand-img " src="{{ asset('assets/images/client-logo.png') }}" width="350px">
                    </div>
                    <h3 class="font-size-24">Sign In</h3>
                    <form method="POST" action="{{ route($platform . '.login') }}">
                        {{ csrf_field() }}
                        @include('inc.error-list')
                        @if (Session::get('alert_error'))
                        <div class="alert alert-alt alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <p class="alert-link">{{ Session::get('alert_error') }}</p>
                        </div>
                        @endif
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputEmail" name="login" placeholder="Email or Username" value="{{ old('login') }}">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password">
                        </div>
                        <div class="form-group clearfix">
                            <div class="checkbox-custom checkbox-inline checkbox-primary pull-left">
                                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">Remember me</label>
                            </div>
                            {{-- <a class="pull-right" href="forgot-password.html">Forgot password?</a> --}}
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                    </form>
                    {{-- <p>No account? <a href="register-v2.html">Sign Up</a></p> --}}
                </div>
            </div>
        </div>
    </body>
</html>