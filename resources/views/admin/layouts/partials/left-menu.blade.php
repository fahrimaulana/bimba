<div class="site-menubar">
    <div class="site-menubar-body">
        <div>
            <div class="site-menubar-header">
                <form id="form-client" method="POST" action="">
                    {!! csrf_field() !!}
                    <div class="site-menu-category access-level-title mart10">Outlet</div>
                    <select id="client" name="client" class="show-tick select-user-access" data-plugin="selectpicker">
                        <option value="">Dummy Store I</option>
                        <option value="">Dummy Store II</option>
                    </select>
                </form>
            </div>
            <ul class="site-menu" data-plugin="menu">
                @include (strtolower(platform()) . '.layouts.partials.menu')
            </ul>
        </div>
    </div>
    {{-- <div class="site-menubar-footer">
        <a href="javascript: void(0);" data-placement="top" data-toggle="tooltip" data-original-title="Settings">
        <span class="icon wb-settings"></span>
        </a>
        <a href="javascript: void(0);" class="fold-show" data-placement="top" data-toggle="tooltip" data-original-title="Profile">
        <span class="icon wb-user"></span>
        </a>
        <a href="{{ route('logout') }}" data-placement="top" data-toggle="tooltip" data-original-title="Logout">
        <span class="icon wb-power"></span>
        </a>
    </div> --}}
</div>