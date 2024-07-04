<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <style>
        .navbar-brand.brand-logo {
            width: 100% !important;
            height: 100% !important;
        }

        .logo-dark {
            width: 100% !important;
            height: 140% !important;
        }
    </style>
    <div class="navbar-brand-wrapper d-flex align-items-center">

        <a class="navbar-brand brand-logo" href="#">

            <img src="{{ asset('assets/logo-gereja.png') }}" alt="logo" class="logo-dark" />

        </a>

    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center flex-grow-1">
        @if (auth()->user()->username)
            <h5 class="mb-0 font-weight-medium d-none d-lg-flex">Selamat Datang {{ auth()->user()->name }}!</h5>
        @endif
        <ul class="navbar-nav navbar-nav-right ml-auto">
            <li class="nav-item dropdown d-none d-xl-inline-flex user-dropdown">
                <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown"
                    aria-expanded="false">
                    <img class="img-xs rounded-circle ml-2" src="{{ auth()->user()->getAvatar() }}" alt="Profile image">
                    <span class="font-weight-normal"> {{ auth()->user()->username }} </span></a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <img class="img-md rounded-circle" src="{{ auth()->user()->getAvatar() }}" alt="Profile image">
                        <p class="mb-1 mt-3">{{ auth()->user()->username }}</p>
                        <p class="font-weight-light text-muted mb-0">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('users.change-password') }}" class="dropdown-item"><i
                            class="dropdown-item-icon icon-user text-primary"></i> Change
                        Password</a>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item"><i
                                class="dropdown-item-icon icon-power text-primary"></i>Sign
                            Out</button>
                    </form>
                    {{-- <a href="{{ route('logout') }}" class="dropdown-item"><i
                            class="dropdown-item-icon icon-power text-primary"></i>Sign
                        Out</a> --}}
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
