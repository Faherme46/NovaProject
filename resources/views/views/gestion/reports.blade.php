<div>
    <x-alerts />
    <div class="card mt-0 mx-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Contenido del informe</h4>
            <a class="btn btn-primary " href="{{ route('predio.export.controles') }}">Exportar CSV Predios</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-6">
                    <div class="card">
                        <form action="{{ route('asamblea.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_asamblea" value="{{ $asambleaa->id_asamblea }}">
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
                                        name="client_name" value="{{ $asambleaa->folder }}" disabled>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        Referencia:
                                    </span>
                                    <input type="text" class="form-control fs-5 " value="{{ $asambleaa->referencia }}"
                                        name="referencia">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        Tipo:
                                    </span>
                                    <select name="tipo" id="" class="form-control fs-5" required>
                                        <option value="Presencial" selected>Presencial</option>
                                        <option value="Virtual">Virtual</option>
                                        <option value="Mixta">Mixta</option>
                                    </select>
                                    <span class="input-group-text">
                                        Controles:
                                    </span>
                                    <input type="number" class="form-control fs-5 " value="{{ $asambleaa->controles }}"
                                        name="controles" oninput="debouncedValidateMultipleOf50(this)" max="400" required>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        Direccion:
                                    </span>
                                    <input type="text" class="form-control fs-5" value="{{ $asambleaa->lugar }}"
                                        name="lugar" required>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        Fecha
                                    </span>
                                    <input type="date" class="form-control fs-5" value="{{ $asambleaa->fecha }}"
                                        name="fecha" required>
                                    <span class="input-group-text">
                                        Hora:
                                    </span>
                                    <input type="time" class="form-control fs-5 " value="{{ $asambleaa->hora }}"
                                        name="hora" required>
                                </div>

                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        Hora Inicio
                                    </span>
                                    <input type="time" class="form-control fs-5 " value="{{ $asambleaa->h_inicio }}"
                                        name="h_inicio">

                                    <span class="input-group-text">
                                        Hora Cierre
                                    </span>
                                    <input type="time" class="form-control fs-5 " value="{{ $asambleaa->h_cierre }}"
                                        name="h_cierre">
                                </div>
                                @if($asambleaa->registro)
                                <div class="form-group">
                                    <input type="checkbox" class="btn-check" id="sign" name="signature"
                                        value="1" @checked($asambleaa->signature)>
                                    <label class="btn btn-outline-primary" for="sign">
                                        Firma electronica
                                    </label>
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>

                </div>
                @if ($asamblea['registro'])
                    <div class="col-3">
                        <div class="card px-0">
                            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                                <span>
                                <h5 class="card-title mb-0">Orden del dia</h5>
                                <small class="text-muted mb-0">(Sin números)</small>
                                </span>

                                <button type="button" class="btn btn-primary p-1 px-2" id="saveOrdenDia"
                                    wire:click='saveOrdenDia'  data-bs-toggle="tooltip" data-bs-title="Guardar">
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

                @if ($asamblea['registro'])
                <div class="col-3">
                @else
                <div class="col-4">
                @endif

                    <form action="{{ route('gestion.report.docs') }}" method="GET" class="px-3" id="formReport"
                        target="_blank">
                        @csrf
                        <div class="card">
                            <div class="card-body px-0">
                                @if ($questions->isEmpty())
                                    <div class="text-danger text-center mt-2">
                                        <small>No se han hecho votaciones</small>
                                    </div>
                                @elseif (!$allQuestionsVerified)
                                    <div class="text-danger text-center mt-2">
                                        <small>Hay votaciones sin resultado</small>
                                    </div>
                                @endif
                                @if (!$asambleaVerified)
                                    <div class="text-danger text-center mt-2">
                                        <small>Faltan campos en la asamblea</small>
                                    </div>
                                @endif

                                @if (cache('asamblea',[])['registro']&&!cache('prepared',false))
                                    <div class="text-danger text-center mt-2">
                                        <small>No se han preparado los predios</small>
                                    </div>
                                @endif
                                <div class="row justify-content-center px-4 mt-3">
                                    <button type="button" class="btn btn-success p-1" wire:click='setView(0)'
                                        @disabled($questions->isEmpty())>
                                        <div class="card ">
                                            <div
                                                class="card-body d-flex align-items-center p-1 justify-content-center">
                                                <i class="bi bi-card-checklist" style="font-size:40px"></i>
                                                <h5 class="card-title mb-0 ms-2">Ver Votaciones</h5>
                                            </div>
                                        </div>
                                    </button>
                                    @if (cache('asamblea',[])['registro']  )
                                    <button type="button" class="btn btn-warning p-1 mt-2"
                                    onclick=location.href='/predios/repare'; @disabled(!$asambleaVerified)
                                    data-bs-toggle="modal" data-bs-target="#cargandoModal">
                                        <div class="card ">
                                            <div
                                                class="card-body d-flex align-items-center p-1 justify-content-center">
                                                <i class="bi bi-file-earmark-binary" style="font-size:40px"></i>
                                                <h5 class="card-title mb-0 ms-2">Preparar Predios </h5>
                                            </div>
                                        </div>
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-info p-1 mt-2" @disabled(!$asambleaa || !$allQuestionsVerified || !$asambleaVerified  || !cache('prepared',false))
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
                                                <i class="bi bi-door-open" style="font-size:40px"></i>
                                                <h5 class="card-title mb-0 ms-2">Cerrar Asamblea</h5>
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

    @if ($asambleaa)
        <div class="modal fade" tabindex="-1" id="modalDeleteSession" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">¿Desea cerrar la asamblea actual?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Se descargará toda la informacion de la asamblea en las carpetas correspondientes
                        y se limpiaran completamente las tablas.
                    </div>
                    <div class="modal-footer justify-content-end align-items-center">

                        <form action="{{ route('session.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger " data-bs-toggle="modal" data-bs-target="#spinnerModal">
                                Cerrar Asamblea
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
                            @if (cache('report', false))
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
                                    <p class="mb-0">El informe se está generando en una nueva página.<br>
                                        Este proceso puede tardar unos minutos, por favor espere.</p>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            aria-label="Close" >Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    @endif

    <div class="modal fade" id="cargandoModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="spinner-grow text-primary" style="width: 4rem; height: 4rem;" role="status">

                    </div>
                    <span class="ms-3 " style="font-size: 5rem">Cargando...</span>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="spinnerModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="spinner-grow text-primary" style="width: 4rem; height: 4rem;" role="status">

                    </div>
                    <span class="ms-3 " style="font-size: 5rem">Cerrando ...</span>
                </div>

            </div>
        </div>
    </div>
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
