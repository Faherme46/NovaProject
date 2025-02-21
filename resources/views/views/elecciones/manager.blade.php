<div>
    <x-alerts />
    @session('terminal')
        <div class="position-fixed card top-50 start-50 translate-middle p-0  shadow-lg rounded-3 text-center"
            style="z-index: 1050; width: 400px;">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Terminal</h5>
                <button type="button" class="btn-close " wire:click='$refresh'></button>
            </div>
            <div class="card-body">
                <p class="mb-0 fs-5">El asistente puede votar en <span class="badge fs-5 text-bg-success">{{ session('terminal') }}</span></p>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button class="btn btn-primary" wire:click='$refresh'>Aceptar</button>
            </div>
        </div>
    @endsession
    <div class="row g-2">
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0">{{ $asamblea['folder'] }}</h2>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-no-outer-borders mb-0">
                        <tbody>
                            <tr>
                                <td class="text-end">Fecha:</td>
                                <td>{{ $asamblea['fecha'] }} </td>
                            </tr>
                            <tr>
                                <td class="text-end">Hora:</td>
                                <td class="text-start"> {{ $asamblea['hora'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-center py-2">

                    <button type="button"
                        class="btn {{ !$started ? 'btn-outline-success' : 'btn-success' }} fs-5 px-4  me-2"
                        data-bs-toggle="modal" data-bs-target="#modalIniciar" @disabled($started || $finished)>
                        Iniciar
                    </button>

                    <button type="button" class="btn {{ !$finished ? 'btn-outline-warning' : 'btn-warning' }} fs-5 "
                        data-bs-toggle="modal" data-bs-target="#modalTerminar" @disabled(!$started || $finished)>
                        Terminar
                    </button>
                    <br>

                </div>
            </div>
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h2 class="card-title mb-0">Asignaciones</h2>
                    <span wire:click='$refresh' class=" btn text-bg-secondary rounded-circle fs-5 px-2 pt-1 pb-0"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Recargar">
                        <i class="bi bi-arrow-clockwise"></i>
                    </span>
                </div>
                <div class="card-body table-responsive table-fixed-header table-h100 p-0">
                    <table class="table mb-0 table-striped-columns">
                        <thead>
                            <tr class="">
                                <th></th>
                                <th class="text-center">Asistente</th>
                                <th class="text-center">Predios</th>
                                <th class="text-center">Terminal</th>
                                <th class="text-center">Actualizar</th>
                            </tr>
                        </thead>

                        <tbody class="table-group-divider">
                            @forelse ($controles as $control)
                                <tr>
                                    <td class="align-middle">{{ $control->id }}</td>
                                    <td class="text-wrap align-middle" width="30%">{{ $control->persona->nombre }}
                                        {{ $control->persona->apellido }}</td>
                                    {{--  --}}
                                    </td>
                                    <td class="align-middle">
                                        @foreach ($control->predios as $predio)
                                            <p class="mb-0 @if (!$predio->vota) text-danger @endif">
                                                {{ $predio->getFullName() }}
                                            </p>
                                        @endforeach
                                    </td>

                                    @if ($control)
                                        <td class="text-center align-middle">
                                            @if ($control->terminal)
                                                {{ $control->terminal->user_name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($control->state == 5)
                                                <button class="btn btn-secondary" disabled>
                                                    Votado
                                                </button>
                                            @elseif ($control->state == 1)
                                                <button class="btn btn-success"
                                                    wire:click='updateTerminal({{ $control->id }})'>
                                                    Cambiar
                                                </button>
                                                <button class="btn btn-danger"
                                                    wire:click='releaseTerminal({{ $control->id }})'>
                                                    Quitar
                                                </button>
                                            @elseif ($control->state ==4)
                                                <button class="btn btn-primary"
                                                    wire:click='updateTerminal({{ $control->id }})'>
                                                    Actualizar
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Sin registros</td>
                                </tr>
                            @endforelse

                        </tbody>


                    </table>
                </div>

            </div>
        </div>

    </div>

    <div class="modal fade" tabindex="-1" id="modalIniciar">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Iniciar las votaciones
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Al iniciar las elecciones no podrá registrar más candidatos, asagurese de haberlos registrado todos
                </div>
                <div class="modal-footer justify-content-between align-items-center">

                    <button type="submit" class="btn btn-success " data-bs-dismiss="modal" wire:click='iniciar'>
                        Iniciar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="modalTerminar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Terminar las votaciones
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Asegurese de que las elecciones hayan finalizado, a partir de este punto no se puede modificar
                    información
                </div>
                <div class="modal-footer justify-content-between align-items-center">
                    <span class="badge m-0 text-bg-warning fs-6 ">Esta accion no se puede corregir</span>

                    <button type="submit" class="btn btn-warning " data-bs-dismiss="modal" wire:click='terminar'>
                        Terminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
