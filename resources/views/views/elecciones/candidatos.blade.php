<div>
    <x-alerts />
    <div class="row g-4">
        <div class="col-2">
            <table class="list-group pe-0 border mt-23 " style="max-height: 80vh; overflow-y: auto;">
                <tbody>
                    @foreach ($torres as $t)
                        <tr class="list-group-item list-group-item-action fs-6 @if ($t->id == $torre->id) active @endif"
                            wire:click='setPersonasTorre({{ $t->id }})'>
                            <td>{{$t->first}} {{ $t->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-5">
            <div class="row">
                <div class="card px-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            Buscar Candidato
                        </h5>
                        <button class="btn btn-danger py-0 px-1" wire:click='cleanData(1)' data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Limpiar Todo">
                            <i class='bi bi-trash-fill '></i>
                        </button>
                    </div>
                    <div class="card-body">

                        <div class="row  g-3">
                            <div class="col-5 px-0 ">
                                <div class="px-0 d-flex">
                                    <input class="   form-control w-80 me-1 @error('cedula') is-invalid @enderror"
                                        type="text" onkeypress="return onlyNumbers(event)" maxlength="12"
                                        id="cedula" name="cedula" wire:model='cedula' wire:keydown.enter='search'
                                        placeholder="Cédula">
                                    @error('cedula')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <button class="btn btn-primary w-20 mx-0 px-1 py-0" wire:click='search'>
                                        <i class="bi bi-search fs-5"></i>
                                    </button>
                                </div>

                                <div class="my-3 ">
                                    <input name="nombre" id="txtName" class="form-control"
                                        wire:model='attributesCandidato.name' placeholder="Nombre" disabled></input>
                                </div>
                                <div class=" ">
                                    <input name="lastName" id="lastName" class="form-control"
                                        wire:model='attributesCandidato.lastName' placeholder="Apellido"
                                        disabled></input>
                                </div>
                            </div>
                            <div class="col-7 table-responsive table-fixed-header table-20">
                                <table class="table  ">
                                    <thead>
                                        <th>Predios</th>
                                        <th>P/A</th>
                                    </thead>
                                    <tbody>
                                        @if ($candidato)
                                            @foreach ($candidato->predios as $p)
                                                <tr>
                                                    <td>
                                                        <small>{{ $p->getFullName() }}</small>
                                                    </td>
                                                    <td><small>P</small></td>
                                                </tr>
                                            @endforeach
                                            @foreach ($candidato->prediosEnPoder as $p)
                                                <tr class="table-active">
                                                    <td>
                                                        <small>{{ $p->getFullName() }}</small>
                                                    </td>
                                                    <td><small>A</small></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" wire:click='addCandidato'
                            @disabled(!$candidato)>Añadir</button>
                    </div>
                </div>
            </div>
            <div class="row mt-3 px-0">
                <div class="card px-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            Candidatos para <span class="badge text-bg-primary fs-5 py-1">{{$t->first}}
                                {{ $torre->name }}</span>
                        </h5>
                        <input type="number" name="number" id="" disabled class="form-control fs-5 w-10"
                            value="{{ count($candidatos) + count($candidatosPrevios) }}">
                        <button type="button" class="btn btn-success" wire:click='storeCandidatos'>
                            Guardar
                        </button>
                    </div>
                    <div class="card-body table-responsive table-fixed-header table-40 p-0">
                        <table class="table  table-striped ">
                            <thead>
                                <th>
                                    Nombre
                                </th>
                                <th>Identificación</th>
                                <th>
                                    <a class="btn p-0" wire:click='dropAllCandidatos' data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                        data-bs-title="Quitar candidatos sin guardar">
                                        <i class='bi bi-x-square-fill'></i>
                                    </a>
                                </th>
                            </thead>
                            <tbody>
                                @forelse ($candidatos as $c)
                                    <tr>
                                        <td>{{ $c->fullName() }}</td>
                                        <td>
                                            {{ $c->id }}
                                        </td>
                                        <td>
                                            <a class="btn p-0" wire:click='dropCandidato({{ $c->id }})'
                                                data-bs-toggle="tooltip" data-bs-title="Quitar candidato">
                                                <i class='bi bi-x-square-fill'></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>-</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforelse
                                @foreach ($candidatosPrevios as $c)
                                    <tr class="table-active border-top border-secondary">
                                        <td>{{ $c->fullName() }}</td>
                                        <td>
                                            {{ $c->id }}
                                        </td>
                                        <td>
                                            <a class="btn p-0" wire:click='deleteCandidato({{ $c }})'

                                                data-bs-toggle="tooltip" data-bs-title="Eliminar candidato">
                                                <i class='bi bi-x-square-fill'></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
<<<<<<< Updated upstream
                        Personas de la Torre
=======
                        Personas de {{$t->first}} {{$torre->name}}
>>>>>>> Stashed changes
                    </h5>
                </div>
                <div class="card-body table-responsive table-fixed-header p-0 table-h100">
                    <table class="table">
                        <thead class="table-active">
                            <th>Nombre</th>
                            <th>Identificación</th>
                            <th>Predio</th>
                        </thead>
                        <tbody>
                            @foreach ($personas as $persona)
                                @if ($persona)
                                    <tr>
                                        <td class="align-middle" width="50%">
                                            <button type="button" class="btn btn-black text-start p-0"
                                                wire:click='addCandidato({{ $persona->id }})'
                                                data-bs-toggle="tooltip" data-bs-title="Agregar Persona">
                                                {{ $persona->fullName() }}
                                            </button>
                                        </td>
                                        <td class="align-middle" width="15%">
                                            <button type="button" class="btn btn-black text-start p-0"
                                                wire:click='search({{ $persona->id }})' data-bs-toggle="tooltip"
                                                data-bs-title="Buscar   Persona">
                                                {{ $persona->id }}
                                            </button>
                                        </td>
                                        <td class="align-middle">
                                            @foreach ($persona->predios as $predio)
                                                <p class="mb-0 ">
                                                    {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                                                </p>
                                            @endforeach
                                            @foreach ($persona->prediosEnPoder as $predio)
                                                <p class="mb-0 text-info">
                                                    {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                                                </p>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalCandidato" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                                <select class="form-control" name="tipo_id" wire:model="attributesCandidato.tipoId">
                                    <option value="CC" selected>CC</option>
                                    <option value="CE">CE</option>
                                    <option value="NIT">NIT</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <input type="text" name="cedula" class="form-control" onclick="this.select()"
                                    value="{{ $attributesCandidato ? $attributesCandidato['id'] : 0 }}"
                                    onkeypress="return onlyNumbers(event)" maxlength="12"
                                    wire:model='attributesCandidato.id' required />
                                <small id="helpId" class="text-muted">Cedula</small>
                            </div>

                        </div>
                        <div class="mb-3">
                            <input type="text" name="nombre" id="txtName" class="form-control"
                                onclick="this.select()" onkeypress="return noNumbers(event)"
                                wire:model='attributesCandidato.name' />
                            <div>
                                @error('name')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <small id="helpId" class="text-muted">Nombre</small>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="apellido" id="txtApellido" class="form-control"
                                onclick="this.select()" value="{{ old('apelldio') }}"
                                wire:model='attributesCandidato.lastName' placeholder="" />
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
        <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Candidato</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4 class="mb-0">Desea Eliminar a:  </h4><br>
                        <h5 class="mb-0">{{ $candidatoToDelete['nombre'] }} {{ $candidatoToDelete['apellido'] }}</h5>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary"
                            wire:click='detachCandidato({{ $candidatoToDelete['id'] }})'>Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
</div>
@script
    <script>
        $wire.on('showModal', () => {
            $('#modalCandidato').modal('show');
        });

        $wire.on('hideModal', () => {
            $('#modalCandidato').modal('hide');
        });
        $wire.on('showDeleteModal', () => {
            $('#modalDelete').modal('hide');
            $('#modalDelete').modal('show');
        });
        $wire.on('hideDeleteModal', () => {
            $('#modalDelete').modal('hide');
        });
    </script>
@endscript
