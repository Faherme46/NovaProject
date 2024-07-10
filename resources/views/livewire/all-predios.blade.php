<div class="card">

    <div class="card-header">
        <h5 class="card-title"> Predios Disponibles</h5>
    </div>
    <div class="card-header row g-1">
        <div class="col-3">

            <input wire:model.live='searchId' type="text" id="searchId" name="cc_propietario" class="form-control"
                placeholder="Propietario">

        </div>
        <div class="col-2">
            <select wire:model.live='decriptor1' class="form-control" name="descriptor1" id="">
                @foreach ($distincts['descriptor1'] as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-1">
            <select wire:model.live='numeral1' class="form-control" name="numeral1" id="">
                <option value="">#</option>
                @foreach ($distincts['numeral1'] as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <select class="form-control" name="descriptor2" id="">

                @foreach ($distincts['descriptor2'] as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <select wire:model.live='numeral2' class="form-control" name="numeral2" id="">
                <option value="">#</option>
                @foreach ($distincts['numeral2'] as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-1 fpr">
            <button wire:click='clean' class=" btn btn-danger"><i class='bi bi-x-circle-fill '></i></button>
        </div>


    </div>
    <div class="card-body table-responsive table-fixed-header table-h100 ">

        <table class="table">

            <thead>
                <th>Añadir</th>
                <th>Propietario</th>
                <th>Predio</th>
                <th>Coef</th>
            </thead>
            <tbody>
                @forelse ($prediosAll as $predio)
                    @if (!$predio->asignacion->isEmpty())
                        <tr class="table-active">
                            <td>
                                <h2 class="btn pt-0 pb-0 mb-0">{{ $predio->asignacion[0]->control_id }}</h2>
                            </td>
                        @else
                        <tr>
                            <td>
                                <button wire:click="dispatchPredio({{ $predio->id }})" class="btn pt-0 pb-0 mb-0">
                                    <i class='bi bi-plus-circle-fill '></i>
                                </button>
                            </td>
                    @endif
                    <td>
                        <button type="button" class="btn p-0"  wire:click='dispatchPersona({{ $predio->cc_propietario }})'
                            wire:confirm='¿Deseas cambiar el poderdante?'>
                            <i class="bi bi-copy"  ></i>
                        </button>
                        <button class="btn pt-0 pb-0 mb-0"
                            wire:click='dispatchPoderdante({{ $predio->cc_propietario }})'>
                            {{ $predio->cc_propietario }}
                        </button>
                    </td>
                    <td>{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                        {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                    </td>
                    <td>{{ $predio->coeficiente }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No se hallaron Predios</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="d-flex">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="ms-2">Desea cambiar el asistente?</h4>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary"
                    data-bs-dismiss="modal">Cambiar</button>
                </div>
            </div>
        </div>
    </div>
</div>
