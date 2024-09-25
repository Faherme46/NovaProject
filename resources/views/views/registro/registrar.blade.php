<div>
    <x-alerts />
    <div class="row g-2 mb-2 z-2">
        {{-- buscar --}}
        <div class="col-4 ">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 ">Asistente</h5>
                    <div class="me-0">
                        <button class="btn btn-danger" wire:click='cleanData(1)'>
                            <i class='bi bi-trash-fill '></i>
                        </button>
                    </div>
                </div>

                <div class="card-body table-responsive pt-3">


                    <div class="mb-3 d-flex  ">
                        <div class="col-8 me-1 ">


                            <input class="me-2 form-control @error('cedula') is-invalid @enderror" type="text"
                                onkeypress="return onlyNumbers(event)" maxlength="12" id="cedula" name="cedula"
                                wire:model='cedula' wire:keydown.enter='search' @disabled($asistente)
                                placeholder="Cédula">
                            @error('cedula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <button class="btn btn-primary" wire:click.prevent='search'>
                                <i class="bi bi-search"></i>
                            </button>
                        </div>

                    </div>


                    <div class="mb-3 ">
                        <input name="nombre" id="txtName" rows="1" cols="12" class="form-control"
                            value="{{ $asistente ? $asistente->nombre : '' }}" disabled placeholder="Nombre"></input>

                    </div>
                    <div class="mb-3">
                        <input name="nombre" id="txtLastName" rows="1" cols="12" class="form-control"
                            value="{{ $asistente ? $asistente->apellido : '' }}" disabled placeholder="Apellido">
                    </div>

                </div>

            </div>

            <!-- Mostrar el nombre de la persona encontrada aquí -->
        </div>

        {{-- poderdantes --}}
        <div class="col-8">
            @if (session('errorPropietarios'))
                <div class="alert alert-danger position-absolute alert-dismissible z-3 " role="alert">
                    {{ session('errorPropietarios') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card ">
                <div class="card-header mb-0 d-flex align-items-center justify-content-between">
                    <h5 class="card-title  mb-0 me-5">Poderdantes</h5>
                    <div class="d-flex align-items-baseline ">

                        <input placeholder="cedula" onkeypress="return onlyNumbers(event)" type="text"
                            name="cedulaPropietario" id="cc" class="form-control" placeholder=""
                            aria-describedby="helpId" wire:model='ccPoderdante' onclick="this.select()" />
                        <button type="submit" class="btn ms-1 btn-primary" wire:click='addPoderdante'>
                            <i class='bi bi-arrow-right-circle-fill'></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive table-fixed-header">

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cedula</th>
                                <th>
                                    <a class="btn p-0" wire:click='dropAllPoderdantes'>
                                        <i class='bi bi-x-square-fill'></i>
                                    </a>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ($poderdantes ? !$poderdantes->isEmpty() : false)
                                @foreach ($poderdantes as $p)
                                    <tr>
                                        <td>{{ $p->nombre }} {{ $p->apellido }} </td>
                                        <td>{{ $p->id }}</td>
                                        <td>
                                            <button class="btn p-0"
                                                wire:click="dropPoderdante({{ $p->id }},{{ $p->predios }})">
                                                <i class='bi bi-x-square-fill'></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-0">
        {{-- asignar --}}
        <div class="col-12 ">
            <div class="card ">
                <div class="card-header d-flex">
                    <div class="col-10">
                        @if ($asistenteControls)
                            <form id="formPredios" class="row g-3" wire:submit='asignar(1)' method="GET">
                                <div class=" col-2 ">
                                    <select name="control" id="id_control_selected" wire:model="controlH"
                                        wire:change='changePredios' class="form-control" required>
                                        @foreach ($asistenteControls as $control)
                                            <option value="{{ $control->id }}">
                                                {{ $control->id }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary">
                                        Agregar
                                    </button>
                                </div>
                                <div class="col-1">
                                    <button type="button" class="btn btn-primary" wire:click="resetControl">
                                        <i class='bi bi-plus-circle-fill '></i>
                                    </button>
                                </div>
                            </form>
                        @else
                            <form id="formPredios" class="row g-3" wire:submit='asignar(0)' method="GET">
                                <div class=" col-2 ">
                                    <select name="control" id="id_control" class="form-control" required
                                        wire:model="controlId">
                                        @foreach ($controlIds as $control)
                                            <option value="{{ $control }}" @selected($control == $controlId)>
                                                {{ $control }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary">
                                        Asignar
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                    <div class="col-2 text-right">
                        <input class="form-control" name="sum_coef" value="{{ $sumCoef }}" id="sumCoef"
                            readonly></input>
                    </div>

                </div>
                <div class="card-body table-responsive table-fixed-header">
                    <table class="table">
                        <thead>
                            <tr>

                                <th>A/P</th>
                                <th>Predio</th>
                                <th>Coef.</th>
                                <th>
                                    <input class="form-check-input" type="checkbox" wire:model='selectAll'
                                        wire:click="setSumCoef" />
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prediosAvailable as $predio)
                                <tr>

                                    <td>
                                        <span class="badge text-bg-info fs-6">
                                            {{ $predio->getRelationPersona($cedula) }}
                                        </span>

                                    </td>

                                    <td>{{ $predio->getFullName() }} </td>
                                    <td>{{ $predio->coeficiente }}</td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" wire:click="setSumCoef"
                                            wire:model="predioSelected" value="{{ $predio->id }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No hay predios para asignar</td>
                                </tr>
                            @endforelse

                            @if ($asistenteControls)
                                @foreach ($asistenteControls[$controlH]->predios as $predio)
                                    <tr class="table-active">

                                        <td><span class="badge p-1 fs-6 text-bg-info">
                                                {{ $predio->getRelationPersona($asistente->id) }}
                                            </span></td>
                                        <td>{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                            {{ $predio->descriptor2 }} {{ $predio->numeral2 }} </td>
                                        <td colspan="2">{{ $predio->coeficiente }}</td>

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>


    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">No esta registrado</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="creaPersona">
                    <div class="modal-body">

                        <div class=" row g-3 d-flex">
                            <div class="col-2">
                                <select class="form-control" name="tipo_id" wire:model="tipoId">
                                    <option value="CC" selected>CC</option>
                                    <option value="CE">CE</option>
                                    <option value="NIT">NIT</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <input type="text" name="cedula" class="form-control" onclick="this.select()"
                                    value="{{ $cedula }}" onkeypress="return onlyNumbers(event)"
                                    maxlength="12" wire:model='cedula' required />
                                <small id="helpId" class="text-muted">Cedula</small>
                            </div>

                        </div>
                        <div class="mb-3">
                            <input type="text" name="nombre" id="txtName" class="form-control"
                                onclick="this.select()" onkeypress="return noNumbers(event)" wire:model='name' />
                            <div>
                                @error('name')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <small id="helpId" class="text-muted">Nombre</small>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="apellido" id="txtApellido" class="form-control"
                                onclick="this.select()" value="{{ old('apelldio') }}" wire:model='lastName'
                                placeholder="" />
                            <small id="helpId" class="text-muted">Apellido</small>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    


</div>


<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('showModal', (event) => {
            $('#myModal').modal('show');
        });


        Livewire.on('hideModal', (event) => {
            $('#myModal').modal('hide');
        });
    });
</script>
