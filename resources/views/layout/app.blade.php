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
            --first-color: #4723D9;
            --primary: #4723D9;
            --first-color-light: #AFA5D9;
            --white-color-txt: #F7F6FB;
            --white-color: #c8beef;
            --black-color: #151515;
            --darkblue-color: #010152;
            --body-font: 'Nunito', sans-serif;
            --secondary-color: #bfbfbf;
            --z-fixed: 100
        }
    </style>
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
                        @if ($asamblea['registro'])
                            <a href="{{ route('asistencia.registrar') }}" class="nav_link"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Registro">
                                <i class='bi bi-person-check-fill nav_icon'></i>
                            </a>
                        @else
                            <a href="{{ route('asistencia.asignacion') }}" class="nav_link"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Asignacion">
                                <i class='bi bi-building-check nav_icon'></i>
                            </a>
                        @endif

                        <a href="{{ route('consulta') }}" class="nav_link"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Consulta">
                            <i class='bi bi-info-circle-fill nav_icon'></i>
                        </a>
                        <a href="{{ route('entregar') }}" class="nav_link"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Entrega">
                            <i class='bi bi-door-closed-fill nav_icon'></i>
                        </a>
                        @hasanyrole('Admin|Lider')
                            <a href="{{ route('votacion') }}" class="nav_link"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Votaciones">
                                <i class='bi bi-question-circle-fill nav_icon'></i>
                            </a>
                            <a href="{{ route('gestion.asamblea') }}" class="nav_link"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Control">
                                <i class='bi bi-ui-checks-grid nav_icon'></i>
                            </a>
                        @endhasanyrole
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
          <a href="{{route('users.logout')}}" class="btn btn-warning"  >Continuar</a>
        </div>
      </div>
    </div>
  </div>
    @livewireScripts
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
