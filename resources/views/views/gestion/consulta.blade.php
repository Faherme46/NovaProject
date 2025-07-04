<div>
    <x-alerts />
    <div class="col-12">
        <div class="card px-0 ">
            <div class="card-header pb-0 no-bottom-round">
                <div class=" d-flex justify-content-center ">

                    <div class="btn-group w-auto" role="group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" wire:model.live='tab'
                            value='1' @disabled($asamblea->eleccion)>
                        <label class="btn btn-outline-primary d-flex" for="btnradio1">Cambiar Control</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" wire:model.live='tab'
                            value='2' @disabled($asamblea->eleccion)>
                        <label class="btn btn-outline-primary" for="btnradio2">Retirar Predios</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" wire:model.live='tab'
                            value='3'>
                        <label class="btn btn-outline-primary" for="btnradio3">Consultar Predios</label>
                        @if ($asamblea['registro'])
                            <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off"
                                wire:model.live='tab' value='4'>
                            <label class="btn btn-outline-primary" for="btnradio4">Consultar Personas</label>
                        @endif
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio5" wire:model.live='tab'
                            value='5' >
                        <label class="btn btn-outline-primary" for="btnradio5">Consultar Control</label>
                    </div>
                </div>
            </div>
            @if ($tab == 4)
                <div class="card-header d-flex justify-content-between bg-body">

                    @if ($Persona)
                        <form action="{{ route('personas.update') }}" method="post" id="updatePersona">
                            @csrf
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 me-3">
                                        Nombre:
                                    </h6>
                                    <input class="form-control text-end  p-0" type="text"
                                        value="{{ $Persona->nombre }} " name="name" @readonly(!$changes)>
                                </li>

                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 me-3">Apellido:</h6>
                                    <input class="form-control text-end  py-0" type="text"
                                        value=" {{ $Persona->apellido }}" name="lastName" @readonly(!$changes)>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <select class="form-select py-0 w-25" name="tipoid" @disabled(!$changes)>
                                        @foreach ($tiposId as $item)
                                            <option @selected($item == $Persona->tipo_id) value="{{ $item }}">
                                                {{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <input class="form-control text-end w-50 p-0" type="text" name="newId"
                                        value="{{ $Persona->id }}" @readonly(!$changes)>
                                    <input class="d-none p-0" type="text" name="id" value="{{ $Persona->id }}"
                                        hidden>
                                </li>
                            </ul>
                        </form>
                        @hasanyrole('Admin|Lider')
                            <div class="d-flex h-25">
                                @if ($changes)
                                    <button class="btn mt-1 p-0 me-2" type="button"
                                        wire:click='undoPersonaChanges({{ $Persona->id }})'>
                                        <i class="bi bi-arrow-counterclockwise fs-6 "></i>
                                    </button>
                                @endif

                                <h5 class="card-title mb-0 pt-1 me-3">
                                    Cambios
                                </h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input scaled-switch-15" type="checkbox" role="switch"
                                        id="changeSwitch" wire:model.live='changes'>
                                </div>
                            </div>
                        @endhasanyrole
                    @else
                        <form wire:submit="searchPersona('CC')" class="d-flex">
                            <input class="me-2 form-control @error('noFound') is-invalid @enderror" type="number"
                                onkeypress="return onlyNumbers(event)" maxlength="12"
                                wire:keydown.enter="searchPersona('CC')" wire:model='cedulaSearch' placeholder="Cedula"
                                wire:keypress='$refresh'>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @endif
            <div class="card-body p-0 ">

                @if ($tab != 3 && $tab != 5)
                    <div class="row g-1">
                        <div class="col-5">
                            <div class="card p-0 mb-0">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <div class="col-auto">
                                        @if ($tab == 1)
                                            Control A
                                        @elseif ($tab == 2)
                                            Predios Asignados
                                        @elseif($tab == 4)
                                            Predios
                                        @endif
                                    </div>
                                    <div class=" col-4">
                                        @if ($tab != 4)
                                            <input type="number"
                                                class="form-control bg-success-subtle
                                                @error('controlIdL') is-invalid @enderror  @error('controlId') is-invalid @enderror"
                                                wire:model.live.debounce.900ms='controlIdL'
                                                value="{{ $controlIdL }}" placeholder="Control"
                                                onkeypress="return onlyNumbers(event)" maxlength="3">
                                        @endif

                                    </div>
                                </div>
                                <div class="card-body table-responsive table-fixed-header table-45 p-0 mb-0">
                                    <table class="w-100 table mb-0 ">

                                        <tbody>
                                            @forelse ($prediosL as $predio)
                                                <tr scope="row">

                                                    <td style="width: 85%">{{ $predio['descriptor1'] }}
                                                        {{ $predio['numeral1'] }}
                                                        {{ $predio['descriptor2'] }} {{ $predio['numeral2'] }}
                                                    </td>

                                                    <td>
                                                        @if ($tab < 3)
                                                            <button type="button" class="btn p-0"
                                                                wire:click="toRight({{ $predio['id'] }})">
                                                                <i class='bi bi-arrow-right-square-fill'></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                @if ($controlIdL || $Persona)
                                                    <tr class="table-active">
                                                        <td colspan="2">
                                                            {{ $messageL ? $messageL : 'Sin predios' }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                        <div class="col-1 align-items-center mb-auto  mx-auto">
                            @if ($tab != 4)
                                <span class="btn-dark w-100 py-0 mb-4 ps-2 text-center ">
                                    <i class="bi bi-shuffle fs-1 "></i>
                                </span>

                                <div class="card p-2 mt-5 ">
                                    <button type="button" class="btn btn-danger p-0 mb-3 text-center"
                                        wire:click='cleanData(1,{{ $tab }})'>
                                        <i class='bi bi-trash-fill fs-4 '></i>
                                    </button>

                                    <button type="button" class="btn btn-primary  mb-0 py-0 ps-1"
                                        wire:click='toRightAll'>
                                        <i class="bi bi-box-arrow-in-right fs-4 "></i>
                                    </button>
                                </div>
                            @endif


                        </div>

                        <div @if ($tab == 4) class="col-6" @else class="col-5" @endif>
                            <div class="card p-0">
                                <div class="card-header d-flex align-items-center justify-content-between pe-2">
                                    <div class="col-auto">
                                        @if ($tab == 1)
                                            Control B
                                        @elseif($tab == 2)
                                            <p class="mb-0 py-2">Predios a Retirar</p>
                                        @elseif($tab == 4)
                                            <p class="mb-0">Predios asignados</p>
                                        @endif
                                    </div>
                                    <div class=" col-4">
                                        <input type="number"
                                            class="form-control bg-success-subtle  @error('controlIdR') is-invalid @enderror  @error('controlId') is-invalid @enderror"
                                            wire:model.live.debounce.900ms='controlIdR' value="{{ $controlIdR }}"
                                            placeholder="Control" @if ($tab != 1) hidden @endif
                                            onkeypress="return onlyNumbers(event)" maxlength="3">
                                    </div>
                                    <div class="col-auto d-flex justify-content-end">
                                        <i class="bi bi-building bx"></i>
                                    </div>
                                </div>
                                <div class="card-body table-responsive table-fixed-header table-45 px-0 pt-0">


                                    <table class="w-100 table mb-0 ">

                                        <tbody>
                                            @forelse ($prediosR as $predio)
                                                <tr scope="row"
                                                    class="@if ($predio['control_id'] == $controlIdR) table-active @endif">
                                                    <td>{{ $predio['descriptor1'] }} {{ $predio['numeral1'] }}
                                                        {{ $predio['descriptor2'] }} {{ $predio['numeral2'] }}
                                                    </td>

                                                    <td>
                                                        @if (($tab == 2 || $predio['control_id'] != $controlIdR) && $tab != 4)
                                                            <button type="button" class="btn p-0"
                                                                wire:click="toLeft({{ $predio['id'] }})">
                                                                <i class='bi bi-arrow-left-square-fill'></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        @if ($tab == 4)
                                                            @isset($Persona)
                                                                {{ $predio['control_id'] }}
                                                            @endisset
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                @if ($controlIdR || $Persona)
                                                    <tr class="table-active">
                                                        <td colspan="2">
                                                            {{ $messageR ? $messageR : 'Sin predios' }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                @elseif($tab == 3)
                    @if ($Predio)

                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 ">{{ $Predio->descriptor1 }}
                                    {{ $Predio->numeral1 }}
                                    {{ $Predio->descriptor2 }} {{ $Predio->numeral2 }}
                                </h5>
                                <button type="button" class="btn btn-black px-0 py-0 ms-2 mb-0"
                                    wire:click='forgetPredio'>
                                    <i class="bi bi-x-square-fill fs-4 text-danger "></i>
                                </button>
                            </div>

                            @hasanyrole('Admin|Lider')
                                <div class="d-flex align-items-center ">
                                    @if ($changes)
                                        <button type="button" class="btn mt-1 p-0 me-2"
                                            wire:click='undoPredioChanges({{ $Predio->id }})' data-bs-toggle="tooltip"
                                            data-bs-title="Deshacer">
                                            <i class="bi bi-arrow-counterclockwise fs-6 "></i>
                                        </button>
                                    @endif

                                    <h5 class="card-title mb-0 pt-1 me-3">
                                        Cambios
                                    </h5>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input scaled-switch-15" type="checkbox" role="switch"
                                            id="changeSwitch" wire:model.live='changes'>
                                    </div>
                                </div>
                            @endhasanyrole

                        </div>
                        <form action="{{ route('predios.update') }}" method="post" id="updatePredio"
                            name="formPredio">
                            @csrf
                            <div class="card-body pt-3 px-2 d-flex justify-content-center">
                                <div class="col-3 @if (!$asamblea['registro']) mx-auto @endif me-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">
                                                Id:
                                            </h6>
                                            <input type="number" class="form-control w-70"
                                                value="{{ $Predio->id }}" readonly name="id">

                                        </li>

                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">Coef:</h6>
                                            <input type="text" class="form-control w-70" name="coef"
                                                value="{{ $Predio->coeficiente }}" @readonly(!$changes)>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">Votos:</h6>
                                            <input type="text" class="form-control w-70" name="votos"
                                                value="{{ $Predio->votos }}" @readonly(!$changes)>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">Control:</h6>
                                            <input type="text" class="form-control w-50 ms-1" disabled
                                                value="{{ $Predio->control ? $Predio->control->id : '-' }}">
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-center">
                                            <h5 class="mb-0 me-2">Voto:</h5>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input scaled-switch-15" type="checkbox"
                                                    role="switch" id="votoSwitch" name="voto"
                                                    @checked($Predio->vota) @disabled(!$changes)>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-8 ">
                                    @if ($asamblea['registro'])
                                        <ul class="list-group list-group w-100">
                                            <li
                                                class="list-group-item bg-primary
                                                        d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0 bx-w"><strong>Propietarios</strong> </h5>
                                                <button type="button" class="mb-0 btn py-0 px-1"
                                                    data-bs-toggle="modal" data-bs-target="#addPropietarioModal">
                                                    <i class='bi bi-plus-circle-fill bx-w'></i>
                                                </button>
                                            </li>
                                            @foreach ($Predio->personas as $persona)
                                                <li class="list-group-item d-flex justify-content-between w-100">

                                                    <button type="button" class="mb-0 w-75  btn p-0 text-left"
                                                        wire:click='searchPersona({{ $persona->id }})'>
                                                        {{ $persona->nombre }} {{ $persona->apellido }}
                                                    </button>
                                                    <p class="mb-0">{{ $persona->id }}</p>
                                                    <button type="button" class="mb-0 btn btn-danger py-0 px-1"
                                                        wire:click='setPropietarioToDrop({{ $persona }})'
                                                        @disabled($count = $Predio->personas->count() < 2)>
                                                        <i class='bi bi-trash bx-w'></i>
                                                    </button>
                                                </li>
                                            @endforeach

                                        </ul>
                                    @endif
                                </div>

                            </div>
                        </form>
                    @else
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="form-group d-flex align-items-center">
                                <input type="number" class="form-control w-25 me-1" wire:model='predioIdSearch'
                                    placeholder="Id">
                                <button type="button" class="btn btn-primary" wire:click='searchPredio'>
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <button type="button" class="btn btn-primary" wire:click='createPredio'>
                                Crear Predio
                            </button>

                        </div>
                        <div class="card-body">
                            <h4 class="mb-0">Puede Elegir un predio</h4>
                        </div>
                    @endif
                @elseif ($tab == 5)
                    <div class="card border-black w-80 p-0 mx-auto my-3">
                        <div class="card-header">
                            <form wire:submit="searchControl()" method="POST"
                                class="d-flex w-100 justify-content-center align-items-center">
                                <input class="me-2 w-15 form-control @error('noFound') is-invalid @enderror"
                                    type="number" onkeypress="return onlyNumbers(event)" maxlength="3"
                                    wire:keydown.enter="searchControl()" wire:model='controlIdSearch'
                                    placeholder="Control" wire:keypress='$refresh'>
                                <button type="submit" class="btn btn-primary me-2 p-0 px-1">
                                    <i class="bi bi-search fs-5"></i>
                                </button>
                                @if ($Control)
                                    <button type="button" class="btn btn-danger p-0 px-1" wire:click='dropControl'>
                                        <i class="bi bi-x-circle bx-w fs-5 "></i>
                                    </button>
                                    <span class="badge mx-2 border-1 text-primary border border-black fs-6">Coef.
                                        Votacion: {{ $Control ? $Control->sum_coef_can : 0 }}</span>
                                    <span class="badge mx-2 border-1 text-primary border border-black fs-6"> Votos:
                                        {{ $Control ? $Control->predios_vote : 0 }}</span>
                                @endif
                            </form>
                        </div>
                        <div class=" card-body p-0  table-responsive table-fixed-header table-70 ">

                            <table class="table table-bordered my-0 ">
                                <thead class="table-active">
                                    <th>Id</th>
                                    <th colspan="2">Predio</th>
                                    <th>Votos</th>
                                    <th>Coef.</th>
                                    <th>Vota</th>
                                </thead>
                                <tbody>
                                    @if ($Control)
                                        @if ($Control->state == 4)
                                            <tr class="">
                                                <td colspan="5">Control No asignado</td>
                                            </tr>
                                        @elseif($Control->state == 3)
                                            <tr class="">
                                                <td colspan="5">Control Retirado</td>
                                            </tr>
                                        @else
                                            @foreach ($Control->predios as $p)
                                                <tr>
                                                    <td class="pe-0">{{ $p->id }}</td>
                                                    <td colspan="2">{{ $p->getFullName() }}</td>
                                                    <td class="text-end">{{ $p->votos }}</td>
                                                    <td class="text-end">{{ $p->coeficiente }}</td>
                                                    <td class="{{ $p->vota ? '' : 'text-danger' }}">
                                                        {{ $p->vota ? 'SI' : 'No' }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @else
                                        <tr class="text-muted">
                                            <td class="text-muted">#</td>
                                            <td class="text-muted" colspan="2">Torre * Apartamento ###</td>
                                            <td class="text-muted text-end">0</td>
                                            <td class="text-muted text-end">0,0000</td>
                                            <td class="text-muted">No</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot class="position-sticky bottom-0 table-active">
                                    <td class="text-end bold"> </td>
                                    <td></td>
                                    <td class="text-end">Total Predios:
                                        {{ $Control ? $Control->predios->count() : 0 }}
                                    </td>
                                    <td class="text-end">{{ $Control ? $Control->predios()->sum('votos') : 0 }}</td>
                                    <td class="text-end">{{ $Control ? $Control->sum_coef : 0 }}</td>
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @if ($asamblea['registro'] && $Control && $Control->persona)
                        <div class="d-flex mt-2 align-items-center justify-content-center">
                            <span class="bold fs-5 me-2">Asistente: </span>
                            <span> {{ $Control->persona->fullName() }} </span>
                        </div>
                        <div class="d-flex mt-1 align-items-center justify-content-center">
                            <span class="bold fs-5 me-2">{{ $Control->persona->tipo_id }}: </span>
                            <span> {{ $Control->persona->id }} </span>
                        </div>
                    @endif


                @endif

            </div>
            @if ($changes)
                <div class="card-footer text-end">
                    <button class="btn btn-success bx-w" id="btn-{{ $tabNames[$tab] }}" type="button"
                        @if ($tab == 3) onclick="submitformPredio()" @elseif ($tab == 4) onclick="submitformPersona()"
                        @elseif ($tab == 1) wire:click='storeInChange'  @elseif($tab == 2) wire:click='storeDetach' @endif>
                        Guardar
                    </button>
            @endif

        </div>


    </div>

    <!-- Modal agregar propietario-->
    <div class="modal fade" id="addPropietarioModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Añadir propietario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3 d-flex  ">
                        <div class="col-8 me-1 ">
                            <input class="me-2 form-control" type="text" onkeypress="return onlyNumbers(event)"
                                maxlength="12" id="cedula" name="cedula" wire:model='cedulaPersonita'
                                placeholder="Cédula">
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary" wire:click='searchPersonita'
                                data-bs-dismiss="modal">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        @if ($personaFound)
                            <h5>{{ $personaFound }}</h5>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @if ($personaFound)
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                            wire:click='addPropietario'>
                            Guardar
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Modal crear crear persona-->
    <div class="modal fade" id="crearPropietarioModal" tabindex="-1" aria-labelledby="exampleModalLabel"
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
                                <select class="form-control" name="tipo_id" wire:model="tipoId">
                                    <option value="CC" selected>CC</option>
                                    <option value="CE">CE</option>
                                    <option value="NIT">NIT</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <input type="text" name="cedula" class="form-control" onclick="this.select()"
                                    value="{{ $cedulaPersonita }}" onkeypress="return onlyNumbers(event)"
                                    maxlength="12" wire:model='cedulaPersonita' required />
                                <small id="helpId" class="text-muted">Cedula</small>
                            </div>

                        </div>
                        <div class="mb-3">
                            <input type="text" name="nombre" id="txtName" class="form-control"
                                onclick="this.select()" onkeypress="return noNumbers(event)"
                                wire:model='namePersonita' />
                            <div>
                                @error('name')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <small id="helpId" class="text-muted">Nombre</small>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="apellido" id="txtApellido" class="form-control"
                                onclick="this.select()" value="{{ old('apellido') }}" wire:model='lastNamePersonita'
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
    <!-- Modal eliminar persona -->
    <div class="modal fade" id="dropPersonaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Desea quitar definitivamente este
                        propietario?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ $nameToDrop }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                        wire:click='dropPropietario'> Quitar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal crear propietario-->
    <div class="modal fade" id="crearPredioModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Predio</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('predios.create') }}" method="POST">
                    @csrf
                    <div class="modal-body px-1">
                        <div class=" row g-2 d-flex">
                            

                            <div class="col-3">
                                <select class="form-control" name="descriptor1" >
                                    @foreach ($distincts['descriptor1'] as $d1)
                                        <option value="{{ $d1 }}">{{ $d1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <select class="form-control" name="numeral1" >
                                    @foreach ($distincts['numeral1'] as $n1)
                                        <option value="{{ $n1 }}">{{ $n1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <select class="form-control" name="descriptor2" required>
                                    @foreach ($distincts['descriptor2'] as $d2)
                                        <option value="{{ $d2 }}">{{ $d2 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="numeral2" id="numeral2" class="form-control"
                                    onclick="this.select()" placeholder="Numeral 2">
                            </div>
                            @if ($asamblea->registro)
                                <div class="col-3">
                                    <input type="number" name="propietario" class="form-control"
                                        onclick="this.select()" placeholder="CC" required />
                                    <small id="helpId" class="text-muted">Propietario</small>
                                </div>
                            @endif
                            <div class="col-3">
                                <input type="text" name="coef" class="form-control" onclick="this.select()"
                                    placeholder="Coeficiente" required />
                                <small id="helpId" class="text-muted">Coeficiente</small>
                            </div>
                            <div class="col-3">
                                <input type="number" name="votos" class="form-control" onclick="this.select()"
                                    placeholder="Votos" required />
                                <small id="helpId" class="text-muted">Votos</small>
                            </div>
                            <div class="col-3 ">
                                <input class="form-check-input fs-4" type="checkbox" value="1"
                                    id="flexCheckDefault" name="vota" checked>
                                <label class="form-check-label fs-4" for="flexCheckDefault">
                                    Vota
                                </label>
                            </div>

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
<script type="text/javascript">
    function submitformPersona() {
        formPersonaInfo = document.getElementById('updatePersona')
        formPersonaInfo.submit();
    }

    function submitformPredio() {
        formPredioInfo = document.getElementById('updatePredio')
        formPredioInfo.submit();
    }
</script>
@script
    <script>
        $wire.on('addPropietarioModalShow', () => {
            $('#addPropietarioModal').modal('show');
        });
        $wire.on('addPropietarioModalHide', () => {
            $('#addPropietarioModal').modal('hide');
        });
        $wire.on('dropPersonaModalShow', () => {
            $('#dropPersonaModal').modal('show');
        });
        $wire.on('crearPropietarioModalShow', (event) => {
            $('#crearPropietarioModal').modal('show');
        });

        $wire.on('crearPropietarioModalHide', (event) => {
            $('#crearPropietarioModal').modal('hide');
        });


        $wire.on('crearPredioModalShow', (event) => {
            $('#crearPredioModal').modal('show');
        });
    </script>
@endscript
