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
    {{-- Fuente de iconos --}}
    <link href='{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}' rel='stylesheet'>

    <script type='text/javascript' src='{{ asset('assets/js/fabric.min.js') }}'></script>

    {{-- Bootstrap js --}}
    <script type='text/javascript' src='{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.js') }}'></script>
    {{-- jquery --}}
    <script type='text/javascript' src='{{ asset('assets/js/jquery.min.js') }}'></script>
    {{-- popper --}}
    <script src="{{ asset('assets/js/popper.js') }}"></script>


    <style>
        :root {
            --header-height: 3rem;
            --nav-width: 68px;

            --white-color-txt: #F7F6FB;
            --white-color: #c8beef;
            --black-color: #151515;
            --darkblue-color: #010152;
            --body-font: 'Nunito', sans-serif;
            --secondary-color: #bfbfbf;
            --z-fixed: 100
        }
    </style>
    @switch($themeId)
        @case(1)
            <link rel="stylesheet" href="{{ asset('assets/scss/orange.css') }}">
            <style>
                :root {
                    --first-color: #e45801;
                    --first-color-light: #ffa600;
                }
            </style>
        @break

        @case(2)
            <link rel="stylesheet" href="{{ asset('assets/scss/cyan.css') }}">
            <style>
                :root {
                    --first-color: #3ab4ff;
                    --first-color-light: #caebff;
                }
            </style>
        @break

        @case(3)
            <link rel="stylesheet" href="{{ asset('assets/scss/teal.css') }}">
            <style>
                :root {
                    --first-color: #00a976;
                    --first-color-light: #dcfdf3;
                }
            </style>
        @break

        @case(4)
            <link rel="stylesheet" href="{{ asset('assets/scss/pink.css') }}">
            <style>
                :root {
                    --first-color: #ff7cbe;
                    --first-color-light: #ffc9e4;
                }
            </style>
        @break

        @case(5)
            <link rel="stylesheet" href="{{ asset('assets/scss/indigo.css') }}">
            <style>
                :root {
                    --first-color: #4723D9;
                    --first-color-light: #AFA5D9;
                }
            </style>
        @break

        @case(6)
            <link rel="stylesheet" href="{{ asset('assets/scss/blue.css') }}">
            <style>
                :root {
                    --first-color: #3c80fd;
                    --first-color-light: #99bdff;
                }
            </style>
        @break

        @case(7)
            <link rel="stylesheet" href="{{ asset('assets/scss/crimson.css') }}">
            <style>
                :root {
                    --first-color: #a03a3a;
                    --first-color-light: #be9191;
                }
            </style>
        @break

        @default
    @endswitch
    <link rel="stylesheet" href="{{ asset('assets/scss/app.scss') }}">
</head>



<body @auth id="body-pd" @endauth>

    <x-theme-toggle />
    <x-navbar />
    @auth
        <div class="l-navbar" id="nav-bar">
            <nav class="nav">
                <div class="nav_list">
                    <a href="{{ route('home') }}" class="nav_link" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        data-bs-title="Menu Principal">
                        <i class='bi bi-house-fill nav_logo-icon'></i>
                    </a>

                    @if ($asamblea)
                        @if ($asamblea['eleccion'])
                            <a href="{{ route('elecciones.registrar') }}" class="nav_link" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" data-bs-title="Registro">
                                <i class='bi bi-person-check-fill nav_icon'></i>
                            </a>
                            <a href="{{ route('elecciones.candidatos') }}" class="nav_link ps-3 pt-1" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" data-bs-title="Candidatos">
                                <i class='bi bi-person-video2 ms-1 fs-3'></i>
                            </a>

                            <a href="{{ route('gestion.asamblea') }}" class="nav_link" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" data-bs-title="Control">
                                <i class='bi bi-ui-checks-grid nav_icon'></i>
                            </a>
                        @else
                            @if ($asamblea['registro'])
                                <a href="{{ route('asistencia.registrar') }}" class="nav_link" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" data-bs-title="Registro">
                                    <i class='bi bi-person-check-fill nav_icon'></i>
                                </a>
                            @else
                                <a href="{{ route('asistencia.asignacion') }}" class="nav_link" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" data-bs-title="Asignacion">
                                    <i class='bi bi-building-check nav_icon'></i>
                                </a>
                            @endif

                            <a href="{{ route('consulta') }}" class="nav_link" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" data-bs-title="Consulta">
                                <i class='bi bi-info-circle-fill nav_icon'></i>
                            </a>
                            <a href="{{ route('entregar') }}" class="nav_link" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" data-bs-title="Entrega">
                                <i class='bi bi-door-closed-fill nav_icon'></i>
                            </a>
                            @hasanyrole('Admin|Lider')
                                <a href="{{ route('votacion') }}" class="nav_link" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" data-bs-title="Votaciones">
                                    <i class='bi bi-question-circle-fill nav_icon'></i>
                                </a>
                                <a href="{{ route('votacion.show') }}" class="nav_link" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" data-bs-title="Ver Votaciones">
                                    <i class='bi bi-patch-question nav_icon'></i>
                                </a>
                                <a href="{{ route('gestion.asamblea') }}" class="nav_link" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" data-bs-title="Control">
                                    <i class='bi bi-ui-checks-grid nav_icon'></i>
                                </a>
                            @endhasanyrole
                        @endif

                    @endif
                </div>
                <div class="nav-logo">
                    <a class="nav_link" href="#" data-bs-placement="bottom" data-bs-title="Cerrar sesion"
                        data-bs-toggle="modal" data-bs-target="#logOutModal">
                        <i class="bi bi-box-arrow-left  nav_icon fs-4"></i>
                        Logout
                    </a>
                </div>

            </nav>
        </div>

    @endauth
    <div class="mt-3 mx-3">
        @yield('content')
    </div>
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="logOutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">¿Desea cerrar sesión?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="{{ route('users.logout') }}" class="btn btn-warning">Continuar</a>
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
