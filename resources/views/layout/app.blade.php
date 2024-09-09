@php

@endphp

<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <meta charset="UTF-8">

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

    <script type='text/javascript' src='{{ asset('assets/js/fabric.min.js') }}'></script>

    {{-- Bootstrap js --}}
    <script type='text/javascript' src='{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.js') }}'></script>
    {{-- jquery --}}
    <script type='text/javascript' src='{{ asset('assets/js/jquery.min.js') }}'></script>
    {{-- popper --}}
    <script src="{{ asset('assets/js/popper.js') }}"></script>



    <link rel="stylesheet" href="{{ asset('assets/scss/app.scss') }}">
</head>



<body @auth id="body-pd" @endauth>

    <x-theme-toggle />
    <x-navbar />
    @auth
        <div class="l-navbar" id="nav-bar">
            <nav class="nav">
                <div>
                    <a href="{{ route('home') }}" class="nav_logo">

                        @hasrole('Admin')
                            <i class='bi bi-gear-fill nav_logo-icon'></i>
                            <span class="nav_logo-name">Gestion</span> </a>
                        @else
                            <i class='bi bi-house-fill nav_logo-icon'></i>
                            <span class="nav_logo-name">Home</span> </a>
                        @endhasrole


                    <div class="nav_list">


                        @if ($asamblea)
                            @if ($asamblea['registro'])
                                <a href="{{ route('asistencia.registrar') }}" class="nav_link ">
                                    <i class='bi bi-person-check-fill nav_icon'></i> <span class="nav_name">Registro</span>
                                </a>
                            @else
                                <a href="{{ route('asistencia.asignacion') }}" class="nav_link">
                                    <i class='bi bi-building-check nav_icon'></i> <span class="nav_name">Asignar</span>
                                </a>
                            @endif

                            <a href="{{ route('consulta') }}" class="nav_link">
                                <i class='bi bi-info-circle-fill nav_icon'></i> <span class="nav_name">Consulta</span>
                            </a>
                            <a href="{{ route('entregar') }}" class="nav_link">
                                <i class='bi bi-door-closed-fill nav_icon'></i> <span class="nav_name">Entregar</span>
                            </a>
                            @hasanyrole('Admin|Lider')
                                <a href="{{ route('votacion') }}" class="nav_link">
                                    <i class='bi bi-question-circle-fill nav_icon'></i> <span class="nav_name">Votacion</span>
                                </a>
                                <a href="{{ route('gestion.asamblea') }}" class="nav_link">
                                    <i class='bi bi-ui-checks-grid nav_icon'></i> <span class="nav_name">Asignaciones</span>
                                </a>
                            @endhasanyrole
                        @endif
                    </div>
                </div>

            </nav>
        </div>

    @endauth
    <div class="mt-3 mx-3">
        @yield('content')
    </div>
    @livewireScripts
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
</body>
<script></script>

</html>
