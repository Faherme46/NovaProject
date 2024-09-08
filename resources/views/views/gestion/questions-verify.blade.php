<div class="">
    <x-alerts />
    <div class="card mx-3 px-0 " style="min-height: 80vh">
        <div class="card-header">
            <h5 class="card-title mb-0 " id="scrollspyVotacion">Votaciones</h5>
        </div>
        <div class="card-body px-3 row">
            @if (!$questions->isEmpty())
                <div class="col-4">
                    <div class="row  justify-content-center">
                        <button type="button" class="btn btn-success p-0 my-1 w-45 " wire:click='setView(1  )'>
                            <div class="card ">
                                <div class="card-body d-flex align-items-center p-1 justify-content-center">
                                    <i class="bi bi-arrow-bar-left" style="font-size:40px"></i>
                                    <h5 class="card-title mb-0 ms-2">Volver</h5>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div class="list-group pe-0 border mt-3" style="max-height: 50vh; overflow-y: auto;">

                        @foreach ($questions as $q)
                            <button type="button"
                                class="list-group-item list-group-item-action @if ($q->id == $question->id) active @endif"
                                wire:click='selectQuestion({{ $q->id }})'>
                                {{ $q->title }}
                            </button>
                        @endforeach
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
                            @if ($question->type != 1)
                                <form wire:submit='setResult' class="mb-2" id="resultForm">
                                    @csrf
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <input type="hidden" name="question_id" value="{{ $question->id }}"
                                                id="resultId">
                                            <input type="text" class="form-control " placeholder="Resultado"
                                                wire:model='questionResultTxt' name="result" id="resultValue">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="btn-group" role="group">
                                                <input type="radio" class="btn-check" name="radioCoef" value="0"
                                                    wire:model='questionIsCoefChart' id="radioNom">

                                                <label class="btn btn-outline-primary" for="radioNom">Nominal</label>

                                                <input type="radio" class="btn-check" name="radioCoef" value="1"
                                                    wire:model='questionIsCoefChart' id="radioCoef">
                                                <label class="btn btn-outline-primary"
                                                    for="radioCoef">Coeficiente</label>
                                            </div>
                                        </div>
                                        <div class="col-4 d-flex align-items-center">
                                            <div class="form-check form-check-reverse form-switch">
                                                <input class="form-check-input scaled-switch-15 " type="checkbox"
                                                    wire:model='questionIsValid' id="reverseCheck1">
                                                <label class="form-check-label fs-5" for="reverseCheck1">
                                                    Mostrar en el informe
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-2 offset-3 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-success"
                                                wire:click='setQuestionsVerified'>
                                                Guardar
                                            </button>
                                        </div>
                                    </div>


                                </form>
                            @endif

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
                                                        {{ $question->resultNom ? $question->resultNom[$op] : 'Sin resultado' }}
                                                    </td>
                                                    <td>
                                                        {{ $question->resultCoef ? $question->resultCoef[$op] : 'Sin resultado' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        <tr>
                                            <td>
                                                {{ 'Abstencion' }}
                                            </td>
                                            <td>
                                                {{ $question->resultNom ? $question->resultNom['abstainted'] : 'Sin resultado' }}
                                            </td>
                                            <td>
                                                {{ $question->resultCoef ? $question->resultCoef['abstainted'] : 'Sin resultado' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ 'Ausente' }}
                                            </td>
                                            <td>
                                                {{ $question->resultNom ? $question->resultNom['absent'] : 'Sin resultado' }}
                                            </td>
                                            <td>
                                                {{ $question->resultCoef ? $question->resultCoef['absent'] : 'Sin resultado' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ 'Nulos' }}
                                            </td>
                                            <td>
                                                {{ $question->resultNom ? $question->resultNom['nule'] : 'Sin resultado' }}
                                            </td>
                                            <td>
                                                {{ $question->resultCoef ? $question->resultCoef['nule'] : 'Sin resultado' }}
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>
                            </div>
                            <div class="row">
                                @if ($question->resultCoef && $question->resultNom)
                                    <div class="col-6 text-center">

                                        <a target="_blank" class="btn btn-outline-primary "
                                            href="/storage/images/results/{{ $question->resultCoef->chartPath }}">
                                            <span>
                                                <h5 class="text-center mb-0">
                                                    <span><i class="bi bi-file-image"></i></span> Gráfico Coeficiente
                                                </h5>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="col-6 text-center">

                                        <a target="_blank" class="btn btn-outline-primary "
                                            href="/storage/images/results/{{ $question->resultNom->chartPath }}">
                                            <span>
                                                <h5 class="text-center mb-0">
                                                    <span><i class="bi bi-file-image"></i></span> Gráfico Nominal
                                                </h5>
                                            </span>
                                        </a>
                                    </div>
                                @endif
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
@script
    <script></script>
@endscript
