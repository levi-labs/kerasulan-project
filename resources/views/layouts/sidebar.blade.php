<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="profile-image">
                    <img class="img-xs rounded-circle" src="{{ auth()->user()->getAvatar() }}" alt="profile image">
                    <div class="dot-indicator bg-success"></div>
                </div>
                <div class="text-wrapper">
                    <p class="profile-name">Allen Moreno</p>
                    <p class="designation">Administrator</p>
                </div>
                <div class="icon-container">
                    <i class="icon-bubbles"></i>
                    <div class="dot-indicator bg-danger"></div>
                </div>
            </a>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Dashboard</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <span class="menu-title">Dashboard</span>
                <i class="icon-screen-desktop menu-icon"></i>
            </a>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Simulation</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('data-training.index') }}">
                <span class="menu-title">Data Training</span>
                <i class="icon-screen-desktop menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('training-data.index') }}">
                <span class="menu-title">Training Data</span>
                <i class="icon-screen-desktop menu-icon"></i>
            </a>
        </li>
        <li class="nav-item nav-category"><span class="nav-link">Master</span></li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('recruitments.index') }}">
                <span class="menu-title">Recruitment</span>
                <i class="icon-globe menu-icon"></i>
            </a>
        </li>

        <li class="nav-item nav-category"><span class="nav-link">Sample Pages</span></li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <span class="menu-title">User Management</span>
                <i class="icon-layers menu-icon"></i>
            </a>
        </li>

        <li class="nav-item pro-upgrade">
            <span class="nav-link">
                <a class="btn btn-block px-0 btn-rounded btn-upgrade" href="{{ route('users.index') }}" target="_blank">
                    <i class="icon-badge mx-2"></i> Upgrade to Pro</a>
            </span>
        </li>
    </ul>
</nav>
