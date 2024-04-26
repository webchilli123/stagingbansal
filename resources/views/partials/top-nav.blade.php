<header class="d-flex justify-content-between align-items-center shadow-sm px-md-5 p-3 mb-4 d-print-none">
    {{-- sidebar opener --}}
    <button class="btn text-primary d-md-none py-0" id="sidebar-opener">
        <span class="fa fa-angle-right h3"></span>
    </button>

    <h6 class="text-primary d-none d-md-block">
        <i class="fa fa-archway me-2"></i> {{ config('app.company_name') }}
    </h6>
    
    {{-- logout dropdown --}}
    <div class="dropdown">
        <button class="btn d-flex align-items-center py-0" type="button" id="logout-dropdown" data-bs-toggle="dropdown">
           <small class="pb-2 mb-1 pe-2 text-muted">{{ Str::limit(auth()->user()->role->name, '15') }}</small>
            <span class="fa fa-user-circle h4 text-primary"></span>
            <span class="fa fa-angle-down small ms-2 mb-2"></span>
        </button>
        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" aria-labelledby="logout-dropdown">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">Logout</button>
            </form>
        </div>
    </div>
</header>
