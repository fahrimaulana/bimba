<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
        <meta name="author" content="{{ env('APP_NAME') }}">
        <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
        <!-- Stylesheets -->
        <link rel="stylesheet" href="{{ asset('global/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/css/bootstrap-extend.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/site.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/fb-spinner.css') }}">
        <!-- Plugins -->
        <link rel="stylesheet" href="{{ asset('global/vendor/animsition/animsition.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/asscrollable/asScrollable.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-sweetalert/sweetalert.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-select/bootstrap-select.min.css') }}">
        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('global/fonts/font-awesome/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/fonts/open-iconic/open-iconic.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/fonts/weather-icons/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/fonts/web-icons/web-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('global/fonts/brand-icons/brand-icons.min.css') }}">
        <!--[if lt IE 9]>
        <script src="{{ asset('global/vendor/html5shiv/html5shiv.min.js') }}"></script>
        <![endif]-->
        <!--[if lt IE 10]>
        <script src="{{ asset('global/vendor/media-match/media.match.min.js') }}"></script>
        <script src="{{ asset('global/vendor/respond/respond.min.js') }}"></script>
        <![endif]-->
        <script src="{{ asset('global/vendor/breakpoints/breakpoints.min.js') }}"></script>
        <script>
            Breakpoints();
        </script>
        @yield('head')
    </head>
    <body class="animsition dashboard">
        <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        @include(strtolower(platform()) . '.layouts.partials.top-bar')
        @include(strtolower(platform()) . '.layouts.partials.left-menu')
        {{-- @include(strtolower(platform()) . '.layouts.partials.grid-menu') --}}

        <div class="page">
            <div class="page-header">
                <h1 class="page-title">@yield('title')</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route(guardType() . '.index') }}">Home</a></li>
                    @stack('breadcrumb')
                </ol>
            </div>
            <div class="page-content container-fluid">
                @yield('content')
            </div>
        </div>

        @include(strtolower(platform()) . '.layouts.partials.footer')

        <script src="{{ asset('global/vendor/babel-external-helpers/babel-external-helpers.js') }}"></script>
        <script src="{{ asset('global/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('global/vendor/vue/vue.min.js') }}"></script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.js"></script> --}}
        <script src="{{ asset('global/vendor/tether/tether.min.js') }}"></script>
        <script src="{{ asset('global/vendor/bootstrap/bootstrap.min.js') }}"></script>
        <script src="{{ asset('global/vendor/customd-jquery-number/jquery.number.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('global/vendor/mousewheel/jquery.mousewheel.min.js') }}"></script>
        <script src="{{ asset('global/vendor/asscrollbar/jquery-asScrollbar.min.js') }}"></script>
        <script src="{{ asset('global/vendor/asscrollable/jquery-asScrollable.min.js') }}"></script>
        <script src="{{ asset('global/vendor/ashoverscroll/jquery-asHoverScroll.min.js') }}"></script>
        <script src="{{ asset('global/vendor/animsition/animsition.min.js') }}"></script>
        <script src="{{ asset('global/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
        <script src="{{ asset('global/js/State.min.js') }}"></script>
        <script src="{{ asset('global/js/Component.min.js') }}"></script>
        <script src="{{ asset('global/js/Plugin.min.js') }}"></script>
        <script src="{{ asset('global/js/Base.min.js') }}"></script>
        <script src="{{ asset('global/js/Config.min.js') }}"></script>
        <script src="{{ asset('assets/js/Section/Menubar.min.js') }}"></script>
        <script src="{{ asset('assets/js/Section/GridMenu.min.js') }}"></script>
        <script src="{{ asset('assets/js/Section/Sidebar.min.js') }}"></script>
        <script src="{{ asset('assets/js/Section/PageAside.min.js') }}"></script>
        <script src="{{ asset('assets/js/Plugin/menu.min.js') }}"></script>
        <script src="{{ asset('global/js/config/colors.min.js') }}"></script>
        <script src="{{ asset('global/vendor/screenfull/screenfull.min.js') }}"></script>
        <script src="{{ asset('assets/js/Site.min.js') }}"></script>
        <script src="{{ asset('global/vendor/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
        <script src="{{ asset('global/js/Plugin/bootstrap-select.min.js') }}"></script>
        <script>
            Config.set('assets', '{{ asset('assets') }}');
            $(document).ready(function() {
                Site.run();

                @if (Session::has('swal_error'))
                    swal("Error!", "{!! Session::get('swal_error') !!}", "error");
                @elseif (Session::has('swal_success'))
                    swal("Success!", "{!! Session::get('swal_success') !!}", "success");
                @endif

                $('.separator.currency').number(true, 2);
                $('.separator').not('.separator.currency').number(true, 0);
                $('.separator').keyup(function() {
                    $(this).next('.separator-hidden').val($(this).val());
                });
                @if (!Request::is('report*'))
                // jQuery plugin to prevent double submission of forms
                jQuery.fn.preventDoubleSubmission = function() {
                    $(this).on('submit',function(e){
                        var $form = $(this);
                        if ($form.data('submitted') === true) {
                            e.preventDefault();
                        } else {
                            $form.data('submitted', true);
                        }
                    });

                    return this;
                };
                $('form').preventDoubleSubmission();
                @endif

                $('#locked-year-filter, #locked-month-filter').on('change', function() {
                    $('#locked-period-form').submit();
                });

                @if (!Request::is('/'))
                    if ($('li.site-menu-item').hasClass('active')) {
                        $('.site-menubar .scrollable-container').animate({
                            scrollTop: $('li.site-menu-item.active').offset().top - 130
                        }, "slow")
                    }
                @endif
            });
        </script>
        @yield('footer')
    </body>
</html>