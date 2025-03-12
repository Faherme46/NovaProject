<div>
    <x-alerts />
    <div class="row g-3">
        <div class="col-7">
            <div class="card">
                <form action="{{ route('elecciones.update') }}" method="POST">
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
                            <input type="text" class="form-control fs-5 " placeholder="Cliente" name="client_name"
                                value="{{ $asambleaa->folder }}" disabled>
                        </div>
                        <div class="row g-2">
                            <div class="input-group col mb-2">
                                <span class="input-group-text">
                                    Referencia:
                                </span>
                                <input type="text" class="form-control fs-5 " value="{{ $asambleaa->referencia }}"
                                    name="referencia">
                            </div>
                            <div class="input-group col mb-2">
                                <span class="input-group-text">
                                    Tipo:
                                </span>
                                <select name="tipo" id="" class="form-control fs-5" required>
                                    <option value="Presencial" selected>Presencial</option>
                                    <option value="Virtual">Virtual</option>
                                    <option value="Mixta">Mixta</option>
                                </select>

                            </div>
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
                    </div>
                </form>
            </div>

        </div>
        <div class="col-4">



            <div class="card">
                <div class="card-body px-0">

                    @if (!$asambleaVerified)
                        <div class="text-danger text-center mt-2">
                            <small>Faltan campos en la asamblea</small>
                        </div>
                    @endif

                    {{-- @if (!$ordenDia)
                            <div class="text-danger text-center mt-2">
                                <small>No se ha guardado Orden del Dia</small>
                            </div>
                        @endif --}}
                    <div class="row justify-content-center px-4 mt-3">
                        <button type="button" class="btn btn-warning p-1 mt-2" onclick=location.href='/predios/repare';
                            @disabled(!$asambleaVerified) data-bs-toggle="modal" data-bs-target="#cargandoModal">
                            <div class="card ">
                                <div class="card-body d-flex align-items-center p-1 justify-content-center">
                                    <i class="bi bi-file-earmark-binary" style="font-size:40px"></i>
                                    <h5 class="card-title mb-0 ms-2">Preparar Predios </h5>
                                </div>
                            </div>
                        </button>
                        <form action="{{ route('elecciones.report.create') }}" method="GET" id="formReport"
                            target="_blank" class="d-flex w-100 p-0">
                            @csrf
                            <button type="submit" class="btn btn-info p-1 mt-2 w-100" @disabled(!$asambleaVerified && cache('prepared',false))>
                                <div class="card ">
                                    <div class="card-body d-flex align-items-center p-1 justify-content-center">
                                        <i class="bi bi-file-earmark-richtext" style="font-size:40px"></i>
                                        <h5 class="card-title mb-0 ms-2">Crear informe </h5>
                                    </div>
                                </div>
                            </button>

                        </form>
                        <button type="button" class="btn btn-danger p-1 mt-2" data-bs-toggle=modal
                            data-bs-target=#modalDeleteSession {{-- @disabled(!$asamblea || !$report) --}}>
                            <div class="card ">
                                <div class="card-body d-flex align-items-center p-1 justify-content-center">
                                    <i class="bi bi-door-open" style="font-size:40px"></i>
                                    <h5 class="card-title mb-0 ms-2">Cerrar Asamblea</h5>
                                </div>
                            </div>
                        </button>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="closeModal" tabindex="-1">
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

    <div class="modal fade" tabindex="-1" id="modalDeleteSession" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">¿Desea cerrar la asamblea actual?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Se descargará toda la informacion de la asamblea en las carpetas correspondientes
                    y se limpiaran completamente las tablas.
                </div>
                <div class="modal-footer justify-content-end align-items-center">

                    <form action="{{ route('session.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger " data-bs-toggle="modal"
                            data-bs-target="#closeModal">
                            Cerrar Asamblea
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
