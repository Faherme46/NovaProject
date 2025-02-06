<div>
    <x-alerts />
    <div class="row mt-4">


        @if (!$questions->isEmpty())
            <div class="col-4">

                <table class="list-group pe-0 border " style="max-height: 70vh; overflow-y: auto;">

                    @foreach ($questions as $key=>$q)
                        <tr class="list-group-item px-2 list-group-item-action lines-text-2 @if ($q->id == $question->id) active @endif"
                            wire:click='selectQuestion({{ $q->id }})'>
                            <td class="text-end me-2">{{$key+1}}</td>
                            <td class="ps-2">{{ $q->title }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header d-flex">
                        <div class="col-10">
                            <h6 class="card-title text-center mb-0 lines-text-4" style="font-size: {{ $sizeTitle }}rem;">
                                {{ $question->title }}
                            </h6>
                        </div>
                        <div class="col-2 justify-content-end d-flex">
                            <button type="button" class="btn btn-primary" wire:click='importarVotos({{$q->id}})'>
                                Importar <br>Votos
                            </button>
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
</div>
