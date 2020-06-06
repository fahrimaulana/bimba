@can (['view-user-list', 'view-user-login-history', 'view-role-list', 'change-preference'])
<li class="site-menu-category">Settings</li>
@endcan

<li class="site-menu-item has-sub {{ Request::is('unit/master*') ? 'active open' : '' }}">
    <a href="javascript:void(0)">
        <i class="site-menu-icon wb-grid-4"></i>
        <span class="site-menu-title">Master Data</span>
        <span class="site-menu-arrow"></span>
    </a>
    <ul class="site-menu-sub">
        @can('view-department-list')
        <li class='site-menu-item {{ Request::is('unit/master/department*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.department.index') }}'>
                <span class='site-menu-title'>Departemen</span>
            </a>
        </li>
        @endcan
        @can('view-staff-position-list')
        <li class='site-menu-item {{ Request::is('unit/master/staff-position*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.staff-position.index') }}'>
                <span class='site-menu-title'>Jabatan Staff</span>
            </a>
        </li>
        @endcan
        @can('view-absence-reason-list')
        <li class='site-menu-item {{ Request::is('unit/master/absence-reason*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.absence-reason.index') }}'>
                <span class='site-menu-title'>Alasan Absen</span>
            </a>
        </li>
        @endcan
        @can('view-public-relation-status-list')
        <li class='site-menu-item {{ Request::is('unit/master/public-relation-status*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.public-relation-status.index') }}'>
                <span class='site-menu-title'>Status Humas</span>
            </a>
        </li>
        @endcan
        @can('view-grade-list')
        <li class='site-menu-item {{ Request::is('unit/master/grade*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.grade.index') }}'>
                <span class='site-menu-title'>Grade</span>
            </a>
        </li>
        @endcan
        @can('view-class-group-list')
        <li class='site-menu-item {{ Request::is('unit/master/class-group*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.class-group.index') }}'>
                <span class='site-menu-title'>Grup Kelas</span>
            </a>
        </li>
        @endcan
        @can('view-media-source-list')
        <li class='site-menu-item {{ Request::is('unit/master/media-source*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.media-source.index') }}'>
                <span class='site-menu-title'>Sumber Media</span>
            </a>
        </li>
        @endcan
        @can('view-student-phase-list')
        <li class='site-menu-item {{ Request::is('unit/master/student-phase*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.student-phase.index') }}'>
                <span class='site-menu-title'>Tahap Murid</span>
            </a>
        </li>
        @endcan
        @can('view-student-out-reason-list')
        <li class='site-menu-item {{ Request::is('unit/master/student-out-reason*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.student-out-reason.index') }}'>
                <span class='site-menu-title'>Alasan Keluar Murid</span>
            </a>
        </li>
        @endcan
        @can('view-student-note-list')
        <li class='site-menu-item {{ Request::is('unit/master/student-note*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.student-note.index') }}'>
                <span class='site-menu-title'>Catatan Murid</span>
            </a>
        </li>
        @endcan
        @can('view-special-allowance-group-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/master/special-allowance-group*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.special-allowance-group.index') }}'>
                <span class='site-menu-title'>Grup Tunjangan Khusus</span>
            </a>
        </li>
        @endcan
        @can('view-special-allowance-list')
        <li class='site-menu-item has-sub {{ !Request::is('unit/master/special-allowance-group*') && Request::is('unit/master/special-allowance*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.special-allowance.index') }}'>
                <span class='site-menu-title'>Tunjangan Khusus</span>
            </a>
        </li>
        @endcan
    </ul>
</li>

@can (['view-user-list', 'view-user-login-history', 'view-role-list'])
<li class="site-menu-item has-sub {{ Request::is('unit/management*') ? 'active open' : '' }}">
    <a href="javascript:void(0)">
        <i class="site-menu-icon wb-user"></i>
        <span class="site-menu-title">User Management</span>
        <span class="site-menu-arrow"></span>
    </a>
    <ul class="site-menu-sub">
        @can ('view-user-list')
        <li class="site-menu-item {{ Request::is('unit/management/user*') && !Request::is('unit/management/user/login-history') ? 'active' : '' }}">
            <a class="animsition-link" href="{{ route('client.management.user.index') }}">
            <span class="site-menu-title">Users</span>
            </a>
        </li>
        @endcan
        @can ('view-user-login-history')
        <li class="site-menu-item {{ Request::is('unit/management/user/login-history') ? 'active' : '' }}">
            <a class="animsition-link" href="{{ route('client.management.user.login-history') }}">
            <span class="site-menu-title">User Login History</span>
            </a>
        </li>
        @endcan
        @can ('view-role-list')
        <li class="site-menu-item {{ Request::is('unit/management/role*') ? 'active' : '' }}">
            <a class="animsition-link" href="{{ route('client.management.role.index') }}">
            <span class="site-menu-title">Role & Permission</span>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcan
@can ('change-preference')
<li class='site-menu-item has-sub {{ Request::is('unit/preference*') ? 'active' : '' }}'>
    <a href='{{ route('client.preference.edit') }}'>
        <i class='site-menu-icon wb-settings'></i>
        <span class='site-menu-title'>Preference</span>
    </a>
</li>
@endcan

@can ('change-preference')
<li class='site-menu-item has-sub {{ Request::is('unit/upload-download*') ? 'active' : '' }}'>
    <a href='{{ route('client.upload-download.index') }}'>
        <i class='site-menu-icon wb-download'></i>
        <span class='site-menu-title'>Upload & Download File</span>
    </a>
</li>
@endcan
<li class='site-menu-item has-sub {{ Request::is('unit/report/view*') ? 'active' : '' }}'>
    <a href='{{ route('client.report.view.index') }}'>
        <i class='site-menu-icon fa-file-text-o'></i>
        <span class='site-menu-title'>Report</span>
    </a>
</li>