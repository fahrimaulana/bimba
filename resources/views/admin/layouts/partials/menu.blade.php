<li class="site-menu-category">Home</li>
<li class="site-menu-item has-sub {{ Request::is('admin/dashboard') ? 'active' : '' }}">
    <a href="{{ route('admin.dashboard') }}">
        <i class="site-menu-icon wb-dashboard"></i>
        <span class="site-menu-title">Dashboard</span>
    </a>
</li>

@can (['view-user-list', 'view-user-login-history', 'view-role-list', 'change-preference'])
<li class="site-menu-category">Settings</li>
@endcan
@can (['view-user-list', 'view-user-login-history', 'view-role-list'])
<li class="site-menu-item has-sub {{ Request::is('admin/management*') ? 'active open' : '' }}">
    <a href="javascript:void(0)">
        <i class="site-menu-icon wb-user"></i>
        <span class="site-menu-title">User Management</span>
        <span class="site-menu-arrow"></span>
    </a>
    <ul class="site-menu-sub">
        @can ('view-user-list')
        <li class="site-menu-item {{ Request::is('admin/management/user*') && !Request::is('admin/management/user/login-history') ? 'active' : '' }}">
            <a class="animsition-link" href="{{ route('admin.management.user.index') }}">
            <span class="site-menu-title">Users</span>
            </a>
        </li>
        @endcan
        @can ('view-user-login-history')
        <li class="site-menu-item {{ Request::is('admin/management/user/login-history') ? 'active' : '' }}">
            <a class="animsition-link" href="{{ route('admin.management.user.login-history') }}">
            <span class="site-menu-title">User Login History</span>
            </a>
        </li>
        @endcan
        @can ('view-role-list')
        <li class="site-menu-item {{ Request::is('admin/management/role*') ? 'active' : '' }}">
            <a class="animsition-link" href="{{ route('admin.management.role.index') }}">
            <span class="site-menu-title">Role & Permission</span>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcan
@can ('change-preference')
<li class='site-menu-item has-sub {{ Request::is('admin/preference*') ? 'active' : '' }}'>
    <a href='{{ route('admin.preference.edit') }}'>
        <i class='site-menu-icon wb-settings'></i>
        <span class='site-menu-title'>Preference</span>
    </a>
</li>
@endcan