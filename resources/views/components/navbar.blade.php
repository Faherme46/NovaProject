<nav class="navbar navbar-expand-lg bg-primary header " id="header">
    <div class="container-fluid">
        @auth
            <div class="header_toggle">
                <i class='bi bi-list bx-w' id="header-toggle"></i>
            </div>
        @endauth

        <div class="collapse navbar-collapse me-3" id="navbarSupportedContent">
            <h2 class="mb-0 ms-2 text-light ">{{ $asamblea['folder'] }}</h2>
            <div class="mx-auto">
                <livewire:quorum-state />
            </div>
            <ul class="ms-auto navbar-nav mb-0 ">
                <li class="dropdown nav-item">
                    <button class="btn header_img nav-link py-2 dropdown-toggle d-flex align-items-center bx-w"
                        id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                        aria-label="Toggle theme (auto)">
                        <i class="bi bi-person-circle bx-w" style="font-size: 2rem"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end mx-0 shadow w-220px">
                        @auth
                            <li class="dropdown-item">
                                <span class="dropdown-item d-flex gap-2 align-items-center">
                                    {{ auth()->user()->name }}
                                </span>
                            </li>
                            <li class="dropdown-item">
                                <hr class="dropdown-divider">
                            </li>
                            <li class="dropdown-item">
                                <a class="dropdown-item  d-flex gap-2 align-items-center"
                                    href="{{ route('users.logout') }}">
                                    <i class="bi bi-arrow-left-square-fill"></i>
                                    Logout
                                </a>
                            </li>
                        @else
                            <li class="dropdown-item">
                                <a class="dropdown-item d-flex gap-2 align-items-center" href="{{ route('login') }}">
                                    Login
                                </a>
                            </li>
                        @endauth

                    </ul>
                </li>
            </ul>

        </div>
        <button button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
