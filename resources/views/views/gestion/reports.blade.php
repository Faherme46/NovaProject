<div class="px-3 pb-3">
    <div class="card mt-0">
        <div class="card-header">
            <h4 class="card-title mb-0">Contenido del informe</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('gestion.report.docs') }}" method="post" class="row g-3">
                @csrf
                <div class="col-8 ">

                    <div class="card">

                        <div class="card-body">
                            <div class="row g-2">


                                <div class="col-6">
                                    <div class="input-group ">
                                        <span class="input-group-text" id="basic-addon1">
                                            Cliente
                                        </span>
                                        <input type="text" class="form-control" placeholder="Cliente" name="client_name"
                                            wire:model='values.cliente'>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group ">
                                        <span class="input-group-text">
                                            Referencia
                                        </span>
                                        <input type="text" class="form-control" wire:model='values.referencia'
                                        name="reference">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group ">
                                        <span class="input-group-text">
                                            Tipo
                                        </span>
                                        <input type="text" class="form-control" wire:model='values.tipo'
                                        name="type">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="input-group ">
                                        <span class="input-group-text">
                                            Direccion
                                        </span>
                                        <input type="text" class="form-control" wire:model='values.direccion'
                                        name="address">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group ">
                                        <span class="input-group-text">
                                            Fecha
                                        </span>
                                        <input type="text" class="form-control" placeholder="--/--/----"
                                            wire:model='values.fecha' name="date">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group ">
                                        <span class="input-group-text">
                                            Hora inicio
                                        </span>
                                        <input type="text" class="form-control" placeholder="00:00" value="10:00"
                                            wire:model='values.h_inicio' name="h_start">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group ">
                                        <span class="input-group-text">
                                            Hora Fin
                                        </span>
                                        <input type="text" class="form-control" placeholder="00:00" value="10:00"
                                            wire:model='values.h_fin' name="h_end">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2 g-2">
                        {{-- <div class="col-5">
                            <div class="card px-0 ">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Estadísticas de asamblea</h4>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-bordered table-striped table-no-outer-borders mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="text-end">Controles registrados:</td>
                                                <td class="text-start">{{ $allControls->count() }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-end">Predios Citados:</td>
                                                <td class="text-start">{{ $prediosRegistered }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-end">Predios Asistentes:</td>
                                                <td class="text-start">{{ $prediosRegistered }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-end">Quorum Inicial:</td>
                                                <td class="text-start">{{ $quorumRegistered }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-end">Quorum Final:</td>
                                                <td class="text-start">{{ $quorumVote }}</td>
                                            </tr>
                                            <!-- Añade más filas según sea necesario -->
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div> --}}
                        @if ($asambleaOn->registro)
                            <div class="col-7">
                                <div class="card px-0">
                                    <div class="card-header justify-content-between d-flex">
                                        <h6 class="card-title mb-0">Anexos</h6>

                                    </div>
                                    <div class="card-body px-2">
                                        <div id="input-group-container">
                                            @for ($i = 1; $i <= count($anexos); $i++)
                                                <div class="input-group me-2">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        Anexo {{ $i }}
                                                    </span>
                                                    <input type="text" class="form-control px-2" name="anexos[]"
                                                        value="{{ $anexos[$i - 1] }}">

                                                </div>
                                            @endfor


                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-5">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        Otros
                                    </h6>
                                </div>
                                <div class="card-body">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($asambleaOn->registro)
                    <div class="col-4">
                        <div class="card px-0">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Orden del dia</h5>
                            </div>
                            <div class="card-body p-0">
                                <textarea class="form-control rounded-0 " wire:model='values.orden'
                                style="min-height: 50vh"></textarea>
                            </div>
                        </div>

                    </div>
                @endif





                <div class="row mt-3" style="min-height: 80vh">
                    <div class="card px-0 ">
                        <div class="card-header">
                            <h5 class="card-title mb-0 " id="scrollspyVotacion">Votaciones</h5>
                        </div>
                        <div class="card-body">
                            @if (!$questions->isEmpty())
                                <div class="row">
                                    <div class="col-4">
                                        <div class="row px-0">
                                            <div class="list-group pe-0 border"
                                                style="max-height: 50vh; overflow-y: auto;">

                                                @foreach ($questions as $q)
                                                    <button type="button"
                                                        class="list-group-item list-group-item-action
                                                         @if ($q->id == $question->id) active @endif"
                                                        wire:click='selectQuestion({{ $q->id }})'>
                                                        {{ $q->title }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="row mt-3 justify-content-center">


                                            <button type="button" class="btn btn-danger p-0 my-1 w-45 "
                                                data-bs-toggle=modal data-bs-target=#modalDeleteSession
                                                {{-- @disabled(!$asambleaOn || !$report) --}}>
                                                <div class="card ">
                                                    <div class="card-body d-flex align-items-center p-1">
                                                        <i class="bi bi-trash" style="font-size:40px"></i>

                                                        <h5 class="card-title mb-0 ms-2">Eliminar sesión</h5>
                                                    </div>
                                                </div>
                                            </button>

                                            <button type="submit" class="btn btn-info p-0 my-1 w-45 ms-2"
                                                @disabled(!$asambleaOn || $report)>
                                                <div class="card ">
                                                    <div class="card-body d-flex align-items-center p-1">
                                                        <i class="bi bi-file-earmark-richtext"
                                                            style="font-size:40px"></i>
                                                        <h5 class="card-title mb-0 ms-2">Crear informe </h5>
                                                    </div>
                                                </div>
                                            </button>



                                        </div>


                                    </div>
                                    <div class="col-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    {{ $question->title }}
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row px-2">


                                                    <table class="table table-bordered">
                                                        <thead class="table-active">
                                                            <tr>
                                                                <th>Descripcion</th>
                                                                <th>Votos</th>
                                                                <th>Coeficiente</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $options = [
                                                                    'optionA',
                                                                    'optionB',
                                                                    'optionC',
                                                                    'optionD',
                                                                    'optionE',
                                                                    'optionF',
                                                                ];

                                                            @endphp
                                                            @foreach ($options as $op)
                                                                @if ($question[$op])
                                                                    <tr>
                                                                        <td>
                                                                            {{ $question[$op] }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $question->resultNom[$op] }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $question->resultCoef[$op] }}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach

                                                            <tr>
                                                                <td>
                                                                    {{ 'Abstencion' }}
                                                                </td>
                                                                <td>
                                                                    {{ $question->resultNom['abstainted'] }}
                                                                </td>
                                                                <td>
                                                                    {{ $question->resultCoef['abstainted'] }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    {{ 'Ausente' }}
                                                                </td>
                                                                <td>
                                                                    {{ $question->resultNom['absent'] }}
                                                                </td>
                                                                <td>
                                                                    {{ $question->resultCoef['absent'] }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    {{ 'Nulos' }}
                                                                </td>
                                                                <td>
                                                                    {{ $question->resultNom['nule'] }}
                                                                </td>
                                                                <td>
                                                                    {{ $question->resultCoef['nule'] }}
                                                                </td>
                                                            </tr>

                                                        </tbody>

                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h6 class="text-center">
                                                            Coeficiente
                                                        </h6>
                                                        <a
                                                            href="/storage/images/results/{{ $question->resultCoef->chartPath }}">
                                                            <img src="/storage/images/results/{{ $question->resultCoef->chartPath }}"
                                                                class="w-100 h-auto">
                                                        </a>
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="text-center">
                                                            Nominal
                                                        </h6>
                                                        <a
                                                            href="/storage/images/results/{{ $question->resultNom->chartPath }}">
                                                            <img src="/storage/images/results/{{ $question->resultNom->chartPath }}"
                                                                class="w-100 h-auto">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <h4>
                                    No se realizaron votaciones
                                </h4>
                            @endif



                        </div>
                    </div>
                </div>


            </form>

        </div>
    </div>






    @if ($asambleaOn)
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
    @endif

</div>
