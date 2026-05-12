<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FinanceFlow - Wealth Management</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="logo-text">
                        <h1>FinanceFlow</h1>
                        <span>WEALTH MANAGEMENT</span>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('budgeting') }}" class="nav-item {{ request()->routeIs('budgeting') ? 'active' : '' }}">
                    <i class="fas fa-wallet"></i>
                    <span>Budgeting</span>
                </a>
                <a href="{{ route('transactions.index') }}" class="nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transactions</span>
                </a>
                <a href="{{ route('reports') }}" class="nav-item {{ request()->routeIs('reports') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Reports</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <a href="#" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-question-circle"></i>
                    <span>Support</span>
                </a>
            </div>
            
            <button class="btn-add-transaction" onclick="window.location='{{ route('transactions.create') }}'">
                <i class="fas fa-plus"></i>
                <span>Add Transaction</span>
            </button>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <!-- Top Bar -->
<header class="top-bar">
    <div class="top-bar-left">
        <nav class="top-nav">
            <a href="{{ route('home') }}" class="top-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">Financial Overview</a>
            <a href="{{ route('budgeting') }}" class="top-nav-item {{ request()->routeIs('budgeting') ? 'active' : '' }}">Portfolio</a>
            <a href="{{ route('reports') }}" class="top-nav-item {{ request()->routeIs('reports') ? 'active' : '' }}">Insights</a>
        </nav>
    </div>
    <div class="top-bar-right">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search transactions...">
        </div>
        <button class="btn-generate-report">Generate Report</button>
        
        <!-- User Menu - FIX DI SINI -->
        <div class="user-menu">
            @auth
                <i class="fas fa-bell"></i>
                <div class="user-avatar">
                    <span>{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                </div>
                <div class="dropdown-menu">
                    <span>{{ Auth::user()->email }}</span>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-login">Login</a>
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-register">Register</a>
                @endif
            @endauth
        </div>
    </div>
</header>
            
            <!-- Page Content -->
            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>