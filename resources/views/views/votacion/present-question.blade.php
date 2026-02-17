<div>
    <x-alerts />
    <style>
        .list-group-item.active::before {
            content: none;
            /* Elimina el pseudo-elemento ::before */
        }

        .list-group-item.light {
            border-width: 1px;
            /* Ajusta el grosor del borde */
            border-color: blueviolet;
        }

        .list-group-item.light.first {
            border-top: solid 2px;
            border-color: blueviolet;
        }

        .list-group-item.dark {
            border-width: 1px;
            /* Ajusta el grosor del borde */
            border-color: white;
            border-right: 0px
        }
    </style>
    @php
        $options = ['A', 'B', 'C', 'D', 'E', 'F'];
    @endphp
    <div class="row mt-3 justify-content-center">
        <div class="col-12 align-items-center ">
            <div class="card ">
                <div class="card-header mx-0 d-flex align-items-center   ">

                    <div class="col-10  text-center ms-2">
                        @if ($isEditting)
                            <textarea class="mb-0 text-uppercase  w-100 " style="font-size: {{ $sizeTitle }}rem;" wire:model="newTitle"></textarea>
                        @else
                            <h1 class="mb-0 text-uppercase text-center
                                @if ($sizeTitle < 2) lines-text-4
                                @elseif ($sizeTitle < 3) lines-text-3
                                @else  lines-text-2 @endif "
                                id="title" style="font-size: {{ $sizeTitle }}rem;">
                                {{ $question->title }}
                            </h1>
                        @endif

                    </div>
                    <div class="col-2  pe-3 justify-content-end d-flex">

                        @if ($iseditting)
                            <button type="button" class="btn btn-info me-2 fs-4" wire:click='updateQuestion'>
                                <i class="bi bi-floppy2"></i>
                            </button>
                        @else
                            <button type="button" class="btn btn-info me-2 fs-4" wire:click='editting' data-bs-toggle="tooltip"
                            data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        @endif

                        <button type="button" class="btn btn-primary fs-4" wire:click='voting' id="fullscreen"
                            @disabled($isEditting)>
                            VOTAR
                        </button>
                        
                    </div>
                </div>

                <div class="card-body d-flex justify-content-center">
                    @if ($question['type'] == 1)
                        <h1 class="mb-0" style="font-size:{{ $sizeOptions - 4.5 }}rem ">PUEDE SELECCIONAR CUALQUIER
                            OPCION</h1>
                    @else
                        <ul class="list-group w-100 ">
                            @foreach ($options as $op)
                                @if ($question['option' . $op])
                                    <li class="list-group-item light first d-flex p-0">
                                        <div
                                            class="col-1 bg-primary border-bottom border-3 text-light
                                        border-white d-flex justify-content-center align-items-center">
                                            <h1 class="mb-0  " style="font-size:{{ $sizeHeads }}rem ">
                                                {{ $op }}</h1>
                                        </div>
                                        <div class="col-11 d-flex align-items-center justify-content-center">
                                            @if ($isEditting && $question->type == 2)
                                                <textarea type="text" class="w-100 mb-0 text-uppercase ms-2" rows="1"
                                                    style="font-size:{{ $sizeOptions - 2 }}rem" wire:model='newOptions.{{ 'option' . $op }}'>
                                            </textarea>
                                            @else
                                                <h1 class="mx-auto mb-0 text-uppercase lines-text-2 ms-2"
                                                    style="font-size:{{ $sizeOptions }}rem ">
                                                    {{ $question['option' . $op] }}
                                                </h1>
                                            @endif

                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>
