<div class="row px-3">
    <div class="col-4 ">
        <div class="row justify-content-center">

            <button class="btn btn-danger p-0 my-1 w-45 "  data-bs-toggle=modal data-bs-target=#modalDeleteSession
                @disabled(!$asambleaOn || !$report)>
                <div class="card ">
                    <div class="card-body d-flex align-items-center p-1">
                        <i class="bi bi-trash" style="font-size:40px"></i>

                        <h5 class="card-title mb-0 ms-2">Eliminar sesión</h5>
                    </div>
                </div>
            </button>

            <button class="btn btn-info p-0 my-1 w-45 ms-2" data-bs-toggle=modal data-bs-target=#modalCreateReport
                @disabled(!$asambleaOn || $report)>
                <div class="card ">
                    <div class="card-body d-flex align-items-center p-1">
                        <i class="bi bi-file-earmark-richtext" style="font-size:40px"></i>
                        <h5 class="card-title mb-0 ms-2">Crear informe  </h5>
                    </div>
                </div>
            </button>

        </div>
        <div class="row ">
            <div class="card px-0">
                <div class="card-header">
                    <h4 class="card-title mb-0">Informacion de asamblea</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-no-outer-borders mb-0">
                        <tbody>
                            <tr>
                                <td class="text-end">Lugar</td>
                                <td>
                                    {{ $asambleaOn->folder }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-end">Fecha:</td>
                                <td>{{ $asambleaOn->fecha }} </td>
                            </tr>
                            <tr>
                                <td class="text-end">Hora:</td>
                                <td class="text-start"> {{ $asambleaOn->hora }}</td>
                            </tr>
                            <tr>
                                <td class="text-end">Controles:</td>
                                <td class="text-start">{{ $asambleaOn->controles }}</td>
                            </tr>
                            <tr>
                                <td class="text-end">Controles registrados:</td>
                                <td class="text-start">{{ $allControls->count() }}</td>
                            </tr>
                            <tr>
                                <td class="text-end">Predios registrados:</td>
                                <td class="text-start">{{ $prediosRegistered }}</td>
                            </tr>
                            <tr>
                                <td class="text-end">Predios Habilitados:</td>
                                <td class="text-start">{{ $prediosVote }}</td>
                            </tr>
                            <tr>
                                <td class="text-end">Predios Inhabilitados:</td>
                                <td class="text-start">{{ $prediosRegistered - $prediosVote }}</td>
                            </tr>
                            <tr>
                                <td class="text-end">Quorum Presente:</td>
                                <td class="text-start">{{ $quorumRegistered }}</td>
                            </tr>
                            <tr>
                                <td class="text-end">Quorum Habilitado:</td>
                                <td class="text-start">{{ $quorumVote }}</td>
                            </tr>
                            <!-- Añade más filas según sea necesario -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Contenido del informe</h4>
            </div>
            <div class="card-body"></div>


        </div>
    </div>
    @if ($asambleaOn)
        <div class="modal fade" tabindex="-1" id="modalDeleteSession" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">¿Desea eliminar la sesión?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        <div class="modal fade" tabindex="-1" id="modalCreateReport" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crear el informe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Se generara un documento pdf con los parametros indicados, con toda la información relevante.
                    </div>
                    <div class="modal-footer justify-content-end align-items-center">


                            <button type="submit" class="btn btn-success ">
                                Generar informe
                            </button>

                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
