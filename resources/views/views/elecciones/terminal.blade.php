<div>

    <x-alerts />

    <p class="d-none " wire:keypress='verifyAsistente'></p>
    @if ($asistente && $control && !$asamblea['h_cierre'])
        <div class="d-flex justify-content-center vh-100 align-items-center">
            <div class="card">
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
        <div class="row mx-0 text-center vh-100 align-items-center bg-info bg-opacity-25">
            <div class=" justify-content-center pt-0">
                <img src="/storage/images/logo.png" style="width: 400px;" alt="logo">
                <h1 class="mb-0 me-3 mt-5 "
                    style="font-size: 6rem;color:#cc0000; font-weight:bolder;font-family:'Trebuchet MS';">
                    @if ($asamblea['h_cierre'])
                        VOTACIONES CERRADAS
                    @else
                        {{ strtoupper($terminal->user_name) }} <br>
                        <button class="btn btn-"></button>
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
