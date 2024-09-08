<div>
    <x-alerts />
    <div class="card mt-0 mx-3">
        <div class="card-header">
            <h4 class="card-title mb-0">Contenido del informe</h4>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-6">
                    <div class="card">
                        <form action="{{ route('asamblea.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_asamblea" value="{{ $asamblea->id_asamblea }}">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    General
                                </h5>
                                <div class="">
                                    <button type="submit" class="btn btn-primary py-1">Guardar</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">
                                        Cliente
                                    </span>
                                    <input type="text" class="form-control fs-5 " placeholder="Cliente"
                                        name="client_name" value="{{ $asamblea->folder }}" disabled>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        Referencia:
                                    </span>
                                    <input type="text" class="form-control fs-5 " value="{{ $asamblea->referencia }}"
                                        name="referencia" required>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        Tipo:
                                    </span>
                                    <input type="text" class="form-control fs-5 " value="{{ $asamblea->tipo }}"
                                        name="tipo" required>
                                    <span class="input-group-text">
                                        Controles:
                                    </span>
                                    <input type="number" class="form-control fs-5 " value="{{ $asamblea->controles }}"
                                        name="controles" oninput="debouncedValidateMultipleOf50(this)" required>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        Direccion:
                                    </span>
                                    <input type="text" class="form-control fs-5" value="{{ $asamblea->lugar }}"
                                        name="lugar" required>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        Fecha
                                    </span>
                                    <input type="date" class="form-control fs-5" value="{{ $asamblea->fecha }}"
                                        name="fecha" required>
                                    <span class="input-group-text">
                                        Hora:
                                    </span>
                                    <input type="time" class="form-control fs-5 " value="{{ $asamblea->hora }}"
                                        name="hora" required>
                                </div>

                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        Hora Inicio
                                    </span>

                                    <input type="time" class="form-control fs-5 " value="{{ $asamblea->h_inicio }}"
                                        name="h_inicio" required>
                                    <span class="input-group-text">
                                        Hora Cierre
                                    </span>
                                    <input type="time" class="form-control fs-5 " value="{{ $asamblea->h_cierre }}"
                                        name="h_cierre" required>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                @if ($asamblea['registro'])
                    <div class="col-3">
                        <div class="card px-0">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Orden del dia</h5>
                                <button type="button" class="btn btn-primary p-1 px-2" id="saveOrdenDia"
                                    wire:click='saveOrdenDia'>
                                    <i class="bi bi-floppy-fill"></i>
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <input type="hidden" name="ordenDia" id="ordenDia" hidden>
                                <textarea class="form-control rounded-0 " style="min-height: 50vh" id="txtOrdenDia" wire:model='ordenDia'></textarea>
                            </div>
                        </div>

                    </div>
                @endif


                <div class="col-3">
                    <form action="{{route('gestion.report.docs')}}" method="GET" class="px-3" id="formReport"
                    target="_blank">
                        @csrf
                        <div class="card">
                            <div class="card-body px-0">
                                @if ($this->questions->isEmpty())
                                    <div class="text-danger text-center mt-2">
                                        <small>No se han hecho votaciones</small>
                                    </div>
                                @endif
                                @if (!$allQuestionsVerified)
                                    <div class="text-danger text-center mt-2">
                                        <small>Hay votaciones sin resultado</small>
                                    </div>
                                @endif

                                {{-- @if (!$ordenDia)
                                    <div class="text-danger text-center mt-2">
                                        <small>No se ha guardado Orden del Dia</small>
                                    </div>
                                @endif --}}
                                <div class="row justify-content-center px-4 mt-3">
                                    <button type="button" class="btn btn-success p-1" wire:click='setView(0)'
                                        @disabled($this->questions->isEmpty())>
                                        <div class="card ">
                                            <div
                                                class="card-body d-flex align-items-center p-1 justify-content-center">
                                                <i class="bi bi-card-checklist" style="font-size:40px"></i>
                                                <h5 class="card-title mb-0 ms-2">Ver Votaciones</h5>
                                            </div>
                                        </div>
                                    </button>
                                    <button type="button" class="btn btn-info p-1 mt-2" @disabled(!$asamblea || !$allQuestionsVerified)
                                    wire:click='verifyForm'>
                                        <div class="card ">
                                            <div
                                                class="card-body d-flex align-items-center p-1 justify-content-center">
                                                <i class="bi bi-file-earmark-richtext" style="font-size:40px"></i>
                                                <h5 class="card-title mb-0 ms-2">Crear informe </h5>
                                            </div>
                                        </div>
                                    </button>
                                    <button type="button" class="btn btn-danger p-1 mt-2" data-bs-toggle=modal
                                        data-bs-target=#modalDeleteSession {{--@disabled(!$asamblea || !$report)--}}>
                                        <div class="card ">
                                            <div
                                                class="card-body d-flex align-items-center p-1 justify-content-center">
                                                <i class="bi bi-trash" style="font-size:40px"></i>
                                                <h5 class="card-title mb-0 ms-2">Eliminar sesión</h5>
                                            </div>
                                        </div>
                                    </button>


                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @if ($asamblea)
        <div class="modal fade" tabindex="-1" id="modalDeleteSession" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">¿Desea eliminar la sesión?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Se descargará toda la informacion de la asamblea en las carpetas correspondientes
                        y se limpiaran completamente las tablas.
                    </div>
                    <div class="modal-footer justify-content-between align-items-center">
                        <span class="badge m-0 text-bg-warning fs-6 ">Esta accion no se puede deshacer</span>
                        <form action="{{ route('session.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger ">
                                Eliminar sesion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="loadingModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex justify-content-center align-items-center">
                            @if(cache('report',false))
                                <h2>
                                    <span class="badge text-bg-success"><i class="bi bi-check2-square"></i></span>
                                    El informe se ha generado Correctamente
                                </h2>
                            @else
                                <div class="spinner-border text-primary me-2" role="status"
                                    style="{width: 6rem;height: 6rem;border-width: 0.6  em;}">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <div>
                                    <p class="mb-0">El informe se está generando.<br>
                                        Este proceso puede tardar unos minutos, por favor espere.</p>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

</div>
@script
    <script>
        $wire.on('submit-form-report', () => {
            document.getElementById('formReport').submit();
            $('#loadingModal').modal('hide');
            $('#loadingModal').modal('show');
        });
    </script>
@endscript
