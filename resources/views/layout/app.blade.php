@php

@endphp

<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Nova</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    {{-- Bootstrap CSS --}}
    <link href='{{ asset('assets/scss/custom.css') }}' rel='stylesheet'>
    {{-- Fuente de iconos --}}
    <link href='{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}' rel='stylesheet'>


    {{-- Bootstrap js --}}
    <script type='text/javascript' src='{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.js') }}'></script>
    {{-- jquery --}}
    <script type='text/javascript' src='{{ asset('assets/js/jquery.min.js') }}'></script>
    {{-- popper --}}
    <script src="{{ asset('assets/js/popper.js') }}"></script>

    {{-- color theme --}}
    <script src="{{ asset('assets/js/color-modes.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('assets/scss/app.scss') }}">
</head>



<body id="body-pd">
    <svg xmlns="{{ asset('assets/bootstrap-icons/bootstrap-icons.svg') }}" class="d-none">
        <symbol id="check2" viewBox="0 0 16 16">
            <path
                d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path
                d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
            <path
                d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path
                d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
        </symbol>
    </svg>

    <nav class="navbar bd-primary header" id="header">
        <div class="header_toggle"> <i class='bi bi-list  ' id="header-toggle"></i> </div>
        <div class="col ms-4">
            <h2 class="">{{ $name_asamblea }}</h2>
        </div>

        <ul class="navbar-nav flex-row flex-wrap ms-md-auto align-items-baseline">
            <li class="nav-item ">
                <h3>Quorum:</h3>
            </li>
            <li class="nav-item py-2 py-lg-1 col-12 col-lg-auto">
                <div class="vr d-none d-lg-flex h-100 mx-lg-2 text-white"></div>
                <hr class="d-lg-none my-2 text-white-50">
            </li>
            <li class="dropdown nav-item ">
                <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center show"
                    id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                    aria-label="Toggle theme (auto)">
                    <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
                        <use href="#circle-half"></use>
                    </svg>
                    <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="light" aria-pressed="false">
                            <svg class="bi me-2 opacity-50" width="1em" height="1em">
                                <use href="#sun-fill"></use>
                            </svg>
                            Light
                            <svg class="bi ms-auto d-none" width="1em" height="1em">
                                <use href="#check2"></use>
                            </svg>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item  d-flex align-items-center"
                            data-bs-theme-value="dark" aria-pressed="false">
                            <svg class="bi me-2 opacity-50" width="1em" height="1em">
                                <use href="#moon-stars-fill"></use>
                            </svg>
                            Dark
                            <svg class="bi ms-auto d-none" width="1em" height="1em">
                                <use href="#check2"></use>
                            </svg>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item  d-flex align-items-center active"
                            data-bs-theme-value="auto" aria-pressed="true">
                            <svg class="bi me-2 opacity-50" width="1em" height="1em">
                                <use href="#circle-half"></use>
                            </svg>
                            Auto
                            <svg class="bi ms-auto d-none" width="1em" height="1em">
                                <use href="#check2"></use>
                            </svg>
                        </button>
                    </li>
                </ul>

            </li>
            <li class="nav-item py-2 py-lg-1 col-12 col-lg-auto">
                <div class="vr d-none d-lg-flex h-100 mx-lg-2 text-white"></div>
                <hr class="d-lg-none my-2 text-white-50">
            </li>
            <li class="dropdown nav-item">
                <button class="btn header_img nav-link py-2 dropdown-toggle d-flex align-items-center" id="bd-theme"
                    type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
                    <i class="bi bi-person-circle" style="font-size: 2rem"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end mx-0 shadow w-220px">
                    @auth
                        <li class="dropdown-item">
                            <span class="dropdown-item d-flex gap-2 align-items-center">
                                {{ $currentUser->name }}
                            </span>
                        </li>
                        <li class="dropdown-item">
                            <hr class="dropdown-divider">
                        </li>
                        <li class="dropdown-item">
                            <a class="dropdown-item  d-flex gap-2 align-items-center" href="{{ route('users.logout') }}">
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



    </nav>

    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="{{ route('home') }}" class="nav_logo"> <i class='bi bi-house-fill nav_logo-icon'></i> <span
                        class="nav_logo-name">Home</span> </a>
                <div class="nav_list">
                    <a href="{{ route('asistencia.index') }}" class="nav_link ">
                        <i class='bi bi-person-check-fill nav_icon'></i>
                        <span class="nav_name">Registro</span> </a>
                    @hasanyrole('Admin|Lider')
                        <a href="{{ route('admin.asambleas') }}" class="nav_link">
                            <i class='bi bi-gear-wide-connected nav_icon'></i> <span class="nav_name">Asamblea</span>
                        </a>
                    @endhasanyrole

                    <a href="{{ route('asistenciaa') }}" class="nav_link">
                        <i class='bi bi-ui-checks-grid nav_icon'></i> <span class="nav_name">Asignaciones</span></a>
                    <a href="{{ route('users.index') }}" class="nav_link">
                        <i class='bi bi-people-fill nav_icon'></i> <span class="nav_name">Usuarios</span> </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-folder nav_icon'></i> <span class="nav_name">Resultados</span> </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span class="nav_name">Asistencia</span> </a>
                </div>
            </div>
            @auth
                <div>
                    <p class="nav_logo"> <i class='bi bi-person-circle nav_logo-icon'></i>
                        <span class="nav_logo-name">{{ $currentUser->name }} <br>
                            <small class="">{{$currentUser->getRoleNames()->first()}}</small> </span>

                    </p>

                    <hr class="divider">
                    <a href="#" class="nav_link"> <i class='bi bi-box-arrow-right nav_icon'></i>
                        <span class="nav_name">Salir</span> </a>
                </div>
            @endauth

        </nav>
    </div>



    <div class="height-100 ">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dimissible" role="alert">
                <div class="row justify-content-between">
                    <div class="col-6">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                    <div class="col-1 offset-md-5">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>

                </div>

            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
        @isset($slot)
            {{ $slot }}
        @endisset
    </div>







    @livewireScripts
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
