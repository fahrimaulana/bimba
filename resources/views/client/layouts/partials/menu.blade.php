<li class="site-menu-category">Home</li>
<li class="site-menu-item has-sub {{ Request::is('unit/dashboard') ? 'active' : '' }}">
    <a href="{{ route('client.dashboard') }}">
        <i class="site-menu-icon wb-dashboard"></i>
        <span class="site-menu-title">Dashboard</span>
    </a>
</li>

<li class="site-menu-item has-sub {{ Request::is('unit/report') ? 'active' : '' }}">
    <a href="{{ route('client.report.index') }}">
        <i class="site-menu-icon wb-pie-chart"></i>
        <span class="site-menu-title">Laporan</span>
    </a>
</li>

@can('edit-profile')
<li class='site-menu-item has-sub {{ Request::is('unit/profile*') ? 'active' : '' }}'>
    <a href='{{ route('client.profile.show-form') }}'>
        <i class='site-menu-icon wb-home'></i>
        <span class='site-menu-title'>Profil Unit</span>
    </a>
</li>
@endcan

@can (['view-staff-list', 'view-staff-absence-list'])
<li class="site-menu-item has-sub {{ Request::is('unit/staff*') || Request::is('unit/trial-student*') ? 'active open' : '' }}">
    <a href="javascript:void(0)">
        <i class="site-menu-icon wb-users"></i>
        <span class="site-menu-title">Staff</span>
        <span class="site-menu-arrow"></span>
    </a>
    <ul class="site-menu-sub">
        @can('view-staff-list')
        <li class='site-menu-item {{ Request::is('unit/staff*') ? 'active' : '' }}'>
            <a href='{{ route('client.staff.index') }}'>
                <span class='site-menu-title'>Daftar Staff</span>
            </a>
        </li>
        @endcan
        @can('view-staff-absence-list')
        <li class='site-menu-item {{ Request::is('unit/absence*') ? 'active' : '' }}'>
            <a href='{{ route('client.staff.absence.index') }}'>
                <span class='site-menu-title'>Absensi</span>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcan

@can('view-public-relation-list')
<li class='site-menu-item has-sub {{ Request::is('unit/public-relation *') ? 'active' : '' }}'>
    <a href='{{ route('client.public-relation.index') }}'>
        <i class='site-menu-icon wb-users'></i>
        <span class='site-menu-title'>Humas</span>
    </a>
</li>
@endcan

