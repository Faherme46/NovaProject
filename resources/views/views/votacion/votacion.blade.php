<div>
    <x-alerts />
    <div class="row mt-3 g-3 w-100">
        <div class="col-8 align-items-center ">
            <form wire:submit='createQuestion' id="form">
                @csrf

                <input type="number" name="mins" id="" wire:model='mins' hidden>
                <input type="number" name="secs" id="" wire:model='secs' hidden>
                <input type="number" name="controls" wire:model='controlsRegistered' hidden>
                <div class="card ">
                    <div class="card-header d-flex align-items-center py-3 ">

                        <div class="dropdown me-2">
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Preguntas
                            </button>
                            <ul class="dropdown-menu">
                                @foreach ($questionsPrefab as $question)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center
                                             @if ($question->id == $questionId) active @endif"
                                            wire:click='setQuestion({{ $question->id }})'>{{ $question->title }}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>

                        <input type="text" wire:model='questionTitle' class="form-control me-2" required>
                        <button type="button" class="btn btn-primary" @disabled(!$isQuestion) data-bs-toggle=modal
                            data-bs-target=#modalPresentQuestion>Presentar</button>

                    </div>
                    <div class="card-header d-flex align-items-center py-3 justify-content-between  ">
                        <div class="btn-group" role="group">
                            @if ($questionType == 6)
                                <input type="radio" class="btn-check" name="radioType" id="radioType6"
                                    wire:model.number.live='questionType' value="6" @disabled($questionType != 6)
                                    checked>
                                <label class="btn btn-outline-primary  fw-bolder  px-2" for="radioType6">Prueba</label>
                            @elseif ($questionType == 5)
                                <input type="radio" class="btn-check" name="radioType" id="radioType5"
                                    wire:model.number.live='questionType' value="5" @disabled($questionType != 5)
                                    checked>
                                <label class="btn btn-outline-primary  fw-bolder  px-2" for="radioType5">
                                    Tratamiento de datos
                                </label>
                            @else
                                <input type="radio" class="btn-check" name="radioType" id="radioType1"
                                    wire:model.number.live='questionType' value="1" @disabled(!$isQuestion || $questionType != 1)>
                                <label class="btn btn-outline-primary  fw-bolder  px-2" for="radioType1">Quorum</label>

                                <input type="radio" class="btn-check" name="radioType" id="radioType4"
                                    wire:model.number.live='questionType' value="2" @disabled(!$isQuestion || ($questionType != 2 && $questionId != 12))>

                                <label class="btn btn-outline-primary fw-bolder  px-2"
                                    for="radioType4">Seleccion</label>

                                <input type="radio" class="btn-check" name="radioType" id="radioType2"
                                    wire:model.number.live='questionType' value="3" @disabled(!$isQuestion || ($questionType != 3 && $questionId != 12))>
                                <label class="btn btn-outline-primary fw-bolder  px-2"
                                    for="radioType2">Aprobacion</label>

                                <input type="radio" class="btn-check" name="radioType" id="radioType3"
                                    wire:model.number.live='questionType' value="4" @disabled(!$isQuestion || ($questionType != 4 && $questionId != 12))>
                                <label class="btn btn-outline-primary fw-bolder  px-2" for="radioType3">Si/No</label>
                            @endif


                        </div>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="radioCoef" value="0" id="radioNom"
                                @disabled(!$isQuestion) wire:model.number.live='questionCoefChart'>
                            <label class="btn btn-outline-primary" for="radioNom">Nominal</label>

                            <input type="radio" class="btn-check" name="radioCoef" value="1" id="radioCoef"
                                @disabled(!$isQuestion) wire:model.number.live='questionCoefChart'>
                            <label class="btn btn-outline-primary" for="radioCoef">Coeficiente</label>
                        </div>
                        <div class="form-check form-switch mb-0 align-items-center ">

                            <input class="form-check-input scaled-switch-15 me-2" type="checkbox" role="switch"
                                id="switchBlanco" wire:model.change='questionWhite' @disabled(!$questionType || $questionType == 1 || $questionType == 5)>
                            <label class="form-check-label fw-bolder fs-5 ms-3" for="switchBlanco">Blanco</label>
                        </div>
                    </div>
                    <div class="card-body d-flex ">
                        <table class="table table-bordered ">
                            <tr>
                                <th class="text-center">
                                    <input type="text" class="custom-input text-center " size="1"
                                        value="A" disabled>
                                </th>
                                <td>
                                    <input type="text" class="custom-input  resettable w-100" id="optionA"
                                        wire:model.live='questionOptions.A' @readonly(!in_array($questionType, [2, 6]))>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center">
                                    <input type="text" class="custom-input text-center text-center" size="1" value="B"
                                        disabled>
                                </th>
                                <td>
                                    <input type="text" class="custom-input  resettable w-100" id="optionB"
                                        wire:model.live='questionOptions.B' @readonly(!in_array($questionType, [2, 6]))>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center">
                                    <input type="text" class="custom-input text-center " size="1" value="C"
                                        disabled>
                                </th>
                                <td>
                                    <input type="text" class="custom-input  resettable w-100" id="optionC"
                                        wire:model.live='questionOptions.C' @readonly(!in_array($questionType, [2, 6]))>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center">
                                    <input type="text" class="custom-input text-center " size="1" value="D"
                                        disabled>
                                </th>
                                <td>
                                    <input type="text" class="custom-input resettable w-100" id="optionD"
                                        wire:model.live='questionOptions.D' @readonly(!in_array($questionType, [2, 6]))>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center">
                                    <input type="text" class="custom-input text-center" size="1" value="E"
                                        disabled>
                                </th>
                                <td>
                                    <input type="text" class="custom-input  resettable w-100" id="optionE"
                                        wire:model.live='questionOptions.E' @readonly(!in_array($questionType, [2, 6]))>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center">
                                    <input type="text" class="custom-input text-center " size="1" value="F"
                                        disabled>
                                </th>
                                <td>
                                    <input type="text" class="custom-input w-100 resettable w-100" id="optionF"
                                        wire:model.live='questionOptions.F' @readonly(!in_array($questionType, [2, 6]))
                                        wire:keydown='disableWhite'>
                                </td>
                            </tr>
                        </table>

                    </div>

                </div>

                <div class="modal fade" tabindex="-1" id="modalPresentQuestion" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Iniciar presentación
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                A continuación se mostrará la ventana de votación
                            </div>
                            <div class="modal-footer justify-content-between align-items-center">
                                <span class="badge m-0 text-bg-warning fs-6 ">Esta accion no se puede corregir</span>

                                <button type="submit" class="btn btn-primary " data-bs-dismiss="modal">
                                    Presentar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-4 ">

            <div class="card">

                <div class="card-body py-0 px-0">
                    <table class="table mb-0">
                        <thead>
                            <tr class="table-active">
                                <th></th>
                                <th class="text-center">
                                    <h5 class="card-title mb-0">Presentes</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="card-title mb-0">Habilitados</h5>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-end align-content-center">
                                    <h5 class="card-title mb-0">Quorum:</h5>
                                </td>
                                <td>
                                    <input type="text" class="form-control px-1 ms-2  "
                                        wire:model='quorumRegistered'>
                                </td>
                                <td>
                                    <input type="number " class="form-control px-1 ms-1 " wire:model='quorumVote'>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-end align-content-center">
                                    <h5 class="card-title mb-0">Controles:</h5>
                                </td>
                                <td>
                                    <input type="text" class="form-control px-1 ms-2"
                                        wire:model='controlsRegistered'>
                                </td>
                                <td>
                                    <input type="number" class="form-control px-1 ms-1 " wire:model='controlsVote'>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-end align-content-center">
                                    <h5 class="card-title mb-0">Predios:</h5>
                                </td>
                                <td>
                                    <input type="text" class="form-control px-1 ms-2"
                                        wire:model='prediosRegistered'>
                                </td>
                                <td>
                                    <input type="number " class="form-control px-1 ms-1 " wire:model='prediosVote'>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="card px-0 w-50 mt-2">
                <div class="card-header py-1">
                    <h4 class="card-title mb-0 text-center">Tiempo </h4>
                </div>
                <div class="card-body py-1 d-flex justify-content-center">
                    <div class="col-1 d-flex flex-column mt-3 me-2">
                        <button class="btn-arrow up" wire:click='increment(1)'></button>
                        <button class="btn-arrow down" wire:click='decrement(1)'></button>
                    </div>
                    <div class="col-auto me-2">
                        <h1 class="ff-clock medium-large-text">
                            {{ $mins < 10 ? '0' : '' }}{{ $mins }}:{{ $secs < 10 ? '0' : '' }}{{ $secs }}
                        </h1>
                    </div>
                    <div class="col-1 d-flex flex-column mt-3">
                        <button class="btn-arrow up" wire:click='increment(0)'></button>
                        <button class="btn-arrow down" wire:click='decrement(0)'></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', function() {
        Livewire.on('resetInputs', function(questionOptions) {
            let inputs = document.querySelectorAll('.resettable');

            inputs.forEach(function(input) {
                input.value = '';
            });
        });

    });
</script>

@script
    <script>
        $wire.on('setInputs', () => {

            questionA = document.getElementById('optionA')
            questionB = document.getElementById('optionB')
            questionC = document.getElementById('optionC')
            questionD = document.getElementById('optionD')
            questionE = document.getElementById('optionE')
            questionF = document.getElementById('optionF')

            questionA.value = $wire.questionOptions.A
            questionB.value = $wire.questionOptions.B
            questionC.value = $wire.questionOptions.C
            questionD.value = $wire.questionOptions.D
            questionE.value = $wire.questionOptions.E
            questionF.value = $wire.questionOptions.F
        });

        $wire.on('setWhite', (event) => {
            let id = event.myId
            input = document.getElementById('option'+id);
            $wire.questionOptions[id]='Blanco'
            input.value = 'Blanco'
        });
        $wire.on('setNone', (event) => {
            let id = event.myId
            input = document.getElementById('option'+id);
              $wire.questionOptions[id]=''
            input.value = ''
        });

    </script>
@endscript
