<div>
    <x-alerts />

    @php
        $colors = [
            1 => 'text-bg-primary', //activo
            2 => 'text-bg-info', //ausente
            3 => 'text-bg-warning', //retirado
            4 => 'text-bg-black', //sin asignar
            5 => 'text-bg-danger', //entregado
        ];
    @endphp
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ $asamblea['folder'] }}</h2>
                </div>
                <div class="card-body px-0 pt-0">


                    <table class="table table-bordered table-striped table-no-outer-borders">
                        <tbody>
                            <tr>
                                <td class="text-end">Fecha:</td>
                                <td>{{ $asamblea['fecha']}} </td>
                            </tr>
                            <tr>
                                <td class="text-end">Hora:</td>
                                <td class="text-start"> {{ $asamblea['hora'] }}</td>
                            </tr>
                            <tr>
                                <td class="text-end">Controles:</td>
                                <td class="text-start">{{ $asamblea['controles'] }}</td>
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
                <div class="card-footer d-flex align-items-center justify-content-center py-2">

                    <button type="button" class="btn btn-success fs-5 px-4  me-2" wire:click='iniciar'
                        @disabled($started || $finished)>
                        Iniciar
                    </button>

                    <button type="button" class="btn btn-warning fs-5 " wire:click='terminar'
                        @disabled(!$started || $finished)>
                        Terminar
                    </button>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0">Asignaciones</h2>
                </div>
                <div class="card-body table-responsive table-fixed-header table-h100 px-0">
                    <table class="table mb-0 ">
                        <thead>
                            <tr class="table-active">
                                <th class="text-center"><i class="bi bi-building bx"></i></th>
                                @if ($asamblea['registro'])
                                    <th>Asistente</th>
                                @endif
                                <th class="align-middle bx">Predios</th>
                                <th class="align-middle bx">Coef. Neto</th>
                                <th class="align-middle bx">Coef. Vota</th>
                                <th class="align-middle bx">Estado</th>
                            </tr>
                        </thead>

                        <tbody class="table-group-divider">
                            @forelse ($allControls as $control)

                                <tr>
                                    <td class="text-center">{{ $control->id }}</td>
                                    @if ($asamblea['registro'])
                                        <td>{{ $control->persona->nombre }} {{ $control->persona->apellido }}</td>
                                    @endif
                                    {{--  --}}
                                    </td>
                                    <td>
                                        @foreach ($control->predios as $predio)
                                            <p class="mb-0 @if (!$predio->vota) text-danger @endif">
                                                {{ $predio->getFullName() }}
                                            </p>
                                        @endforeach
                                    </td>
                                    <td>{{ $control->sum_coef }}</td>
                                    <td>{{ $control->sum_coef_can }}</td>
                                    <td>
                                        <span class="badge p-1 fs-6 {{ $colors[$control->state] }}">{{ $control->getStateTxt() }}</span>
                                    </td>
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
</div>
