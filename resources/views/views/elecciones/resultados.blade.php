<div class="">
    <x-alerts class="pt-3"></x-alerts>

    <div class="row mt-4">
        <div class="col-2">
            <div class="card mb-2">
                <div class="card-body justify-content-center d-flex">
                    <form action="{{ route('elecciones.resultados.grafica') }}" method="get" class="text-center">
                        @csrf
                        <button type="submit" class="btn btn-warning w-80" data-bs-toggle="modal"
                            data-bs-target="#spinnerModal">
                            Generar Gráficas
                        </button>

                    </form>
                </div>
            </div>
            <table class="list-group pe-0 border mt-23 " style="max-height: 80vh; overflow-y: auto;">
                <tbody>
                    @foreach ($torres as $t)
                        <tr class="list-group-item list-group-item-action fs-6 @if ($t->id == $torre->id) active @endif"
                            wire:click='setTorre({{ $t->id }})'>
                            <td class="text-end me-2 border-right">{{ $t->id }}. </td>
                            <td> {{ $t->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-6">


            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0 card-title">
                        {{ $torre->name }}
                    </h4>
                </div>
                <div class="card-body p-0">

                    <div class="table-responsive table-fixed-header  table-70 p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-active">
                                <tr>
                                    <th class="fs-5 mb-0">ID</th>
                                    <th class="fs-5 mb-0">NOMBRE</th>
                                    <th class="fs-5 mb-0">COEFICIENTE</th>
                                    <th class="fs-5 mb-0">VOTOS</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($torre->candidatos as $c)
                                    <tr>
                                        <td>
                                            {{ $c->id }}
                                        </td>
                                        <td>
                                            {{ $c->fullName() }}
                                        </td>
                                        <td class="text-end">
                                            {{ $c->pivot->coeficiente ? $c->pivot->coeficiente : 0 }}
                                        </td>
                                        <td class="text-end">
                                            {{ $c->pivot->votos ? $c->pivot->votos : 0 }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        VOTO EN BLANCO
                                    </td>
                                    <td class="text-end">
                                        {{ $torre->coeficienteBlanco }}
                                    </td>
                                    <td class="text-end">
                                        {{ $torre->votosBlanco }}
                                    </td>

                                </tr>


                            </tbody>
                            <tfoot class="table-active border-top border-2 ">
                                <th></th>
                                <th class="text-end fs-5">TOTAL</th>
                                <th class="text-end fs-5">
                                    <strong>{{ $torre->coeficienteBlanco + $torre->candidatos->sum('pivot.coeficiente') }}</strong>
                                </th>
                                <th class="text-end fs-5">
                                    <strong>{{ $torre->votosBlanco + $torre->candidatos->sum('pivot.votos') }}</strong>
                                </th>


                            </tfoot>

                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-4">
            @if (cache('graficas', false) && $torre)
                <div class="card">
                    <div class="row mt-2 text-center">
                        <h6>COEFICIENTE</h6>
                        <a target="_blank"
                            href="/storage/images/results/{{ cache('asamblea', [])['name'] . '/' . $torre->id . '/coefChart.png' }}">
                            <img src="/storage/images/results/{{ cache('asamblea', [])['name'] . '/' . $torre->id . '/coefChart.png' }}"
                                alt="No se encontro imagen" style="max-width: 100%">
                        </a>
                    </div>
                    <div class="row mt-2 text-center">
                        <h6>NOMINAL</h6>
                        <a target="_blank"
                            href="/storage/images/results/{{ cache('asamblea', [])['name'] . '/' . $torre->id . '/nominalChart.png' }}">
                        <img src="/storage/images/results/{{ cache('asamblea', [])['name'] . '/' . $torre->id . '/nominalChart.png' }}"
                            alt="No se encontro imagen" style="max-width: 100%">
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="spinnerModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="spinner-grow text-primary" style="width: 4rem; height: 4rem;" role="status">

                    </div>
                    <span class="ms-3 " style="font-size: 5rem">Cargando Resultados...</span>
                </div>

            </div>
        </div>
    </div>
</div>
