<div>
    <x-alerts />
    <div class="row mt-4">


        @if (!$questions->isEmpty())
            <div class="col-4">

                <table class="list-group pe-0 border " style="max-height: 70vh; overflow-y: auto;">

                    @foreach ($questions as $key => $q)
                        <tr class="list-group-item px-2 list-group-item-action d-flex  @if ($q->id == $question->id) active @endif"
                            wire:click='selectQuestion({{ $q->id }})'>
                            <td class="text-end me-2">{{ $key + 1 }}</td>
                            <td class="ps-2 lines-text-4">{{ $q->title }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-end px-2">
                        <div class="col-9 align-items-center ">
                            <h6 class="card-title text-center mb-0 lines-text-4"
                                style="font-size: {{ $sizeTitle }}rem;">
                                {{ $question->title }}
                            </h6>
                        </div>
                        <div class="col-3 d-flex justify-content-end ">

                            @hasanyrole('Admin')
                            <div class="justify-content-end align-items-center d-flex">
                                <button type="button" class="btn btn-primary px-1" data-bs-toggle="modal"
                                    data-bs-target="#modalImport">
                                    Importar <br>Votos
                                </button>
                            </div>
                            <div class="justify-content-end ms-1 align-items-center d-flex">
                                <button type="button" class="btn btn-info px-1" data-bs-toggle="modal"
                                    data-bs-target="#modalChart">
                                    Generar <br> Gráfica
                                </button>
                            </div>
                            @if ($question->plancha)
                                <div class="justify-content-end ms-1 align-items-center d-flex">
                                    <button type="button" class="btn btn-warning px-1" data-bs-toggle="modal"
                                        data-bs-target="#modalPlancha">
                                        Ver <br> Plancha
                                    </button>
                                </div>
                            @endif
                        @endhasanyrole
                        </div>
                    </div>
                    <div class="card-body p-0 ">
                        <div class=" d-flex justify-content-center">
                            <div class="btn-group my-1" role="group">
                                <input type="radio" class="btn-check" wire:model='inCoefResult' value="0"
                                    wire:change='$refresh' id="btnradio1">
                                <label class="btn btn-outline-primary" for="btnradio1">Nominal</label>

                                <input type="radio" class="btn-check" wire:model='inCoefResult' value="1"
                                    wire:change='$refresh' id="btnradio2" checked>
                                <label class="btn btn-outline-primary" for="btnradio2">Coeficiente</label>
                            </div>
                        </div>
                        @if ($question->resultCoef)
                            @if ($inCoefResult)
                                <a target="_blank"
                                    href="/storage/images/results/{{ $question->resultCoef->chartPath }}">
                                    <img src="/storage/images/results/{{ $question->resultCoef->chartPath }}"
                                        alt="No se encontro imagen" style="max-width: 100%">
                                </a>
                            @else
                                <a target="_blank" href="/storage/images/results/{{ $question->resultNom->chartPath }}">
                                    <img src="/storage/images/results/{{ $question->resultNom->chartPath }}"
                                        alt="No se encontro imagen" style="max-width: 100%">
                                </a>
                            @endif
                        @else
                            <div class="d-flex mt-2 justify-content-center">
                                <h1 class="text-center">No hubo resultados</h1>
                            </div>
                        @endif


                    </div>
                </div>
            </div>
        @else
            <h4>
                No se realizaron votaciones
            </h4>
        @endif
    </div>



    @if ($question)
        <div class="modal fade" id="modalImport" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5 " id="modalTitleId">
                            ¿Importar los votos y generar la gráfica para la pregunta {{ $question->id }} ?
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <form action="{{ route('question.import') }}" method="post">
                            @csrf
                            <input type="hidden" name="idQuestion" value="{{ $question->id }}">
                            <button type="submit" class="btn btn-primary">Importar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalChart" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5 " id="modalTitleId">
                            Generar la gráfica con los valores de resultado para la pregunta {{ $question->id }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <form action="{{ route('question.createChart') }}" method="post">
                            @csrf
                            <input type="hidden" name="idQuestion" value="{{ $question->id }}">
                            <button type="submit" class="btn btn-info">Continuar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if ($question && $question->plancha && $resultToUse)
                <div class="modal fade" id="modalPlancha" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
                role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fs-5 lines-text-4 " id="modalTitleId">
                                {{ $question->title }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                    
                        <div class="modal-body p-0">
                            <table class="table table-bordered border-black mb-0">
                                <tbody>
            
                                    <tr class="">
                                        <td colspan="4" class="text-center">
                                            <h2 class="mb-0">COCIENTE ELECTORAL: {{$question->plancha['umbral']}}</h2>
                                        </td>
                                    </tr>
                                    <tr class="table-active">
                                        <td class="text-center" colspan="2">
                                            <h1 class="mb-0  "> LISTAS</h1>
                                        </td>
                                        <td class="col-1 px-2">
                                            <h1 class="mb-0">VOTOS</h1>
                                        </td>
                                        <td class="col-1 px-2">
                                            <h1 class="mb-0">PLAZAS</h1>
                                        </td>
                                    </tr>
                                    @foreach (['A','B','C','D','E','F'] as $op)
                                        @if ($question['option' . $op] && $question['option' . $op]!=='EN BLANCO')
                                            <tr class=" p-0">
                                                <td class="bg-primary text-light text-center">
                                                    <h1 class="mb-0  ">
                                                        {{ $op }}
                                                    </h1>
                                                </td>
                                                <td class="text-center">
                                                    <h1 class="text-uppercase lines-text-2 mb-0 ">
                                                        {{ $question['option' . $op] }}
                                                    </h1>
                                                </td>
                                                <td class="text-center">
                                                    
                                                    <h1 class="mb-0  ">{{ $resultToUse['option' . $op] }}</h1>
                                                </td>
                                                <td class="text-center">
                                                    <h1 class="mb-0  ">{{ $question->plancha['option' . $op] }}</h1>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
            
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <th colspan="2" class="text-end bold">
                                            <h1 class="mb-0">TOTAL</h1>
                                        </th>
                                        <th class="text-center">
                                            <h1 class="mb-0">
                                                {{$resultToUse['total']-$resultToUse['absent']-$resultToUse['abstainted']-$resultToUse['nule']}} 
                                            </h1>
                                        </th>
                                        <th class="text-center">
                                            <h1 class="mb-0">
                                                {{ $question->plancha->plazas }}
                                            </h1>
                                        </th>
                                    </tr>
                                </tfoot>
            
                            </table>
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-primary fs-5"  wire:click='calculatePlazas'>
                                Volver a Calcular
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
    @endif

</div>
<!-- Optional: Place to the bottom of scripts -->
<script>
    const myModal = new bootstrap.Modal(
        document.getElementById("modalId"),
        options,
    );
</script>
