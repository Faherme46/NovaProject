<div>
    <style>
        .custom-input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 2rem;
        }
    </style>
    <div class="row mt-5 g-3 justify-content-center">
        <div class="col-auto align-items-center ">
            <form action="{{route('questions.create')}}" method="post" id="form">
                @csrf
                <div class="card ">
                    <div class="card-header d-flex align-items-center py-3 ">
                        <div class="col-2 me-2">
                            <div class="dropdown">
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
                        </div>
                        <div class="col-8 d-flex align-items-center ms-2">
                            <input type="text" wire:model='questionTag' class="form-control" name="title">
                        </div>
                        <div class="col-1 mx-2">
                            <button type="submit" class="btn btn-primary" @disabled(!$questionId )>Presentar</button>
                        </div>
                    </div>
                    <div class="card-header d-flex align-items-center py-3  ">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="radioType" id="radioType1" autocomplete="off"
                                wire:model.number.live='questionType'  value="1" @disabled($questionType != 1)>
                            <label class="btn btn-outline-primary  fw-bolder fs-5 py-1 px-2" for="radioType1">Quorum</label>

                            <input type="radio" class="btn-check" name="radioType" id="radioType4" autocomplete="off"
                                wire:model.number.live='questionType'  value="2" @disabled($questionId != 12 && $questionType != 2 )>
                            <label class="btn btn-outline-primary fw-bolder fs-5 py-1 px-2" for="radioType4">Seleccion</label>
                            <input type="radio" class="btn-check" name="radioType" id="radioType2" autocomplete="off"
                                wire:model.number.live='questionType'  value="3" @disabled($questionId != 12 && $questionType != 3)>
                            <label class="btn btn-outline-primary fw-bolder fs-5 py-1 px-2" for="radioType2">Aprobacion</label>

                            <input type="radio" class="btn-check" name="radioType" id="radioType3" autocomplete="off"
                                wire:model.number.live='questionType'  value="4" @disabled($questionId != 12 && $questionType != 4)>
                            <label class="btn btn-outline-primary fw-bolder fs-5 py-1 px-2" for="radioType3">Si/No</label>

                        </div>
                        <div class="mx-2 vr"></div>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="radioCoef" id="radioCoef1" autocomplete="off"
                                wire:model='questionNominal' value="1" @disabled(!$questionId)>
                            <label class="btn btn-outline-primary fw-bolder fs-5 py-1 px-2" for="radioCoef1">Nominal</label>

                            <input type="radio" class="btn-check" name="radioCoef" id="radioCoef2" autocomplete="off"
                            wire:model.boolean='questionNominal' value="0" @disabled(!$questionId)>
                            <label class="btn btn-outline-primary fw-bolder fs-5 py-1 px-2" for="radioCoef2">Coef.</label>
                        </div>
                        <div class="mx-2 vr"></div>
                        <div class="form-check form-switch mb-0 align-items-center ">
                            <input class="form-check-input scaled-switch-15 me-2" type="checkbox" role="switch" id="switchBlanco"
                             wire:model.change='questionWhite' @disabled(!$questionType ||$questionType==1 || $questionType==5  )>
                            <label class="form-check-label fw-bolder fs-5 ms-3" for="switchBlanco">Blanco</label>
                        </div>
                    </div>
                    <div class="card-body d-flex    ">

                        <ul class="list-group">
                            <li class="list-group-item pe-0">
                                <input type="text" class="custom-input " size="1" value="A" disabled>
                            </li>
                            <li class="list-group-item pe-0">
                                <input type="text" class="custom-input " size="1" value="B" disabled>
                            </li>
                            <li class="list-group-item pe-0">
                                <input type="text" class="custom-input " size="1" value="C" disabled>
                            </li>
                            <li class="list-group-item pe-0">
                                <input type="text" class="custom-input " size="1" value="D" disabled>
                            </li>
                            <li class="list-group-item pe-0">
                                <input type="text" class="custom-input" size="1" value="E" disabled>
                            </li>
                            <li class="list-group-item pe-0">
                                <input type="text" class="custom-input " size="1" value="F" disabled>
                            </li>

                        </ul>
                        <ul class="list-group w-100 ">
                            <li class="list-group-item ">
                                <input type="text" class="custom-input resettable" name="optionA"
                                    value="{{ $questionOptions['A'] }}" @readonly($questionType!=2)>
                            </li>
                            <li class="list-group-item">
                                <input type="text" class="custom-input resettable" name="optionB"
                                    value="{{ $questionOptions['B'] }}" @readonly($questionType!=2)>
                            </li>
                            <li class="list-group-item">
                                <input type="text" class="custom-input resettable" name="optionC"
                                    value="{{ $questionOptions['C'] }}" @readonly($questionType!=2)>
                            </li>
                            <li class="list-group-item">
                                <input type="text" class="custom-input resettable" name="optionD"
                                    value="{{ $questionOptions['D'] }}" @readonly($questionType!=2)>
                            </li>
                            <li class="list-group-item">
                                <input type="text" class="custom-input resettable" name="optionE"
                                    value="{{ $questionOptions['E'] }}" @readonly($questionType!=2)>
                            </li>
                            <li class="list-group-item">
                                <input type="text" class="custom-input resettable" name="optionF"
                                    value="{{ $questionOptions['F'] }}" @readonly($questionType!=2)>
                            </li>
                        </ul>
                    </div>

                </div>
            </form>
        </div>
        <div class="col-3 ms-2">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0 text-center">Presentes </h3>
                </div>
                <div class="card-body ">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Quorum: </h5>
                            <input type="text" class="form-control px-1 ms-2 w-50" wire:model='quorumRegistered'>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Predios: </h5>
                            <input type="text" class="form-control px-1 ms-2 w-50" wire:model='prediosRegistered'>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Controles: </h5>
                            <input type="text" class="form-control px-1 ms-2 w-50"
                                wire:model='controlsRegistered'>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0 text-center">Votantes </h3>
                </div>
                <div class="card-body ">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Quorum: </h5>
                            <input type="number " class="form-control px-1 ms-2 w-50" wire:model='quorumVote'>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Predios: </h5>
                            <input type="text" class="form-control px-1 ms-2 w-50" wire:model='prediosVote'>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Controles: </h5>
                            <input type="text" class="form-control px-1 ms-2 w-50" wire:model='controlsVote'>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editableTexts = document.querySelectorAll('.editable-text');
        const editableInputs = document.querySelectorAll('.editable-input');

        editableTexts.forEach(function(editableText, index) {
            editableText.addEventListener('click', function() {
                editableText.style.display = 'none';
                editableInputs[index].style.display = 'block';
                editableInputs[index].focus();
            });
        });

        editableInputs.forEach(function(editableInput, index) {
            editableInput.addEventListener('blur', function() {
                editableText = editableTexts[index];
                editableText.textContent = editableInput.value;
                editableInput.style.display = 'none';
                editableText.style.display = 'block';
            });

            editableInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    editableInput.blur();
                }
            });
        });
    });

</script>
<script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('resetInputs', function () {
            let inputs = document.querySelectorAll('.resettable');
            inputs.forEach(function(input) {
                input.value = '';
            });
        });
    });
</script>

