@can (['exchange-card', 'access-redeem-transaction', 'access-manual-earning'])
    <li class="nav-item dropdown">
        @if (Session::has('locked_customer'))
            <a class="nav-link customer-info" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up"
            role="button">
                <p class="text-center mar0 text-primary"><b>{{ lockedCustomer()->name }}</b></p>
                <p class="text-center mar0">{{ lockedCustomer()->barcode ?: '-' }}</p>
                <i class="icon wb-chevron-down-mini" aria-hidden="true"></i>
            </a>
            <div class="dropdown-menu" role="menu">
                @can ('view-customer-list')
                <a class="dropdown-item" href="{{ route('customer.show', lockedCustomer()->id) }}" role="menuitem"><i class="icon wb-info-circle" aria-hidden="true"></i> Customer Detail</a>
                @endcan
                @can ('edit-customer')
                <a class="dropdown-item" href="{{ route('customer.edit', lockedCustomer()->id) }}" role="menuitem"><i class="icon wb-edit" aria-hidden="true"></i> Edit Customer</a>
                @endcan
                <a class="dropdown-item" href="{{ route('customer.active.index') }}" role="menuitem"><i class="icon wb-loop" aria-hidden="true"></i> Change Customer</a>
            </div>
        @else
            <a class="nav-link customer-info" data-toggle="tooltip" data-placement="bottom" href="{{ route('customer.active.index') }}" data-original-title="Click to select customer">
                <p class="text-center mar0"><b>- No Customer -</b></p>
                <p class="text-center mar0"><button class="btn btn-outline btn-success btn-xs">Choose Customer</button></p>
            </a>
        @endif
    </li>

    @if (Session::has('locked_customer'))
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="tooltip" data-placement="bottom" href="#" data-original-title="Total Expense">
                <i class="icon fa-money" aria-hidden="true"></i>
                <span>{{ thousandSeparator(lockedCustomer()->total_expense, 2) . ' IDR'}}</span>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="tooltip" data-placement="bottom" href="#" data-original-title="Redeem Point">
                <i class="icon fa-gift" aria-hidden="true"></i>
                <span>{{ thousandSeparator(lockedCustomer()->redeem_point) }} Pts</span>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="tooltip" data-placement="bottom" href="#" data-original-title="Lucky Draw Point">
                <i class="icon fa-empire" aria-hidden="true"></i>
                <span>{{ thousandSeparator(lockedCustomer()->luckyDrawPoint) }} Pts</span>
            </a>
        </li>
    @endif
@endcan