<div class="col-12">
    <x-alerts />
    <div class="position-fixed w-100 z-2 top-2 end-0 px-4 pt-2 d-flex justify-content-between align-items-center">

        <div class="d-flex">
            <button wire:click='goBack' class="btn me-1 fs-4  btn-primary py-0">
                Volver
            </button>
            <button wire:click='getOut' class="btn btn-danger fs-4 py-0">
                Salir
            </button>

        </div>

        <div class="btn-group" role="group">
            <input type="radio" class="btn-check" wire:model.change='plazasCoef' value="0"
                wire:change='updatePlazasCoef(0)' id="btnradio1">
            <label class="btn btn-outline-primary" for="btnradio1">Nominal</label>

            <input type="radio" class="btn-check" wire:model.change='plazasCoef' value="1" id="btnradio2"
             wire:change='updatePlazasCoef(1)'
                checked>
            <label class="btn btn-outline-primary" for="btnradio2">Coeficiente</label>
        </div>
    </div>
    @php
        $options = ['A', 'B', 'C', 'D', 'E', 'F'];
    @endphp
    <div class="card mt-2">

        <div class="card-body mt-5   ">

            @if ($question->resultCoef)
                <table class="table table-bordered border-black ">


                    <thead>
                        <th class=" fs-1" colspan="4">

                            <h1 class="mb-0 text-uppercase text-center lines-text-2 " id="title"
                                style="font-size: {{ $sizeTitle - 1 }}rem;" data-bs-toggle="popover"
                                data-bs-content="{{ $question->title }}">
                                {{ $question->title }}
                            </h1>

                        </th>
                    </thead>
                    <tbody>

                        <tr class="">
                            <td colspan="4" class="text-center">
                                <h2 class="mb-0">COCIENTE ELECTORAL: {{$valuesPlanchas['umbral']}}</h2>
                            </td>
                        </tr>
                        <tr class="table-active">
                            <td class="text-center" colspan="2">
                                <h1 class="mb-0  "> LISTAS</h1>
                            </td>
                            <td class="col-1 px-2">
                                <h1 class="mb-0">VOTOS</h1>
                            </td>
                            <td class="col-1 px-2">
                                <h1 class="mb-0">PLAZAS</h1>
                            </td>
                        </tr>
                        @foreach ($options as $op)
                            @if ($question['option' . $op] && $question['option' . $op]!=='EN BLANCO')
                                <tr class=" p-0">
                                    <td class="bg-primary text-light text-center">
                                        <h1 class="mb-0  ">
                                            {{ $op }}
                                        </h1>
                                    </td>
                                    <td class="text-center">
                                        <h1 class="text-uppercase lines-text-2 mb-0 ">
                                            {{ $question['option' . $op] }}
                                        </h1>
                                    </td>
                                    <td class="text-center">
                                        <h1 class="mb-0  ">{{ $resultToUse['option' . $op] }}</h1>
                                    </td>
                                    <td class="text-center">
                                        <h1 class="mb-0  ">{{ $valuesPlanchas['option' . $op] }}</h1>
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <th colspan="2" class="text-end bold">
                                <h1 class="mb-0">TOTAL</h1>
                            </th>
                            <th class="text-center">
                                <h1 class="mb-0">
                                    {{$valuesPlanchas['total']}}
                                </h1>
                            </th>
                            <th class="text-center">
                                <h1 class="mb-0">
                                    {{ $plazas }}
                                </h1>
                            </th>
                        </tr>
                    </tfoot>

                </table>
            @else
                <h1>
                    No se genero resultado
                </h1>
            @endif



        </div>
    </div>
</div>
