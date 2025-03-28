<div class="card">
    @if ($distincts['descriptor1'][0] !== '' && count($distincts['descriptor1']) > 1)
        <div class="card-header">
            <div class="row g-1">
                @foreach ($distincts['descriptor1'] as $descriptor1)
                    <div class="col-auto">
                        <input type="radio" class="btn-check" name="descriptor1" id="{{ $descriptor1 }}"
                            value="{{ $descriptor1 }}" wire:model.live='search.descriptor1'>
                        <label class="btn btn-outline-primary me-2 "
                            for="{{ $descriptor1 }}">{{ $descriptor1 }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @if ($distincts['numeral1'][0] !== '')
        <div class="card-header table-25 table-responsive py-1">
            <div class="row  g-1">
                @if (count($distincts['descriptor1']) == 1)
                    <div class="col-auto d-flex me-2 align-items-center align-middle">
                        <span>{{ $distincts['descriptor1'][0] }}</span>
                    </div>
                @endif
                @foreach ($distincts['numeral1'] as $numeral1)
                    <div class="col-auto">
                        <input type="radio" class="btn-check" name="numeral1" value="{{ $numeral1 }}"
                            id="{{ $numeral1 }}" wire:model.live='search.numeral1'>
                        <label class="btn btn-outline-primary" for="{{ $numeral1 }}">{{ $numeral1 }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @if ($distincts['descriptor2'][0] !== '' && count($distincts['descriptor2'])>1)
        <div class="card-header ">
            <div class="row g-1">

                @foreach ($distincts['descriptor2'] as $descriptor2)
                    <div class="col-auto">
                        <input type="radio" class="btn-check" name="descriptor2" id="{{ $descriptor2 }}"
                            value="{{ $descriptor2 }}" wire:model.live='search.descriptor2'>
                        <label class="btn btn-outline-primary" for="{{ $descriptor2 }}">{{ $descriptor2 }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card-header">
        <div class="row g-2 align-items-center table-responsive table-70">
            @if (count($distincts['descriptor2']) == 1)
                <div class="col-auto d-flex me-2 align-items-center align-middle">
                    <span>{{ $distincts['descriptor2'][0] }}</span>
                </div>
            @endif
            <div class="col-3">
                <input type="number" id="search" class="form-control" wire:model='search.numeral2'
                    wire:keydown.enter="searchWithNumeral2">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="button" wire:click="searchWithNumeral2"
                data-bs-toggle="tooltip" data-bs-title="Buscar predio">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <div class="col-auto">
                <button class="btn btn-danger" type="button" wire:click="search">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body px-1">
        <div class="row g-1 table-responsive table-55 p-0 justify-content-center">

            @forelse ($prediosAll as $predio)
                <div class="col-auto">
                    @if ($consulta)
                        <input type="radio" class="btn-check" id="check-{{ $predio['id'] }}" name='predio-check'>
                        <label class="btn btn-outline-info  fs-5" for="check-{{ $predio['id'] }}"
                            data-bs-toggle="tooltip" data-bs-title='Consultar Predio'
                            wire:click='dispatchPredio(@json($predio))'>
                            {{ $predio['numeral2'] }}
                        </label>
                    @else
                        <input type="checkbox" class="btn-check" id="check-{{ $predio['id'] }}"
                            onchange="this.checked = true;" @disabled($predio['control_id'])>
                        <label
                            class="btn @if ($predio['control_id']) btn-warning @else btn-outline-info @endif fs-5"
                            for="check-{{ $predio['id'] }}" data-bs-toggle="tooltip" data-bs-title='Agregar Predio'
                            wire:click='dispatchPredio(@json($predio))'>
                            {{ $predio['numeral2'] }}
                        </label>
                    @endif
                </div>
            @empty
                <div class="col">
                    <p>No se hallaron Predios</p>
                </div>
            @endforelse
        </div>


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
    {{--
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
                            <div class="@if ($asamblea['registro']) col-6 @else col-12 @endif">
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
                            @if ($asamblea['registro'])
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
                    <div class="modal-body pt-0 d-flex align-items-center">
                        <span class="me-2">
                            Controles:
                        </span>

                        @foreach ($Persona->controls as $control)
                            <button type="button" class="btn btn-success me-2">
                                <span>{{ $control->id }}</span>
                            </button>
                        @endforeach
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

                            @if ($asamblea['registro'])
                                <li class="list-group-item d-flex">

                                    <div class="ms-0 me-auto ">
                                        <div class="fw-bold">Propietario: </div>
                                        <div class="d-flex">
                                            <p class="ms-3 mb-0">{{ $Predio->persona->nombre }}
                                                {{ $Predio->persona->apellido }}</p>
                                            <p class="ms-3 mb-0"> {{ $Predio->persona->tipo_id }}:
                                                {{ $Predio->persona->id }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="badge btn text-bg-primary rounded-pill pt-2"
                                        wire:click='showPersona({{ $Predio->persona->id }})'>
                                        <i class="bi bi-eye fs-2"></i>
                                    </span>

                                </li>
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

                                    @if ($Predio->control)
                                        {{ $Predio->control->id }}
                                    @else
                                        Sin Asignar
                                    @endif
                                </h6>
                            </li>
                        </ul>
                    </div>

                @endisset

            </div>
        </div>
    </div> --}}

    {{-- Modal Control --}}
    {{-- <div class="modal fade" id="modalControl" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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

                                @if ($Control->asignacion())
                                    <li class="list-group-item">
                                        <h6>Estado: {{ $states[$Control->state] }}</h6>
                                    </li>
                                    <li class="list-group-item">
                                        <h6>Coeficiente: {{ $Control->sum_coef }}</h6>
                                    </li>
                                @else
                                    <li class="list-group-item">Sin asignar</li>
                                @endif

                                @if ($asamblea['registro'] && $Control->asignacion())
                                    <li class="list-group-item d-flex">
                                        <div class="ms-0 me-auto ">

                                            <div class="fw-bold">Asistente: </div>
                                            <div class="d-flex">
                                                <p class="ms-3 mb-0">{{ $Control->persona->nombre }}
                                                    {{ $Control->persona->apellido }}</p>
                                                <p class="ms-3 mb-0"> {{ $Control->persona->tipo_id }}:
                                                    {{ $Control->persona->id }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="badge btn text-bg-primary rounded-pill pt-2"
                                            wire:click='showPersona({{ $Control->persona->id }})'>
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
                                            @if ($Control->asignacion())
                                                @forelse ($Control->predios as $predio)
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
    </div> --}}

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
