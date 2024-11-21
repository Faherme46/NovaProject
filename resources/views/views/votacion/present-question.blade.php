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
                <div class="card-header d-flex align-items-center   ">

                    <div class="col-11  text-center ms-2">
                        <h1 class="mb-0 text-uppercase text-center lines-text-2 " id="title"
                            style="font-size: {{ $sizeTitle }}rem;" data-bs-toggle="popover"
                            data-bs-content="{{$question->title}}">
                            {{$question->title}}
                        </h1>
                    </div>
                    <div class="col-1 ms-3 me-2">
                        <button type="submit" class="btn btn-primary fs-4" wire:click='voting'>
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
                                        <div class="col-1 bg-primary border-bottom border-3 text-light
                                        border-white d-flex justify-content-center align-items-center">
                                            <h1 class="mb-0  " style="font-size:{{ $sizeHeads }}rem ">
                                                {{ $op }}</h1>
                                        </div>
                                        <div class="col-11 d-flex align-items-center justify-content-center">
                                            <h1 class="mx-auto mb-0 text-uppercase lines-text-2 ms-2" style="font-size:{{ $sizeOptions }}rem ">
                                                {{$question['option' . $op]}}
                                            </h1>
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
