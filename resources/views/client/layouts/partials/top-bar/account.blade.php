<li class="nav-item dropdown">
    <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
        <span class="avatar avatar-online">
            {{ ucwords(optional($authUser)->name) }} <span class="role-display">as <b>{{ ucwords($authUser->role ? optional($authUser->role)->display_name : '-') }}</b></span>
            <img src="{{ asset('global/portraits/5.jpg') }}" style="width: 30px !important;" alt="...">
            <i></i>
        </span>
    </a>
    <div class="dropdown-menu" role="menu">
        <a class="dropdown-item" href="{{ route(strtolower(platform()) . '.management.user.change-password') }}" role="menuitem"><i class="icon fa-key" aria-hidden="true"></i> Change Password</a>
        <a class="dropdown-item" href="{{ route(strtolower(platform()) . '.logout') }}" role="menuitem"><i class="icon wb-power" aria-hidden="true"></i> Logout</a>
    </div>
</li>