<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    {{-- color theme --}}
    {{-- <script src="{{ asset('assets/js/color-modes.js') }}"></script>     --}}

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
    <script type='text/javascript' src='{{ asset('assets/js/fabric.min.js') }}'></script>
    {{-- popper --}}
    <script src="{{ asset('assets/js/popper.js') }}"></script>

    <script src="{{ asset('assets/js/chart.js') }}"></script>


    <link rel="stylesheet" href="{{ asset('assets/scss/app.scss') }}">

    <style>
        html,
        body {
            overflow: hidden;
        }
    </style>
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