@can (['view-student-statistic', 'view-tuition', 'view-trial-student-list', 'view-move-grades','view-student-list','view-mbc','view-certificate'])
<li class="site-menu-item has-sub {{ Request::is('unit/student*') || Request::is('unit/trial-student*') ? 'active open' : '' }}">
    <a href="javascript:void(0)">
        <i class="site-menu-icon wb-users"></i>
        <span class="site-menu-title">Murid</span>
        <span class="site-menu-arrow"></span>
    </a>
    <ul class="site-menu-sub">
        @can('view-student-statistic')
        <li class='site-menu-item {{ Request::is('unit/student-statistic*') ? 'active' : '' }}'>
            <a href='{{ route('client.student.statistic.index') }}'>
                <span class='site-menu-title'>Statistik Murid</span>
            </a>
        </li>
        @endcan
        @can('view-trial-student-list')
        <li class='site-menu-item {{ Request::is('unit/trial-student*') ? 'active' : '' }}'>
            <a href='{{ route('client.student.trial-student.index') }}'>
                <span class='site-menu-title'>Murid Trial</span>
            </a>
        </li>
        @endcan
        @can('view-student-list')
        <li class='site-menu-item {{ !Request::is('unit/student/tuition*') && (Request::is('unit/student') || Request::is('unit/student/*')) ? 'active' : '' }}'>
            <a href='{{ route('client.student.index') }}'>
                <span class='site-menu-title'>Buku Induk</span>
            </a>
        </li>
        @endcan
        @can('view-tuition')
        <li class='site-menu-item {{ Request::is('unit/student/tuition*') ? 'active' : '' }}'>
            <a href='{{ route('client.student.tuition.index') }}'>
                <span class='site-menu-title'>Kartu SPP</span>
            </a>
        </li>
        @endcan
        @can('view-mbc')
        <li class='site-menu-item {{ Request::is('unit/mbc*') ? 'active' : '' }}'>
            <a href='{{ route('client.student.mbc.index') }}'>
                <span class='site-menu-title'>MBC Murid</span>
            </a>
        </li>
        @endcan
        @can('view-certificate')
        <li class='site-menu-item {{ Request::is('unit/student/certificate*') ? 'active' : '' }}'>
            <a href='{{ route('client.student.certificate.index') }}'>
                <span class='site-menu-title'>Sertifikat Beasiswa</span>
            </a>
        </li>
        @endcan
         @can('view-move-grades')
        <li class='site-menu-item {{ Request::is('unit/student/move-grades*') ? 'active' : '' }}'>
            <a href='{{ route('client.student.move-grades.index') }}'>
                <span class='site-menu-title'>Pindah Gol</span>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcan

@can(['view-class-list' ,'view-voucher-list', 'view-transaction-list', 'view-petty-cash-transaction-list', 'view-tuition-report'])
<li class="site-menu-item has-sub {{ Request::is('unit/finance*') ? 'active open' : '' }}">
    <a href="javascript:void(0)">
        <i class="site-menu-icon wb-tag"></i>
        <span class="site-menu-title">Keuangan</span>
        <span class="site-menu-arrow"></span>
    </a>
    <ul class="site-menu-sub">
        @can('view-class-list')
        <li class='site-menu-item {{ !Request::is('unit/master/class-group*') && Request::is('unit/master/class*') ? 'active' : '' }}'>
            <a href='{{ route('client.master.class.index') }}'>
                <span class='site-menu-title'>Kelas</span>
            </a>
        </li>
        @endcan
        @can('view-voucher-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/finance/voucher*') ? 'active' : '' }}'>
            <a href='{{ route('client.voucher.index') }}'>
                <span class='site-menu-title'>Voucher</span>
            </a>
        </li>
        @endcan

        @can('view-transaction-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/finance/transaction*') ? 'active' : '' }}'>
            <a href='{{ route('client.transaction.index') }}'>
                <span class='site-menu-title'>Penerimaan</span>
            </a>
        </li>
        @endcan

        @can('view-petty-cash-transaction-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/finance/petty-cash*') ? 'active' : '' }}'>
            <a href='{{ route('client.petty-cash.index') }}'>
                <span class='site-menu-title'>Petty Cash</span>
            </a>
        </li>
        @endcan

        @can('view-recap-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/finance/recap*') ? 'active' : '' }}'>
            <a href='{{ route('client.recap.index') }}'>
                <span class='site-menu-title'>Rekap</span>
            </a>
        </li>
        @endcan

        @can('view-tuition-report')
        <li class='site-menu-item has-sub {{ Request::is('unit/finance/tuition/report') ? 'active' : '' }}'>
            <a href='{{ route('client.tuition.report.index') }}'>
                <span class='site-menu-title'>Data SPP</span>
            </a>
        </li>
        @endcan

        <li class="site-menu-item has-sub {{ Request::is('unit/finance/salary*') ? 'active open' : '' }}">
            <a href="javascript:void(0)">
                <span class="site-menu-title">Gaji</span>
                <span class="site-menu-arrow"></span>
            </a>
            <ul class="site-menu-sub">
                @can('view-tuition-report')
                <li class='site-menu-item has-sub {{ Request::is('unit/finance/salary/skim*') ? 'active' : '' }}'>
                    <a href='{{ route('client.salary.skim.index') }}'>
                        <span class='site-menu-title second-sub-menu'>SKIM</span>
                    </a>
                </li>
                @endcan
                @can('view-staff-income-list')
                <li class='site-menu-item has-sub {{ Request::is('unit/finance/salary/income*') ? 'active' : '' }}'>
                    <a href='{{ route('client.salary.income.index') }}'>
                        <span class='site-menu-title second-sub-menu'>Pendapatan</span>
                    </a>
                </li>
                @endcan
                @can('view-staff-deduction-list')
                <li class='site-menu-item has-sub {{ Request::is('unit/finance/salary/deduction*') ? 'active' : '' }}'>
                    <a href='{{ route('client.salary.deduction.index') }}'>
                        <span class='site-menu-title second-sub-menu'>Pengurangan</span>
                    </a>
                </li>
                @endcan
                @can('view-staff-salary-list')
                <li class='site-menu-item has-sub {{ Request::is('unit/finance/salary/payment*') ? 'active' : '' }}'>
                    <a href='{{ route('client.salary.payment.index') }}'>
                        <span class='site-menu-title second-sub-menu'>Pembayaran</span>
                    </a>
                </li>
                @endcan
                @can('view-staff-slip-list')
                <li class='site-menu-item has-sub {{ Request::is('unit/finance/salary/slip*') ? 'active' : '' }}'>
                    <a href='{{ route('client.salary.slip.index') }}'>
                        <span class='site-menu-title second-sub-menu'>Slip</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>

        @can (['view-recap-progressive', 'view-payment-progressive', 'view-slip-progressive'])
        <li class="site-menu-item has-sub {{ Request::is('unit/finance/progressive*') ? 'active open' : '' }}">
            <a href="javascript:void(0)">
                <span class="site-menu-title">Progressive</span>
                <span class="site-menu-arrow"></span>
            </a>
            <ul class="site-menu-sub">
                @can('view-recap-progressive')
                <li class='site-menu-item has-sub {{ Request::is('unit/finance/progressive/recap*') ? 'active' : '' }}'>
                    <a href='{{ route('client.progressive.recap.index') }}'>
                        <span class='site-menu-title second-sub-menu'>Rekap</span>
                    </a>
                </li>
                @endcan
                @can('view-payment-progressive')
                <li class='site-menu-item has-sub {{ Request::is('unit/finance/progressive/payment*') ? 'active' : '' }}'>
                    <a href='{{ route('client.progressive.payment.index') }}'>
                        <span class='site-menu-title second-sub-menu'>Pembayaran</span>
                    </a>
                </li>
                @endcan
                @can('view-slip-progressive')
                <li class='site-menu-item has-sub {{ Request::is('unit/finance/progressive/slip*') ? 'active' : '' }}'>
                    <a href='{{ route('client.progressive.slip.index') }}'>
                        <span class='site-menu-title second-sub-menu'>Slip</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        @can('view-profit-sharing')
        <li class='site-menu-item has-sub {{ Request::is('unit/finance/profit-sharing*') ? 'active' : '' }}'>
            <a href='{{ route('client.profit-sharing.index') }}'>
                <span class='site-menu-title'>Bagi Hasil</span>
            </a>
        </li>
        @endcan

        @can('view-report-finance')
        <li class='site-menu-item has-sub {{ Request::is('unit/finance/report*') ? 'active' : '' }}'>
            <a href='{{ route('client.finance.report.index') }}'>
                <span class='site-menu-title'>Laporan</span>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcan

@can('view-dpu')
<li class="site-menu-item has-sub {{ Request::is('unit/dpu*') ? 'active open' : '' }}">
    <a href="{{ route('client.dpu.index') }}">
        <i class="site-menu-icon wb-stats-bars"></i>
        <span class="site-menu-title">DPU</span>
    </a>
</li>
@endcan

@can(['view-module-statistic', 'view-module-price-list', 'view-module-addition-list', 'view-module-usage-list', 'view-module-stock-recap-list',])
<li class="site-menu-item has-sub {{ Request::is('unit/module*') ? 'active open' : '' }}">
    <a href="javascript:void(0)">
        <i class="site-menu-icon wb-book"></i>
        <span class="site-menu-title">Modul</span>
        <span class="site-menu-arrow"></span>
    </a>
    <ul class="site-menu-sub">
        @can('view-module-statistic')
        <li class='site-menu-item has-sub {{ Request::is('unit/module/statistic*') ? 'active' : '' }}'>
            <a href='{{ route('client.module.statistic.index') }}'>
                <span class='site-menu-title'>Statistic</span>
            </a>
        </li>
        @endcan
        @can('view-module-price-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/module/price*') ? 'active' : '' }}'>
            <a href='{{ route('client.module.price.index') }}'>
                <span class='site-menu-title'>Harga</span>
            </a>
        </li>
        @endcan
        @can('view-module-addition-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/module/addition*') ? 'active' : '' }}'>
            <a href='{{ route('client.module.addition.index') }}'>
                <span class='site-menu-title'>Penerimaan</span>
            </a>
        </li>
        @endcan
        @can('view-module-usage-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/module/usage*') ? 'active' : '' }}'>
            <a href='{{ route('client.module.usage.index') }}'>
                <span class='site-menu-title'>Pemakaian</span>
            </a>
        </li>
        @endcan
        @can('view-module-stock-recap-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/module/stock-recap*') ? 'active' : '' }}'>
            <a href='{{ route('client.module.stock-recap.index') }}'>
                <span class='site-menu-title'>Rekap Stok</span>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcan

@can(['view-order-module-list', 'view-order-attribute-list', 'view-order-certificate-list', 'view-order-stpb-list', 'view-order-atk-list'])
<li class="site-menu-item has-sub {{ Request::is('unit/order*') ? 'active open' : '' }}">
    <a href="javascript:void(0)">
        <i class="site-menu-icon wb-shopping-cart"></i>
        <span class="site-menu-title">Order</span>
        <span class="site-menu-arrow"></span>
    </a>
    <ul class="site-menu-sub">
        @can('view-order-statistic')
        <li class='site-menu-item has-sub {{ Request::is('unit/order/statistic*') ? 'active' : '' }}'>
            <a href='{{ route('client.order.statistic.index') }}'>
                <span class='site-menu-title'>Statistic</span>
            </a>
        </li>
        @endcan
        @can('view-order-module-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/order/module*') ? 'active' : '' }}'>
            <a href='{{ route('client.order.module.index') }}'>
                <span class='site-menu-title'>Modul</span>
            </a>
        </li>
        @endcan
        @can('view-order-attribute-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/order/attribute*') ? 'active' : '' }}'>
            <a href='{{ route('client.order.attribute.index') }}'>
                <span class='site-menu-title'>KA | ME | Tas</span>
            </a>
        </li>
        @endcan
        @can('view-order-certificate-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/order/certificate*') ? 'active' : '' }}'>
            <a href='{{ route('client.order.certificate.index') }}'>
                <span class='site-menu-title'>Sertifikat</span>
            </a>
        </li>
        @endcan
        @can('view-order-stpb-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/order/stpb*') ? 'active' : '' }}'>
            <a href='{{ route('client.order.stpb.index') }}'>
                <span class='site-menu-title'>STPB</span>
            </a>
        </li>
        @endcan
        @can('view-order-atk-list')
        <li class='site-menu-item has-sub {{ Request::is('unit/order/atk*') ? 'active' : '' }}'>
            <a href='{{ route('client.order.atk.index') }}'>
                <span class='site-menu-title'>ATK</span>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcan

@can('view-product-list')
<li class='site-menu-item has-sub {{ Request::is('unit/product*') ? 'active' : '' }}'>
    <a href='{{ route('client.product.index') }}'>
        <i class='site-menu-icon wb-grid-4'></i>
        <span class='site-menu-title'>Produk</span>
    </a>
</li>
@endcan

@include ('client.layouts.partials.menu.settings')