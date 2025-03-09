<div wire:keypress='verifyAsistente' class=" bg-primary bg-opacity-25">

    <div class="z-3 position-fixed top-50 start-50 translate-middle  w-60 ">


        @session('success')
            <div class="alert alert-success alert-dismissible align-items-center d-flex" role="alert">
                <h1 class="mb-0">{{ session('success') }}</h1>
                <button type="button" class="btn-close mb-0" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession

        @session('error')
            <div class="alert alert-danger alert-dismissible align-items-center d-flex" role="alert">
                <h1 class="mb-0">{{ session('error') }}</h1>
                <button type="button" class="btn-close mb-0" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession
        @session('warning')
            <div class="alert alert-warning alert-dismissible align-items-center d-flex" role="alert">
                <h1 class="mb-0">{{ session('warning') }}</h1>
                <button type="button" class="btn-close mb-0" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession

        @session('info')
            <div class="alert alert-info alert-dismissible align-items-center d-flex" role="alert">
                <h1 class="mb-0">{{ session('info') }}</h1>
                <button type="button" class="btn-close mb-0" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession


        @session('voted')
            <p class="d-none" wire:poll.20s="dropAlert"></p>
            <div class="alert bg-white" role="alert">
                <div class="d-flex justify-content-between">
                    <h1 class="text-primary">RESUMEN</h1>
                    <button type="button" class="btn-close mb-0 me-0" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <hr class="mt-2">
                <div class="text-center table-responsive table-fixed-header table-60">
                    <h3 class="mb-0">{{ $resumen['asistente'] }}</h1 >
                    
                    <table class="table table-bordered table-striped">
                        <thead class="table-active">
                            <th></th>
                            <th></th>
                            <th class="fs-4">Coeficiente</th>
                            {{-- <th class="fs-4">Votos</th> --}}
                            <th class="text-start fs-4">Vota por:</th>
                        </thead>
                        @foreach ($resumen as $key => $value)
                            @if ($key != 'asistente')
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$value['torre']}}</td>
                                <td>{{$value['coeficiente']}}</td>
                                {{-- <td>{{$value['votos']}}</td> --}}
                                <td class="text-start">{{$value['candidato']}}</td>
                            </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        @endsession
    </div>

    <p class="d-none "></p>
    @if ($asistente && $control && !$asamblea['h_cierre'])

        <div class="d-flex justify-content-center vh-100 align-items-center ">
            <div class="card col-10">

                <div class="card-body">
                    <h1 class="mb-0 card-title " style="font-size: 4rem">{{ $asistente->fullName() }}</h1>
                    <h1 class="mb-0 text-muted"> {{ strtoupper($control->vote) }} </h1>
                </div>
                <div class="card-footer d-flex justify-content-end">

                    <button class="btn btn-primary fs-3" wire:click='votar'>SIGUIENTE</button>
                </div>
            </div>
        </div>
    @elseif ($asamblea)
        <div class="row mx-0 text-center vh-100 align-items-center ">
            <div class=" justify-content-center pt-0">
                <img src="/storage/images/logo.png" style="width: 400px;" alt="logo">
                <h1 class="mb-0 me-3 mt-5 "
                    style="font-size: 6rem;color:#cc0000; font-weight:bolder;font-family:'Trebuchet MS';">
                    @if ($asamblea['h_cierre'])
                        VOTACIONES CERRADAS
                    @else

                        {{ strtoupper($terminal->user_name) }}
                        <br>
                        <button class="btn btn-primary fs-1" wire:click='verifyAsistente'>INGRESAR</button>

                    @endif
                </h1>
            </div>
        </div>
    @else
        <div class="row text-center  vh-100 align-items-center bg-warning">
            <h1>No hay asamblea en sesion</h1>
        </div>
    @endif
</div>
