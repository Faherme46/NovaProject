<div>
    <div class="row mt-3 g-3 justify-content-center">
        <div class="col-auto w-50 align-items-center ">
            <form action="{{ route('questions.create') }}" method="POST" id="form">
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

                        <input type="text" wire:model='questionTag' class="form-control me-2" name="title"
                            @readonly($questionId < 12)>


                        <button type="submit" class="btn btn-primary" @disabled(!$questionId)>Presentar</button>

                    </div>
                    <div class="card-header d-flex align-items-center py-3 justify-content-between  ">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="radioType" id="radioType1" autocomplete="off"
                                wire:model.number.live='questionType' value="1" @disabled($questionType != 1)>
                            <label class="btn btn-outline-primary  fw-bolder fs-5 py-1 px-2"
                                for="radioType1">Quorum</label>

                            <input type="radio" class="btn-check" name="radioType" id="radioType4" autocomplete="off"
                                wire:model.number.live='questionType' value="2" @disabled($questionId != 12 && $questionType != 2)>
                            <label class="btn btn-outline-primary fw-bolder fs-5 py-1 px-2"
                                for="radioType4">Seleccion</label>
                            <input type="radio" class="btn-check" name="radioType" id="radioType2" autocomplete="off"
                                wire:model.number.live='questionType' value="3" @disabled($questionId != 12 && $questionType != 3)>
                            <label class="btn btn-outline-primary fw-bolder fs-5 py-1 px-2"
                                for="radioType2">Aprobacion</label>

                            <input type="radio" class="btn-check" name="radioType" id="radioType3" autocomplete="off"
                                wire:model.number.live='questionType' value="4" @disabled($questionId != 12 && $questionType != 4)>
                            <label class="btn btn-outline-primary fw-bolder fs-5 py-1 px-2"
                                for="radioType3">Si/No</label>
                            <input type="radio" class="btn-check" name="radioType" id="radioType5" autocomplete="off"
                                wire:model.number.live='questionType' value="5" hidden>

                        </div>
                        <div class="form-check form-switch mb-0 align-items-center ">

                            <input class="form-check-input scaled-switch-15 me-2" type="checkbox" role="switch"
                                id="switchBlanco" wire:model.change='questionWhite' @disabled(!$questionType || $questionType == 1 || $questionType == 5)>
                            <label class="form-check-label fw-bolder fs-5 ms-3" for="switchBlanco">Blanco</label>
                        </div>
                    </div>
                    <div class="card-body d-flex ">

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
                                <input type="text" class="custom-input resettable" name="optionA" id="optionA"
                                    value="{{ $questionOptions['A'] }}" @readonly($questionType != 2)>
                            </li>
                            <li class="list-group-item">
                                <input type="text" class="custom-input resettable" name="optionB" id="optionB"
                                    value="{{ $questionOptions['B'] }}" @readonly($questionType != 2)>
                            </li>
                            <li class="list-group-item">
                                <input type="text" class="custom-input resettable" name="optionC" id="optionC"
                                    value="{{ $questionOptions['C'] }}" @readonly($questionType != 2)>
                            </li>
                            <li class="list-group-item">
                                <input type="text" class="custom-input resettable" name="optionD" id="optionD"
                                    value="{{ $questionOptions['D'] }}" @readonly($questionType != 2)>
                            </li>
                            <li class="list-group-item">
                                <input type="text" class="custom-input resettable" name="optionE" id="optionE"
                                    value="{{ $questionOptions['E'] }}" @readonly($questionType != 2)>
                            </li>
                            <li class="list-group-item">
                                <input type="text" class="custom-input resettable" name="optionF" id="optionF"
                                    value="{{ $questionOptions['F'] }}" @readonly($questionType != 2)
                                    wire:keydown='disableWhite'>
                            </li>
                        </ul>
                    </div>

                </div>
            </form>
        </div>
        <div class="col-4 ms-2">

            <div class="card">

                <div class="card-body py-0 px-0">
                    <table class="table mb-0">
                        <thead>
                            <tr class="table-active">
                                <th></th>
                                <th class="text-center">Presentes</th>
                                <th class="text-center">Habilitados</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-end">
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
                                <td class="text-end">
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
                                <td class="text-end">
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

            <div class="card mt-2">
                <div class="card-header py-1">
                    <h4 class="card-title mb-0 text-center">Tiempo </h4>
                </div>
                <div class="card-body py-1 d-flex justify-content-center">
                    <div class="col-1 d-flex flex-column mt-3 ">
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
            input=document.getElementById(id);
            input.value='En Blanco'
        });
        $wire.on('setNone', (event) => {
            let id = event.myId
            input=document.getElementById(id);
            input.value=''
        });
    </script>
@endscript
