<div>
    <x-alerts />
    <div class="row g-3">
        @session('terminal')
            <div class="position-fixed card top-50 start-50 translate-middle p-0  shadow-lg rounded-3 text-center"
                style="z-index: 1050; width: 400px;">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Terminal</h5>
                    <button type="button" class="btn-close " wire:click='$refresh'></button>
                </div>
                <div class="card-body">
                    <p class="mb-0 fs-5">El asistente puede votar en <span
                            class="badge fs-5 text-bg-success">{{ session('terminal') }}</span></p>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-primary" wire:click='$refresh'>Aceptar</button>
                </div>
            </div>
        @endsession

        <div class="col-5">
            <div class="row px-2 mb-2 z-2">
                {{-- buscar --}}
                <div class="card p-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 ">Asistente</h5>
                        <div class="me-0">
                            <button class="btn btn-danger" wire:click='cleanData(1)' data-bs-toggle="tooltip"
                                data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                data-bs-title="Limpiar Todo">
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
                                value="{{ $asistente ? $asistente->nombre : '' }}" disabled
                                placeholder="Nombre"></input>

                        </div>
                        <div class="mb-3">
                            <input name="nombre" id="txtLastName" rows="1" cols="12" class="form-control"
                                value="{{ $asistente ? $asistente->apellido : '' }}" disabled placeholder="Apellido">
                        </div>

                    </div>

<<<<<<< Updated upstream

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

                        <input placeholder="Cédula" onkeypress="return onlyNumbers(event)" type="text"
                            name="cedulaPropietario" id="cc" class="form-control"
                            wire:keydown.enter='addPoderdante' aria-describedby="helpId" wire:model='ccPoderdante'
                            onclick="this.select()" />
                        <button type="submit" class="btn ms-1 btn-primary" wire:click='addPoderdante'>
                            <i class='bi bi-arrow-right-circle-fill'></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive table-fixed-header pt-0 table-23 ">

                    <table class="table ">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>
                                    <a class="btn p-0" wire:click='dropAllPoderdantes'
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-custom-class="custom-tooltip"
                                    data-bs-title="Quitar todos los poderdantes">
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
                                            <button class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Quitar poderdante"
                                                wire:click="dropPoderdante({{ $p->id }},{{ $p->predios }})">
                                                <i class='bi bi-x-square-fill'></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
=======
>>>>>>> Stashed changes
                </div>
            </div>
            <div class="row px-2">
                {{-- poderdantes --}}
                @if (session('errorPropietarios'))
                    <div class="alert alert-danger position-absolute alert-dismissible z-3 " role="alert">
                        {{ session('errorPropietarios') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card p-0">
                    <div class="card-header mb-0 d-flex align-items-center justify-content-between">
                        <h5 class="card-title  mb-0 me-5">Poderdantes</h5>
                        <div class="d-flex align-items-baseline ">

                            <input placeholder="Cédula" onkeypress="return onlyNumbers(event)" type="text"
                                name="cedulaPropietario" id="cc" class="form-control"
                                wire:keydown.enter='addPoderdante' aria-describedby="helpId" wire:model='ccPoderdante'
                                onclick="this.select()" />
                            <button type="submit" class="btn ms-1 btn-primary" wire:click='addPoderdante'>
                                <i class='bi bi-arrow-right-circle-fill'></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive table-fixed-header pt-0 table-40 ">

                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>Cédula</th>
                                    <th>
                                        <a class="btn p-0" wire:click='dropAllPoderdantes' data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Quitar todos los poderdantes">
                                            <i class='bi bi-x-square-fill'></i>
                                        </a>
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                @if ($poderdantes ? !$poderdantes->isEmpty() : false)
                                    @foreach ($poderdantes as $p)
                                        <tr>
                                            <td>{{ $p->id }}</td>
                                            <td>
                                                <button class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Quitar poderdante"
                                                    wire:click="dropPoderdante({{ $p->id }})">
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
        <div class="col-7">
            <div class="card ">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-primary fs-5">Registrar</button>


                    </div>
                    <div class="d-flex text-right align-items-center w-30">
                        <span class="me-2">Predios: </span>
                        <input class="form-control" name="sum_coef" value="{{ count($predioSelected) }}"
                            id="sumCoef" readonly></input>
                    </div>

                </div>
                <div class="card-body table-responsive table-fixed-header table-70 p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Predio</th>
                                <th>
                                    <input class="form-check-input" type="checkbox" wire:model.live='selectAll' />
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prediosAvailable as $predio)
                                <tr>
                                    <td>{{ $predio['numeral1'].' '.$predio['descriptor1'].' '.$predio['numeral2'] }} </td>
                                    <td>
                                        <input class="form-check-input" type="checkbox"
                                            wire:model.live="predioSelected" value="{{ $predio['id'] }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No hay predios para asignar</td>
                                </tr>
                            @endforelse

                            {{-- @if ($asistenteControls)
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
                        @endif --}}
                        </tbody>
                    </table>
                </div>


            </div>
        </div>

        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">No esta registrado</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
                                    <input type="text" name="cedula" class="form-control"
                                        onclick="this.select()" value="{{ $cedula }}"
                                        onkeypress="return onlyNumbers(event)" maxlength="12" wire:model='cedula'
                                        required />
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
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
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
