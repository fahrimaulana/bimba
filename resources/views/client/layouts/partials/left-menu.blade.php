<div class="site-menubar">
    <div class="site-menubar-body">
        <div>
            <div class="site-menubar-header">
                <form id="locked-period-form" method="POST" action="{{ route('general.session.locked-period.change') }}">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-xs-6" style="padding-right: .46875em">
                            <div class="site-menu-category access-level-title mart10">Tahun</div>
                            <select id="locked-year-filter" name="year" class="show-tick select-user-access" data-plugin="selectpicker">
                                @foreach (range(2010, now()->year) as $year)
                                    <option value="{{ $year }}" {{ (int) year() === $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-6" style="padding-left: .46875em">
                            <div class="site-menu-category access-level-title mart10">Bulan</div>
                            <select id="locked-month-filter" name="month" class="show-tick select-user-access" data-plugin="selectpicker">
                                @foreach (shortMonths() as $monthNo => $monthName)
                                    <option value="{{ $monthNo }}" {{ (int) month() === $monthNo ? 'selected' : '' }}>{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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