@php

@endphp

<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    {{-- color theme --}}
    <script src="{{ asset('assets/js/color-modes.js') }}"></script>

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



    <link rel="stylesheet" href="{{ asset('assets/scss/app.scss') }}">
</head>



<body id="body-pd">

    <x-theme-toggle/>
    <x-navbar/>

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
                            <small class="">{{ $currentUser->getRoleNames()->first() }}</small> </span>

                    </p>

                    <hr class="divider">
                    <a href="#" class="nav_link"> <i class='bi bi-box-arrow-right nav_icon'></i>
                        <span class="nav_name">Salir</span> </a>
                </div>
            @endauth

        </nav>
    </div>



    <div class="height-100 container">
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
