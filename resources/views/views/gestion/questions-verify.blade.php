<div class="" >
    <x-alerts/>
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
                                <div class="row mb-2">
                                    <form wire:submit='setResult' class="row mb-2" id="resultForm">
                                        @csrf
                                        <div class="col-9">
                                            <input type="hidden" name="question_id" value="{{ $question->id }}"
                                                id="resultId">
                                            <input type="text" class="form-control " placeholder="Resultado"
                                                wire:model='questionResultTxt' name="result" id="resultValue">
                                        </div>
                                        <div class="col-2">
                                            <button type="submit" class="btn btn-primary"
                                                wire:click='setQuestionsVerified'>
                                                Guardar
                                            </button>
                                        </div>

                                    </form>
                                </div>
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
                                <div class="col-6 text-center">

                                    <a target="_blank" class="btn btn-outline-primary "
                                        href="/storage/images/results/{{ $question->resultCoef->chartPath }}">
                                        <span>
                                            <h5 class="text-center mb-0">
                                              <span><i class="bi bi-file-image"></i></span>  Gráfico Coeficiente
                                            </h5>
                                        </span>
                                    </a>
                                </div>
                                <div class="col-6 text-center">

                                    <a target="_blank" class="btn btn-outline-primary "
                                        href="/storage/images/results/{{ $question->resultNom->chartPath }}">
                                        <span>
                                            <h5 class="text-center mb-0">
                                              <span><i class="bi bi-file-image"></i></span>  Gráfico Nominal
                                            </h5>
                                        </span>
                                    </a>
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
@script
    <script>
        
    </script>
@endscript
