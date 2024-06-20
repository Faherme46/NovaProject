<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Nova</title>

    {{-- Bootstrap CSS --}}
    <link href='{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css') }}' rel='stylesheet'>
    {{-- Fuente de iconos --}}
    <link href='https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css' rel='stylesheet'>

    {{-- Bootstrap js --}}
    <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>
    {{-- jquery --}}
    <script type='text/javascript' src='{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}'></script>
    {{-- popper --}}
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>


    <link rel="stylesheet" href="{{ asset('assets/scss/app.scss') }}">
</head>



    <body id="body-pd">
        <header class="header mb-5" id="header">
            <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
            <div class="col ms-4">
                <h2 class="">{{ $name_asamblea }}</h2>
            </div>



            <div class="header_img"> <img src="https://i.imgur.com/hczKIze.jpg" alt=""> </div>
        </header>

        <div class="l-navbar" id="nav-bar">
            <nav class="nav">
                <div>
                    <a href="{{ route('home') }}" class="nav_logo"> <i class='bx bx-home nav_logo-icon'></i> <span
                            class="nav_logo-name">Home</span> </a>
                    <div class="nav_list">
                        <a href="{{route('asistencia.index')}}" class="nav_link active"> <i class='bx bx-user-check nav_icon'></i>
                            <span class="nav_name">Registro</span> </a>
                        <a href="{{route('admin.asambleas')}}" class="nav_link"> <i class='bx bx-cog nav_icon'></i> <span
                                class="nav_name">Asamblea</span> </a>
                        <a href="{{route('asistenciaa')}}" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span
                                class="nav_name">Asignaciones</span></a>
                        <a href="#" class="nav_link"> <i class='bx bx-bookmark nav_icon'></i> <span
                                class="nav_name">Votos</span> </a>
                        <a href="#" class="nav_link"> <i class='bx bx-folder nav_icon'></i> <span
                                class="nav_name">Resultados</span> </a>
                        <a href="#" class="nav_link"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span
                                class="nav_name">Asistencia</span> </a>
                    </div>
                </div>
                <a href="#" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                        class="nav_name">SignOut</span> </a>
            </nav>
        </div>


        <!--Container Main start-->
        <div class="height-100 ">
            @yield('content')
        </div>
        <!--Container Main end-->








        <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
        <script type='text/javascript'>
            var myLink = document.querySelector('a[href="#"]');
            myLink.addEventListener('click', function(e) {
                e.preventDefault();
            });
        </script>
    </body>

</html>
