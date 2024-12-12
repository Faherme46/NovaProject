<div>


    <style>
        .radioNaranja {
            border-color: #fd7e14;
            color: #fd7e14;
        }

        .radioNaranja:checked {
            background-color: #fd7e14;
            color: #ffffff;
        }
    </style>
    <x-alerts />
    <div class="container">

        <div class="row mt-3">
            <div class="card px-0">
                <div class="row g-0">
                    <div class="col-4 bg-body-tertiary border-end">
                        <div class="card-body">
                            <div class="list-group pe-0 border mt-3" style="max-height: 50vh; overflow-y: auto;">
                                @foreach ($titles as $id => $t)
                                    <button type="button"
                                        class="list-group-item list-group-item-action fs-5
                                    @if ($tab == $id) active @endif"
                                        wire:click='setTab({{ $id }})'>
                                        {{ $t }}
                                    </button>
                                @endforeach

                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="card-header">
                            <h4 class="mb-0">
                                {{ $titles[$tab] }}
                            </h4>
                        </div>
                        <div class="card-body">
                            @if ($tab == 1)
                                <div class="row justify-content-center">
                                    @foreach ($themes as $t)
                                        <input type="radio" class="btn-check  " name="radio{{ $t['color'] }}"
                                            value="{{ $t['id'] }}" wire:model.change='themeId'
                                            id="radio{{ $t['color'] }}">
                                        <label class="btn btn-outline d-flex col-3" for="radio{{ $t['color'] }}"
                                            style="">
                                            <span class="btn "
                                                style="  width: 30px; height: 30px; background-color:{{ $t['rgb'] }}">
                                            </span>
                                            <h4 class="mb-0 ms-2">{{ $t['color'] }}
                                            </h4>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif ($tab == 2)
                                @csrf
                                <div class=" mt-2 justify-content-between d-flex align-items-center">
                                    <span class="d-flex align-content-center">
                                        <div class="dropdown  ">
                                            <button class="btn btn-outline-primary dropdown-toggle fs-5" type="button"
                                                data-bs-toggle="dropdown">
                                                Preguntas
                                            </button>
                                            <ul class="dropdown-menu">
                                                @foreach ($questionsPrefab as $question)
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center
                                                        @if ($selectedQuestion['id'] == $question->id) active @endif"
                                                            wire:click='setQuestion({{ $question->id }})'>
                                                            {{ $question->title }}
                                                        </a>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                        
                                        @if ($selectedQuestion['id'])
                                        <i class="   bi bi-plus-circle-fill ms-4 fs-2 text-primary pointer"
                                            wire:click='newQuestion'></i>
                                            <i class="bi bi-trash-fill ms-4 fs-2 text-danger pointer"
                                                data-bs-toggle=modal data-bs-target=#modalDeleteQuestion></i>
                                        @endif

                                    </span>
                                    <button class="btn btn-primary text-light form-control w-15 fs-5 px-3 ms-5"
                                        type="button" wire:click='storeQuestion'>
                                        Guardar
                                    </button>


                                </div>
                                <div class="row gy-2 align-items-center mt-2">
                                    <div class="col-10">
                                        <div class="input-group">
                                            <div class="input-group-text fs-5">Título</div>
                                            <input type="text" class="form-control" id="title" name="title"
                                                wire:model.live='selectedQuestion.title'>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="dropdown ">
                                            <button class="btn btn-outline-primary dropdown-toggle fs-5" type="button"
                                                data-bs-toggle="dropdown">
                                                Tipo
                                            </button>
                                            <ul class="dropdown-menu">
                                                @foreach ($questionTypes as $type)
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center
                                                            @if ($selectedQuestion['type'] == $type->id) active @endif"
                                                            wire:click='changeType({{ $type->id }})'
                                                            @disabled(true)>
                                                            {{ $type->name }}
                                                        </a>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group">
                                            <div class="input-group-text ">A</div>
                                            <input type="text" class="form-control" id="optionA" name="optionA"
                                                wire:model.live='selectedQuestion.optionA' @readonly(in_array($selectedQuestion['type'], [1, 3, 4, 5]))>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group">
                                            <div class="input-group-text ">B</div>
                                            <input type="text" class="form-control" id="optionB" name="optionB"
                                                wire:model.live='selectedQuestion.optionB' @readonly(in_array($selectedQuestion['type'], [1, 3, 4, 5]))>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group">
                                            <div class="input-group-text ">C</div>
                                            <input type="text" class="form-control" id="optionC" name="optionC"
                                                wire:model.live='selectedQuestion.optionC' @readonly(in_array($selectedQuestion['type'], [1, 3, 4, 5]))>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group">
                                            <div class="input-group-text ">D</div>
                                            <input type="text" class="form-control" id="optionD" name="optionD"
                                                wire:model.live='selectedQuestion.optionD' @readonly(in_array($selectedQuestion['type'], [1, 3, 4, 5]))>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group">
                                            <div class="input-group-text ">E</div>
                                            <input type="text" class="form-control" id="optionE" name="optionE"
                                                wire:model.live='selectedQuestion.optionE' @readonly(in_array($selectedQuestion['type'], [1, 3, 4, 5]))>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group">
                                            <div class="input-group-text ">F</div>
                                            <input type="text" class="form-control" id="optionF" name="optionF"
                                                wire:model.live='selectedQuestion.optionF' @readonly(in_array($selectedQuestion['type'], [1, 3, 4, 5]))>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    @if ($tab == 2)
        <div class="modal fade" tabindex="-1" id="modalDeleteQuestion" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">¿Desea eliminar la pregunta?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-footer justify-content-end align-items-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>


                        <button type="button" class="btn btn-danger " wire:click='deleteQuestion'>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@script
    <script>
        $wire.on('close-delete-modal', () => {
            $('#modalDeleteQuestion').modal('hide');
        });
    </script>
@endscript
