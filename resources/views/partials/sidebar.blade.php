
<style>
    .list-group .list-group-item.active {
    background-color: #0d79ab !important;
    color: white;
}
body{
    font-family: var(--bs-font-sans-serif) !important;
    font-size: 0.9rem !important;
    font-weight: 400 !important;
    line-height: 1.5 !important;
    -webkit-text-size-adjust: 100% !important;

    }
</style>
{{-- sidebar --}}
<aside class="col-10 col-md-3 col-xl-2 px-0 shadow-sm bg-white position-fixed 
    top-0 left-0 h-100 sidebar overflow-auto d-print-none" id="sidebar" style="z-index: 100;">
    <img src="{{ asset('assets/img/curtis.png') }}" class="d-block mx-auto my-4 pb-2" width="140" alt="curtis-logo">
    <section class="list-group rounded-0">

    <a  href="{{ route('dashboard') }}" class="list-group-item list-group-item-action border-0 d-flex justify-content-between {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span>
            <i class="fa fa-home" aria-hidden="true"></i>&nbsp;&nbsp; Dashboard
        </span>
    </a>



        @if(auth()->user()->can('viewAny', App\Models\Item::class)
        || auth()->user()->can('viewAny', App\Models\Category::class)
        || auth()->user()->can('viewAny', App\Models\City::class)
        || auth()->user()->can('viewAny', App\Models\Party::class)
        || auth()->user()->can('viewAny', App\Models\Process::class)
        || auth()->user()->can('viewAny', App\Models\Designation::class)
        || auth()->user()->can('viewAny', App\Models\Employee::class)
        || auth()->user()->can('viewAny', App\Models\Transport::class))
        <a class="list-group-item list-group-item-action border-0 d-flex justify-content-between {{request()->routeIs('items.index') || request()->routeIs('categories.index') || request()->routeIs('cities.index') || request()->routeIs('parties.index') || request()->routeIs('processes.index') || request()->routeIs('designations.index') || request()->routeIs('staff-types.index') || request()->routeIs('employees.index') || request()->routeIs('transports.index') ? 'active' : ''}}" data-bs-toggle="collapse" href="#master-collapse" role="button">
            
            <span>
                <i class="fa fa-archive me-2"></i> Masters
            </span>
            <i class="fa fa-angle-down"></i>
            
        </a>
        <div class="collapse" id="master-collapse" data-bs-parent="#sidebar">
            <div class="list-group">

                @can('viewAny', App\Models\Item::class)
                <a href="{{ route('items.index') }}" class="list-group-item list-group-item-action border-0 ">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-archive me-2"></i> Items
                </a>
                @endcan

                @can('viewAny', App\Models\Category::class)
                <a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action border-0 {{request()->routeIs('categories.index') ? 'active' : ''}}">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-list" aria-hidden="true"></i> Categories
                </a>
                @endcan

                @can('viewAny', App\Models\City::class)
                <a href="{{ route('cities.index') }}" class="list-group-item list-group-item-action border-0 {{request()->routeIs('cities.index') ? 'active' : ''}}">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-location-arrow" aria-hidden="true"></i> Cities
                </a>
                @endcan

                @can('viewAny', App\Models\Party::class)
                <a href="{{ route('parties.index') }}" class="list-group-item list-group-item-action border-0 {{request()->routeIs('parties.index') ? 'active' : ''}}">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-users" aria-hidden="true"></i> Parties
                </a>
                @endcan

                @can('viewAny', App\Models\Process::class)
                <a href="{{ route('processes.index') }}" class="list-group-item list-group-item-action border-0 {{request()->routeIs('processes.index') ? 'active' : ''}}">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-archive me-2"></i> Processes
                </a>
                @endcan

                @can('viewAny', App\Models\Designation::class)
                <a href="{{ route('designations.index') }}" class="list-group-item list-group-item-action border-0 {{request()->routeIs('designations.index') ? 'active' : ''}}">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-archive me-2"></i> Designations
                </a>
                @endcan

                <a href="{{ route('staff-types.index') }}" class="list-group-item list-group-item-action border-0 {{request()->routeIs('staff-types.index') ? 'active' : ''}}">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-archive me-2"></i> Staff Types
                </a>

                @can('viewAny', App\Models\Employee::class)
                <a href="{{ route('employees.index') }}" class="list-group-item list-group-item-action border-0 {{request()->routeIs('employees.index') ? 'active' : ''}}">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-archive me-2"></i> Employees
                </a>
                @endcan

                @can('viewAny', App\Models\Transport::class)
                <a href="{{ route('transports.index') }}" class="list-group-item list-group-item-action border-0 {{request()->routeIs('transports.index') ? 'active' : ''}}">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-truck me-2"></i> Transports
                </a>
                @endcan

            </div>
        </div>
        @endif

        @can('create', App\Models\Order::class)
        <a class="list-group-item list-group-item-action border-0 d-flex justify-content-between {{request()->routeIs('direct.sale.create') || request()->routeIs('direct.sale.listing') || request()->routeIs('orders.index') ? 'active' : ''}}" data-bs-toggle="collapse" href="#direct-collapse" role="button">
            <span>
                <i class="fa fa-shopping-cart me-2"></i> Orders
            </span>
            <i class="fa fa-angle-down"></i>
        </a>
        <div class="collapse" id="direct-collapse" data-bs-parent="#sidebar">
            <div class="list-group">

                <!-- <a href="{{ route('direct.sale.create') }}" class="list-group-item list-group-item-action border-0">
                    <i class="fa fa-user-friends me-2"></i><i class="fa fa-plus-circle" aria-hidden="true"></i> Create Direct Order
                </a> -->
                <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action border-0">
                    <i class="fa fa-user-friends me-2"></i><i class="fa fa-list" aria-hidden="true"></i>  Normal Orders
                </a>
                <a href="{{ route('direct.sale.listing') }}" class="list-group-item list-group-item-action border-0">
                    <i class="fa fa-user-friends me-2"></i><i class="fa fa-list" aria-hidden="true"></i>  Direct Orders
                </a>
            </div>
        </div>
        @endcan

        @can('create', App\Models\Order::class)
        <a class="list-group-item list-group-item-action border-0 d-flex justify-content-between {{request()->routeIs('order.sale.bills') || request()->routeIs('order.purchase.bills') ? 'active' : ''}}" data-bs-toggle="collapse" href="#bills-collapse" role="button">
            <span>
                <i class="fa fa-shopping-cart me-2"></i> Bills
            </span>
            <i class="fa fa-angle-down"></i>
        </a>
        <div class="collapse" id="bills-collapse" data-bs-parent="#sidebar">
            <div class="list-group">

                <!-- <a href="{{ route('direct.sale.create') }}" class="list-group-item list-group-item-action border-0">
                    <i class="fa fa-user-friends me-2"></i><i class="fa fa-plus-circle" aria-hidden="true"></i> Create Direct Order
                </a> -->
                <a href="{{ route('order.sale.bills') }}" class="list-group-item list-group-item-action border-0">
                    <i class="fa fa-user-friends me-2"></i><i class="fa fa-list" aria-hidden="true"></i> Sale BIlls
                </a>
                <a href="{{ route('order.purchase.bills') }}" class="list-group-item list-group-item-action border-0">
                    <i class="fa fa-user-friends me-2"></i><i class="fa fa-list" aria-hidden="true"></i>  Purchase Bills
                </a>
                
            </div>
        </div>
        @endcan

        @if(auth()->user()->can('create', App\Models\Transaction::class)
        || auth()->user()->can('viewAny', App\Models\Transfer::class))

        <a class="list-group-item list-group-item-action border-0 d-flex justify-content-between {{request()->routeIs('account-vouchers.create') || request()->routeIs('multiple.account-vouchers.create') ||  request()->routeIs('stock-vouchers.create') || request()->routeIs('jobs.create') || request()->routeIs('transfers.index') || request()->routeIs('salary-vouchers.index') ? 'active' : ''}}" data-bs-toggle="collapse" href="#voucher-collapse" role="button">
            <span>
                <i class="fa fa-shopping-cart me-2"></i> Vouchers
            </span>
            <i class="fa fa-angle-down"></i>
        </a>
        <div class="collapse" id="voucher-collapse" data-bs-parent="#sidebar">
            <div class="list-group">
                @can('create', App\Models\Transaction::class)
                <a href="{{ route('account-vouchers.create') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-user" aria-hidden="true"></i> Account Voucher
                </a>


                <a href="{{ route('multiple.account-vouchers.create') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-users me-2"></i>
                    Account Voucher <span class="badge alert-primary ms-1">M</span>
                </a>

                <a href="{{ route('stock-vouchers.create') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-list-alt" aria-hidden="true"></i> Stock Voucher
                </a>
                @endcan

                <a href="{{ route('jobs.create') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-list-alt" aria-hidden="true"></i>    JW Opening Stock
                </a>

                @can('viewAny', App\Models\Transfer::class)
                <a href="{{ route('transfers.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-share-square-o" aria-hidden="true"></i> Transfers
                </a>
                @endcan

                <a href="{{ route('salary-vouchers.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-money" aria-hidden="true"></i> Salary Vouchers
                </a>
            </div>
        </div>
        @endif

        <a href="{{ route('employee-attendances.index') }}" class="list-group-item list-group-item-action border-0 {{request()->routeIs('employee-attendances.index') ? 'active' : ''}}">
            <i class="fa fa-calendar me-2"></i> Attendance
        </a>

        <a class="list-group-item list-group-item-action border-0 d-flex justify-content-between {{request()->routeIs('ledger.index') || request()->routeIs('ledger2.index') || request()->routeIs('ledger3.index') || request()->routeIs('party-stock.index') || request()->routeIs('itemwise-stock.index') || request()->routeIs('narration.index') || request()->routeIs('itemLocationStock.index') || request()->routeIs('location-stock.index') || request()->routeIs('transfer-transactions.index') || request()->routeIs('order-transports.index')  ? 'active' : ''}}" data-bs-toggle="collapse" href="#report-collapse" role="button">
            <span>
                <i class="fa fa-book me-2"></i> Reports
            </span>
            <i class="fa fa-angle-down"></i>
        </a>

        <div class="collapse" id="report-collapse" data-bs-parent="#sidebar">
            <div class="list-group">

                @if (auth()->user()->can('viewAny', App\Models\Transaction::class))
                <a href="{{ route('ledger.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-book  me-2"></i> Ledger <span class="badge alert-primary ms-1">page</span>
                </a>

                <a href="{{ route('ledger2.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-book  me-2"></i> Ledger <span class="badge alert-primary ms-1">lazy</span>
                </a>
                @endif

                <a href="{{ route('ledger3.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-book  me-2"></i> Ledger <span class="badge alert-primary ms-1">30 days</span>
                </a>
                <a href="{{ route('party-stock.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-bars" aria-hidden="true"></i> Party Wise Stock
                </a>

                <a href="{{ route('itemwise-stock.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-bars" aria-hidden="true"></i> Item Wise Stock
                </a>

                <a href="{{ route('narration.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-bars" aria-hidden="true"></i> Naration Wise Stock
                </a>
                <a href="{{ route('itemLocationStock.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-bars" aria-hidden="true"></i> Item Location Stock
                </a>

                <a href="{{ route('item-stock.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-bars" aria-hidden="true"></i> Item Stock
                </a>

                <!-- <a href="{{ route('jobs.create') }}" class="list-group-item list-group-item-action border-0">
                    <i class="fa fa-bars" aria-hidden="true"></i> JW Opening Stock
                </a> -->

                <a href="{{ route('location-stock.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-bars" aria-hidden="true"></i> Location Stock
                </a>

                <a href="{{ route('transfer-transactions.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-share-square-o" aria-hidden="true"></i> Transfer Transactions
                </a>

                <a href="{{ route('order-transports.index') }}" class="list-group-item list-group-item-action border-0">
                <i class="fa fa-user-friends me-2"></i><i class="fa fa-truck" aria-hidden="true"></i> Order Transports
                </a>
            </div>
        </div>

        @if(auth()->user()->can('viewAny', App\Models\User::class)
        || auth()->user()->can('viewAny', App\Models\Permission::class)
        || auth()->user()->can('viewAny', App\Models\Role::class))

        <a class="list-group-item list-group-item-action border-0 d-flex justify-content-between {{ request()->routeIs('permissions.index') || request()->routeIs('roles.index') || request()->routeIs('users.index') ? 'active' : '' }}" data-bs-toggle="collapse" href="#setting-collapse" role="button">
    <span>
        <i class="fa fa-cogs me-2"></i> Settings
    </span>
    <i class="fa fa-angle-down"></i>
</a>
<div class="collapse" id="setting-collapse" data-bs-parent="#sidebar">
    <div class="list-group">
        <!-- Permissions -->
        @can('viewAny', App\Models\Permission::class)
        <a href="{{ route('permissions.index') }}" class="list-group-item list-group-item-action border-0">
        <i class="fa fa-user-friends me-2"></i><i class="fa fa-lock" aria-hidden="true"></i> Permissions
        </a>
        @endcan

        <!-- Roles -->
        @can('viewAny', App\Models\Role::class)
        <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action border-0">
        <i class="fa fa-user-friends me-2"></i><i class="fa fa-user-plus" aria-hidden="true"></i> Roles
        </a>
        @endcan

        <!-- Users -->
        @can('viewAny', App\Models\User::class)
        <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action border-0">
        <i class="fa fa-user-friends me-2"></i><i class="fa fa-users me-2"></i> Users
        </a>
        @endcan
    </div>
</div>

        @endif

    </section>
</aside>
{{-- sidebar overlay --}}
<div class="d-none position-fixed top-0 left-0 w-100 h-100" id="sidebar-overlay" style="background: rgb(0 0 0 / 50%);z-index: 99;"></div>