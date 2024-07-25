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
    {{-- popper --}}
    <script src="{{ asset('assets/js/popper.js') }}"></script>

    <script src="{{asset('assets/js/chart.js')}}"></script>


    <link rel="stylesheet" href="{{ asset('assets/scss/app.scss') }}">

    <style>
        html,body{
            overflow: hidden;
        }
    </style>
</head>



<body >
    <div class="px-3">
        <x-alerts />

        <div class="z-2">
            {{$slot}}
        </div>

    </div>


    @livewireScripts
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
