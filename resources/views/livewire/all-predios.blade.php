<div class="card">

    <div class="card-header">
        <h5 class="card-title"> Predios Disponibles</h5>
    </div>
    <div class="card-header row g-1">
        <div class="col-3">

            <input wire:model.live='searchId' type="text" id="searchId" name="cc_propietario" class="form-control"
                placeholder="Propietario" onkeypress="return onlyNumbers(event)">

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
            <input wire:model.live='numeral2' type="text" class="form-control" placeholder="#"
                onkeypress="return onlyNumbers(event)" maxlength="5">
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
                {{-- <th>Coef</th> --}}
            </thead>
            <tbody>
                @forelse ($prediosAll as $predio)
                    @if (!$predio->asignacion->isEmpty())
                        <tr class="table-active">
                            <td>
                                <button class="btn pt-0 pb-0 mb-0" wire:dblclick='showControl({{$predio->asignacion[0]->control_id }})'
                                    wire:click="dispatchControl({{ $predio->asignacion[0]->control_id }})">
                                    {{ $predio->asignacion[0]->control_id }}
                                </button>
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
                        @if ($asambleaOn->registro)
                            <button type="button" class="btn p-0"
                                wire:click='dispatchPersona({{ $predio->cc_propietario }})'
                                wire:confirm='¿Deseas cambiar el Asistente?'>
                                <i class="bi bi-copy"></i>
                            </button>
                        @endif
                        <button class="btn pt-0 pb-0 mb-0" wire:dblclick='showPersona({{ $predio->cc_propietario }})'
                            wire:click='dispatchPoderdante({{ $predio->cc_propietario }})'>
                            {{ $predio->cc_propietario }}
                        </button>
                    </td>
                    <td><span class="btn py-0" wire:dblclick='showPredio({{ $predio->id }})'>
                            {{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                            {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                        </span>
                    </td>
                    {{-- <td>{{ $predio->coeficiente }}</td> --}}
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
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cambiar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Persona -->
    <div class="modal fade" id="modalPersona" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @isset($Persona)
                    <div class="modal-header">
                        <h4 class="mb-0">{{ $Persona->nombre }} {{ $Persona->apellido }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="@if ($asambleaOn->registro) col-6 @else col-12 @endif">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title"> Propiedades </h6>
                                    </div>
                                    <div class="card-body table-responsive table-fixed-header px-0">
                                        <table class="w-100 table mb-0 ">
                                            <tbody>
                                                @forelse ($Persona->predios as $predio)
                                                    <tr scope="row">
                                                        <td>
                                                            <span class="btn p-0"
                                                                wire:click='showPredio({{ $predio->id }})'>
                                                                {{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                                {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="table-active">
                                                        <td colspan="2">
                                                            Sin predios
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @if ($asambleaOn->registro)
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title"> Predios Asignados </h6>
                                        </div>
                                        <div class="card-body table-responsive table-fixed-header px-0">
                                            <table class="w-100 table mb-0 ">
                                                <tbody>
                                                    @forelse ($Persona->prediosAsignados() as $predio)
                                                        <tr scope="row">
                                                            <td>
                                                                <span class="btn p-0"
                                                                    wire:click='showPredio({{ $predio->id }})'>
                                                                    {{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                                    {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                                                                </span>
                                                            </td>

                                                        </tr>
                                                    @empty
                                                        <tr class="table-active">
                                                            <td colspan="2">
                                                                Sin predios Asignados
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endisset

            </div>
        </div>
    </div>
    <!-- Modal Predio -->
    <div class="modal fade" id="modalPredio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @isset($Predio)
                    <div class="modal-header">
                        <h4 class="mb-0">{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                            {{ $predio->descriptor2 }} {{ $predio->numeral2 }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <h6 class="mb-0">Id: {{ $Predio->id }}</h6>
                            </li>
                            <li class="list-group-item d-flex">

                                <div class="ms-0 me-auto ">
                                    <div class="fw-bold">Propietario: </div>
                                    <div class="d-flex">
                                        <p class="ms-3 mb-0">{{ $Predio->persona->nombre }}
                                            {{ $Predio->persona->apellido }}</p>
                                        <p class="ms-3 mb-0"> {{ $Predio->persona->tipo_id }}: {{ $Predio->persona->id }}
                                        </p>
                                    </div>
                                </div>
                                <span class="badge btn text-bg-primary rounded-pill pt-2"
                                    wire:click='showPersona({{ $Predio->persona->id }})'>
                                    <i class="bi bi-eye fs-2"></i>
                                </span>

                            </li>
                            @if ($asambleaOn->registro)
                                <li class="list-group-item">
                                    <h6>Apoderado: </h6>
                                    @if ($Predio->apoderado)
                                        <p class="ms-3 mb-0">{{ $Predio->apoderado->nombre }}
                                            {{ $Predio->apoderado->apellido }}</p>
                                    @else
                                        <p class="text-muted mb-0">Sin Apoderado</p>
                                    @endif
                                </li>
                            @endif
                            <li class="list-group-item">
                                <h6>Coeficiente: </h6> {{ $Predio->coeficiente }}
                            </li>
                            <li class="list-group-item">
                                <h6>Control:

                                    @if ($Predio->asignacion->isEmpty())
                                        Sin Asignar
                                    @else
                                        {{ $Predio->asignacion[0]->control_id }}
                                    @endif
                                </h6>
                            </li>
                        </ul>
                    </div>
                @endisset

            </div>
        </div>
    </div>

    {{-- Modal Control --}}
    <div class="modal fade" id="modalControl" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @isset($Control)
                    <div class="modal-header">
                        <h4 class="mb-0">Control {{ $Control->id }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-6">
                            <ul class="list-group">

                                @if ($Control->asignacion)
                                    <li class="list-group-item">
                                        <h6 class="mb-0">Asignacion:{{ $Control->asignacion->id }}
                                        </h6>
                                    </li>
                                    <li class="list-group-item">
                                        <h6>Estado: {{ $states[$Control->state] }}</h6>
                                    </li>
                                    <li class="list-group-item">
                                        <h6>Coeficiente: {{ $Control->asignacion->sum_coef }}</h6>
                                    </li>
                                @else
                                    <li class="list-group-item">Sin asignar</li>

                                @endif

                                @if ($asambleaOn->registro && $Control->asignacion)
                                    <li class="list-group-item d-flex">
                                        <div class="ms-0 me-auto ">

                                            <div class="fw-bold">Asistente: </div>
                                            <div class="d-flex">
                                                <p class="ms-3 mb-0">{{ $Control->asignacion->persona->nombre }}
                                                    {{ $Control->asignacion->persona->apellido }}</p>
                                                <p class="ms-3 mb-0"> {{ $Control->asignacion->persona->tipo_id }}:
                                                    {{ $Control->asignacion->persona->id }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="badge btn text-bg-primary rounded-pill pt-2"
                                            wire:click='showPersona({{ $Control->asignacion->persona->id }})'>
                                            <i class="bi bi-eye fs-2"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title"> Predios Asignados </h6>
                                </div>
                                <div class="card-body table-responsive table-fixed-header px-0">
                                    <table class="w-100 table mb-0 ">
                                        <tbody>
                                            @if ($Control->asignacion)
                                                @forelse ($Control->asignacion->predios as $predio)
                                                    <tr scope="row">
                                                        <td>
                                                            <span class="btn p-0"
                                                                wire:click='showPredio({{ $predio->id }})'>
                                                                {{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                                {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                                                            </span>
                                                        </td>

                                                    </tr>
                                                @empty
                                                    <tr class="table-active">
                                                        <td colspan="2">
                                                            Sin predios Asignados
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            @else
                                                <tr class="table-active">
                                                    <td colspan="2">
                                                        Sin predios Asignados
                                                    </td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endisset

            </div>
        </div>
    </div>

</div>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('showModalPersona', (event) => {
            $('#modalPredio').modal('hide');
            $('#modalControl').modal('hide');
            $('#modalPersona').modal('hide');
            $('#modalPersona').modal('show');
        });

        Livewire.on('showModalPredio', (event) => {
            $('#modalControl').modal('hide');
            $('#modalPersona').modal('hide');
            $('#modalPredio').modal('hide');
            $('#modalPredio').modal('show');
        });

        Livewire.on('showModalControl', (event) => {
            $('#modalPredio').modal('hide');
            $('#modalPersona').modal('hide');
            $('#modalControl').modal('hide');
            $('#modalControl').modal('show');
        });

    });
</script>
