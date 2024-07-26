<div>

    <div class="row mt-3 g-3 justify-content-center">
        <div class="col-6 align-items-center ">
            <form action="{{route('questions.create')}}" method="post" id="form">
                @csrf

                <input type="number" name="mins" id="" wire:model='mins' hidden>
                <input type="number" name="secs" id="" wire:model='secs' hidden>
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
                    <div class="card-header d-flex align-items-center py-3 justify-content-between  ">
                        <div class="btn-group me-5" role="group">
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
                        <div class="form-check form-switch mb-0 align-items-center ">

                            <input class="form-check-input scaled-switch-15 me-2" type="checkbox" role="switch" id="switchBlanco"
                             wire:model.change='questionWhite' @disabled(!$questionType ||$questionType==1 || $questionType==5  )>
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
                <div class="card-header py-1 ">
                    <h4 class="card-title mb-0 text-center">Presentes </h4>
                </div>
                <div class="card-body py-1">
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

            <div class="card mt-2">
                <div class="card-header py-1">
                    <h4 class="card-title mb-0 text-center">Votantes </h4>
                </div>
                <div class="card-body py-1">
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
                            {{($mins<10)?'0':''}}{{$mins}}:{{($secs<10)?'0':''}}{{$secs}}
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
    document.addEventListener('livewire:init', function () {
        Livewire.on('resetInputs', function () {
            let inputs = document.querySelectorAll('.resettable');
            inputs.forEach(function(input) {
                input.value = '';
            });
        });
    });
</script>
