<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <meta charset="UTF-8">

    {{-- color theme --}}
    {{-- <script src="{{ asset('assets/js/color-modes.js') }}"></script> --}}

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



<body>
    <div class="position-fixed mb-3 me-3 z-3 m-3 bottom-0 end-0">
        <button type="button" class="btn btn-primary" id="fullscreen-button">
            <i class="bi bi-fullscreen"></i>
        </button>
    </div>


    <div class="px-3">

        <div class="z-2">
            {{ $slot }}
        </div>

    </div>


    @livewireScripts
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
</body>
<script>
    document.getElementById('fullscreen-button').addEventListener('click', function() {
        if (!document.fullscreenElement) {
            // Entrar en pantalla completa
            document.documentElement.requestFullscreen().catch((err) => {
                alert(`Error al intentar entrar en pantalla completa: ${err.message} (${err.name})`);
            });
            this.innerHTML = '<i class="bi bi-fullscreen-exit"></i>';
        } else {
            // Salir de pantalla completa
            document.exitFullscreen().catch((err) => {
                alert(`Error al intentar salir de pantalla completa: ${err.message} (${err.name})`);
            });
            this.innerHTML = '<i class="bi bi-fullscreen"></i>';
        }
    });
</script>

</html>
