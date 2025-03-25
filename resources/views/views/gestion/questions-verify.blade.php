<div style="min-height: 80vh">
    <x-alerts />
    <div class="row mt-4">


        @if (!$questions->isEmpty())
            <div class="col-4">
                <div class="row  justify-content-center">
                    <button type="button" class="btn btn-success p-0 my-1 w-45 " wire:click='setView(1)'>
                        <div class="card ">
                            <div class="card-body d-flex align-items-center p-1 justify-content-center">
                                <i class="bi bi-arrow-bar-left" style="font-size:40px"></i>
                                <h5 class="card-title mb-0 ms-2">Volver</h5>
                            </div>
                        </div>
                    </button>
                </div>
                <table class="list-group pe-0 border mt-3 " style="max-height: 60vh; overflow-y: auto;">



                    @foreach ($questions as $q)
                        <tr
                            class="list-group-item list-group-item-action d-flex  @if ($q->id == $question->id) active @endif"
                            wire:click='selectQuestion({{ $q->id }})'>
                            <td class="text-end align-middle me-1">{{ $q->id }}</td>
                            <td class="ps-1 lines-text-2">{{ $q->title }}</td>
                        </tr>
                    @endforeach
                </table>




            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-body">

                        <form wire:submit='storeQuestion' class="mb-2" id="resultForm">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-12">

                                    <textarea type="text" class="form-control " placeholder="Titulo"
                                        wire:model='questionTitle' name="title" id="resultValue">
                                    </textarea>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <input type="hidden" name="question_id" value="{{ $question->id }}" id="resultId">
                                    <input type="text" class="form-control " placeholder="Resultado"
                                        wire:model='questionResultTxt' name="result" id="resultValue"
                                        @readonly($question->type==1 || $question->type==5)>
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
                                        <label class="btn btn-outline-primary" for="radioCoef">Coeficiente</label>
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
                                    <button type="submit" class="btn btn-success" wire:click='setQuestionsVerified'>
                                        Guardar
                                    </button>
                                </div>
                            </div>


                        </form>

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
                                        $options = ['optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF'];

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
                                <tfoot>
                                    <tr class="table-active">
                                        <th class="text-end">Total:</th>
                                        <th>{{ $question->resultNom ? $question->resultNom['total'] : 'Sin resultado' }}</th>
                                        <th>{{ $question->resultCoef ? $question->resultCoef['total'] : 'Sin resultado' }}</th>
                                    </tr>
                                </tfoot>

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
@script
    <script></script>
@endscript
