<div class="row me-3">
    <x-alerts />

    <div class="col-12">
        <div class="row px-0">
            <div class="card px-0 ">
                <div class="card-header pb-0 no-bottom-round">
                    <div class=" d-flex justify-content-between px-2 ">
                        <div class="col-1">
                            <button class="btn btn-danger" wire:click='cleanData(1)'>
                                <i class='bi bi-trash-fill '></i>
                            </button>
                        </div>


                        <div class="col-auto ">
                            <div class="btn-group " role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio1"
                                    autocomplete="off" wire:model.live='tab' value='1'>
                                <label class="btn btn-outline-primary" for="btnradio1">Cambiar</label>

                                <input type="radio" class="btn-check" name="btnradio" id="btnradio2"
                                    autocomplete="off" wire:model.live='tab' value='2'>
                                <label class="btn btn-outline-primary" for="btnradio2">Retirar</label>

                                <input type="radio" class="btn-check" name="btnradio" id="btnradio3"
                                    autocomplete="off" wire:model.live='tab' value='3'>
                                <label class="btn btn-outline-primary" for="btnradio3">Predio</label>
                                @if ($asambleaOn->registro)
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio4"
                                        autocomplete="off" wire:model.live='tab' value='4'>
                                    <label class="btn btn-outline-primary" for="btnradio4">Personas</label>
                                @endif
                            </div>
                        </div>


                        <div class="col-1 text-end">
                            <button class="btn btn-danger d-inline-block" wire:click='proof' wire:keypress='$refresh'>
                                <i class='bi bi-info-circle-fill '></i>
                            </button>
                        </div>
                    </div>
                </div>
                @if ($tab == 4)
                    <div class="card-header d-flex justify-content-between bg-body">

                        @if ($Persona)
                            <form action="{{ route('personas.update') }}" method="post" id="updatePersona"
                                 name="formPersona">
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
                                        <select class="form-select py-0 w-25" name="tipoid"
                                            @disabled(!$changes)>
                                            @foreach ($tiposId as $item)
                                                <option @selected($item == $Persona->tipo_id) value="{{ $item }}">
                                                    {{ $item }}</option>
                                            @endforeach
                                        </select>
                                        <input class="form-control text-end w-50 p-0" type="text" name="newId"
                                            value="{{ $Persona->id }}" @readonly(!$changes)>
                                        <input class="d-none p-0" type="text" name="id"
                                            value="{{ $Persona->id }}" hidden>
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
                                <input class="me-2 form-control @error('noFound') is-invalid @enderror" type="text"
                                    onkeypress="return onlyNumbers(event)" maxlength="12"
                                    wire:keydown.enter="searchPersona('CC')" wire:model='cedulaSearch'
                                    placeholder="Cedula" wire:keypress='$refresh'>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
                <div class="card-body">
                    <div class="row g-1">
                        @if ($tab != 3)
                            <div class="col-5">

                                <div class="card p-0">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <div class="col-auto">
                                            @if ($tab == 1)
                                                Control A
                                            @elseif ($tab == 2)
                                                Predios a Asignar
                                            @elseif($tab == 4)
                                                Predios
                                            @endif
                                        </div>
                                        <div class=" col-4">
                                            @if ($tab != 4)
                                                <input type="text"
                                                    class="form-control bg-success-subtle  @error('controlIdL') is-invalid @enderror  @error('controlId') is-invalid @enderror"
                                                    wire:model.live='controlIdL' value="controlIdL"
                                                    placeholder="Control" onkeypress="return onlyNumbers(event)"
                                                    maxlength="3">
                                            @endif

                                        </div>
                                    </div>
                                    <div class="card-body table-responsive table-fixed-header px-0">
                                        <table class="w-100 table mb-0 ">
                                            <tbody>
                                                @forelse ($prediosL as $predio)
                                                    <tr scope="row">
                                                        <td style="width: 85%">{{ $predio->descriptor1 }}
                                                            {{ $predio->numeral1 }}
                                                            {{ $predio->descriptor2 }} {{ $predio->numeral2 }}</td>

                                                        <td>
                                                            @if ($tab < 3)
                                                                <button class="btn p-0"
                                                                    wire:click="toRight({{ $predio->id }})">
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

                                    <div class="card-footer justify-content-between d-flex">
                                        <div class="col-7 align-content-center">
                                            @if ($asambleaOn->registro && $nameL)
                                                <p class="mb-0">{{ $nameL }}</p>
                                            @endif
                                        </div>
                                        <div class="col-5">
                                            <input class="form-control d-inline-block " name="sum_coef"
                                                value="{{ $sumCoefL }}" id="sumCoef" readonly>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-1 align-items-center mb-auto  mx-auto">
                                @if ($tab != 4)
                                    <span class="btn-dark w-100 py-0 mb-4 ps-2 text-center ">
                                        <i class="bi bi-shuffle fs-1 "></i>
                                    </span>
                                    <div class="card p-2 mt-5 ">
                                        <button class="btn btn-warning ps-2 mb-3 py-0" wire:click='undo'>
                                            <i class="bi bi-arrow-counterclockwise fs-4 "></i>
                                        </button>
                                        <button class="btn btn-primary ps-2 mb-3 py-0" wire:click='exchange'>
                                            <i class="bi bi-arrow-left-right fs-4 "></i>
                                        </button>

                                        <button class="btn btn-primary ps-1 mb-0 py-0" wire:click='toRightAll'>
                                            <i class="bi bi-box-arrow-in-right fs-4 "></i>
                                        </button>
                                    </div>
                                @endif


                            </div>

                            <div class="col-5">
                                <div class="card p-0">
                                    <div class="card-header d-flex align-items-center justify-content-between">
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
                                            <input type="text"
                                                class="form-control bg-success-subtle  @error('controlIdR') is-invalid @enderror  @error('controlId') is-invalid @enderror"
                                                wire:model.live='controlIdR' placeholder="Control"
                                                @if ($tab != 1) hidden @endif
                                                onkeypress="return onlyNumbers(event)" maxlength="3">
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive table-fixed-header px-0">


                                        <table class="w-100 table mb-0 ">

                                            <tbody>
                                                @forelse ($prediosR as $predio)
                                                    <tr scope="row">
                                                        <td>{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                            {{ $predio->descriptor2 }} {{ $predio->numeral2 }}</td>
                                                        <td>
                                                            @if ($tab == 2)
                                                                <button class="btn p-0"
                                                                    wire:click="toLeft({{ $predio->id }})">
                                                                    <i class='bi bi-arrow-left-square-fill'></i>
                                                                </button>
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
                                    <div class="card-footer justify-content-between d-flex">
                                        <div class="col-7 align-content-center">
                                            @if ($asambleaOn->registro && $nameR)
                                                <p class="mb-0">{{ $nameR }}</p>
                                            @endif
                                        </div>
                                        <div class="col-5">
                                            <input class="form-control d-inline-block " name="sum_coef"
                                                value="{{ $sumCoefR }}" id="sumCoef" readonly>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @elseif($tab == 3)
                            @if ($Predio)
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title mb-0 ">{{ $Predio->descriptor1 }}
                                            {{ $Predio->numeral1 }}
                                            {{ $Predio->descriptor2 }} {{ $Predio->numeral2 }}
                                        </h5>
                                        @hasanyrole('Admin|Lider')
                                            <div class="d-flex align-items-center ">
                                                @if ($changes)
                                                    <button class="btn mt-1 p-0 me-2"
                                                        wire:click='undoPredioChanges({{ $Predio->id }})'>
                                                        <i class="bi bi-arrow-counterclockwise fs-6 "></i>
                                                    </button>
                                                @endif

                                                <h5 class="card-title mb-0 pt-1 me-3">
                                                    Cambios
                                                </h5>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input scaled-switch-15" type="checkbox"
                                                        role="switch" id="changeSwitch" wire:model.live='changes'>
                                                </div>
                                            </div>
                                        @endhasanyrole

                                    </div>
                                    <form action="{{ route('predios.update') }}" method="post" id="updatePredio"
                                        name="formPredio">
                                        @csrf
                                        <div class="card-body pt-3 px-2 d-flex justify-content-center">
                                            <div class="col-5 @if (!$asambleaOn->registro) mx-auto @endif me-4">
                                                <ul class="list-group list-group-flush">
                                                    <li
                                                        class="list-group-item d-flex align-items-center justify-content-between">
                                                        <h5 class="mb-0">
                                                            Id:
                                                        </h5>
                                                        <input type="text" class="form-control w-50"
                                                            value="{{ $Predio->id }}" readonly name="id">

                                                    </li>

                                                    <li
                                                        class="list-group-item d-flex align-items-center justify-content-between">
                                                        <h5 class="mb-0">Coef:</h5>
                                                        <input type="text" class="form-control w-50"
                                                            onkeypress="return onlyNumbers(event)" name="coef"
                                                            value="{{ $Predio->coeficiente }}" @readonly(!$changes)>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex align-items-center justify-content-between">
                                                        <h5 class="mb-0">Control:</h5>
                                                        <input type="text" class="form-control w-50" disabled
                                                            value="{{ !$Predio->control->isEmpty() ? $Predio->control[0]->id : 'Sin Asignar' }}">
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex align-items-center justify-content-center">
                                                        <h5 class="mb-0 me-2">Voto:</h5>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input scaled-switch-15"
                                                                type="checkbox" role="switch" id="votoSwitch"
                                                                name="voto" @checked($Predio->vota)
                                                                @disabled(!$changes)>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-6 ">
                                                @if ($asambleaOn->registro)
                                                    <ul class="list-group list-group ">
                                                        <li
                                                            class="list-group-item bg-primary d-flex justify-content-between align-items-center">
                                                            <h5 class="mb-0 bx-w"><strong>Propietario</strong> </h5>
                                                            <button type="button" class="btn p-0" wire:click=''>
                                                                <i class="bi bi-eye-fill fs-3"></i>
                                                            </button>
                                                        </li>
                                                        <li class="list-group-item ">
                                                            <h6 class="mb-0">
                                                                {{ $Predio->persona->nombre }}
                                                                {{ $Predio->persona->apellido }}
                                                            </h6>
                                                        </li>
                                                        <li
                                                            class="list-group-item d-flex align-items-center justify-content-center">
                                                            <h5 class="mb-0">Cedula: {{ $Predio->persona->id }}</h5>
                                                        </li>


                                                    </ul>
                                                @endif
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            @else
                                <h3>
                                    Debe elejir un predio
                                </h3>
                            @endif

                        @endif



                    </div>
                </div>
                @if ($changes)
                    <div class="card-footer text-end">
                        <button class="btn btn-success bx-w" id="btn-{{ $tabNames[$tab] }}" type="submit"
                            @if ($tab == 3) onclick="submitformPredio()" @elseif ($tab == 4) onclick="submitformPersona()" @endif
                            @if ($tab == 1) wire:click='storeInChange'  @elseif($tab == 2) wire:click='storeDetach' @endif>
                            Guardar
                        </button>
                @endif

            </div>
        </div>

    </div>


</div>
<script type="text/javascript">
    function submitformPersona() {
        console.log('buba')
        document.formPersona.submit();
    }

    function submitformPredio() {
        console.log('buba')
        document.formPredio.submit();
    }
</script>
