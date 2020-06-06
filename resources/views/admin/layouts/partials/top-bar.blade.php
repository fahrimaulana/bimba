<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" id="header-logo-container" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided" data-toggle="menubar">
            <span class="sr-only">Toggle navigation</span>
            <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">
            <i class="icon wb-more-horizontal" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle"{{--  data-toggle="gridmenu" --}}>
            <span class="navbar-brand-logo">
                <a href="{{ route(guardType() . '.index') }}">
                    <img class="client-logo-img" src="{{ headerLogo() }}" height="47px">
                </a>
            </span>
        </div>
        {{-- <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search" data-toggle="collapse">
            <span class="sr-only">Toggle Search</span>
            <i class="icon wb-search" aria-hidden="true"></i>
        </button> --}}
    </div>
    <div class="navbar-container container-fluid">
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
            <ul class="nav navbar-toolbar">
                <li class="nav-item hidden-float" id="toggleMenubar">
                    <a class="nav-link" data-toggle="menubar" href="#" role="button">
                        <i class="icon hamburger hamburger-arrow-left">
                            <span class="sr-only">Toggle menubar</span>
                            <span class="hamburger-bar"></span>
                        </i>
                    </a>
                </li>
                <li class="nav-item hidden-sm-down" id="toggleFullscreen">
                    <a class="nav-link icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">
                    <span class="sr-only">Toggle fullscreen</span>
                    </a>
                </li>
                {{-- <li class="nav-item hidden-float">
                    <a class="nav-link icon wb-search" data-toggle="collapse" href="#" data-target="#site-navbar-search" role="button">
                    <span class="sr-only">Toggle Search</span>
                    </a>
                </li>
                @include(strtolower(platform()) . '.layouts.partials.top-bar.mega-menu')
                @include(strtolower(platform()) . '.layouts.partials.top-bar.customer-info') --}}
            </ul>
            <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                {{--@include(strtolower(platform()) . '.layouts.partials.top-bar.country-flag') --}}
                {{-- @include(strtolower(platform()) . '.layouts.partials.top-bar.notification') --}}
                {{-- @include(strtolower(platform()) . '.layouts.partials.top-bar.message') --}}
                @include(strtolower(platform()) . '.layouts.partials.top-bar.account')
            </ul>
        </div>
        <div class="collapse navbar-search-overlap" id="site-navbar-search">
            <form role="search">
                <div class="form-group">
                    <div class="input-search">
                        <i class="input-search-icon wb-search" aria-hidden="true"></i>
                        <input type="text" class="form-control" name="site-search" placeholder="Search...">
                        <button type="button" class="input-search-close icon wb-close" data-target="#site-navbar-search" data-toggle="collapse" aria-label="Close"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</nav>