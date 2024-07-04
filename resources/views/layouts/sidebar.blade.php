<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="profile-image">
                    <img class="img-xs rounded-circle" src="{{ auth()->user()->getAvatar() }}" alt="profile image">
                    <div class="dot-indicator bg-success"></div>
                </div>
                <div class="text-wrapper">
                    <p class="profile-name">{{ auth()->user()->username }}</p>
                    <p class="designation">{{ auth()->user()->name }}</p>
                </div>

            </a>
        </li>
        <style>
            .nav-item.nav-category {
                color: #1b69e6 !important;
            }

            .menu-icon {
                color: #4183ee !important;
            }

            .sidebar .nav .nav-item.active:not(.navbar-brand-mini-wrapper) {
                background: none !important;
            }
        </style>
        <li class="nav-item nav-category">
            <span class="nav-link">Dashboard</span>
        </li>
        <li class="nav-item {{ request()->is('dashboard.index') ? '' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <span class="menu-title">Dashboard</span>
                <i class="icon-screen-desktop menu-icon"></i>
            </a>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Simulation</span>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('data-training.index') }}">
                <span class="menu-title">Data Training</span>
                <i class="icon-screen-desktop menu-icon"></i>
            </a>
        </li> --}}
        <li class="nav-item {{ request()->routeIs('training-data.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('training-data.index') }}">
                <span class="menu-title">Training Data</span>
                <i class="icon-book-open menu-icon"></i>
            </a>
        </li>
        <li class="nav-item nav-category"><span class="nav-link">Master</span></li>
        <li class="nav-item {{ request()->routeIs('recruitments.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('recruitments.index') }}">
                <span class="menu-title">Recruitment</span>
                <i class="icon-globe menu-icon"></i>
            </a>
        </li>

        <li class="nav-item nav-category"><span class="nav-link">User Management</span></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}"
                href="{{ route('users.index') }}">
                <span class="menu-title">Daftar User</span>
                <i class="icon-layers menu-icon"></i>
            </a>
        </li>


    </ul>
</nav>
