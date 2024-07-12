<div class="row me-3">
    <x-alerts />

    <div class="col-12">
        <div class="card">
            <div class="card-header  px-0">
                <div class=" d-flex justify-content-between px-2 ">
                    <div class="col-1">
                        <button class="btn btn-danger" wire:click='cleanData(1)'>
                            <i class='bi bi-trash-fill '></i>
                        </button>
                    </div>

                    <div class="col-auto d-flex">
                        <button class="btn btn-primary @if ($inChange) btn-info @endif ms-2"
                            wire:click='setInChange(true)'>
                            Cambiar
                        </button>
                        <button class="btn btn-primary @if (!$inChange) btn-info @endif ms-2"
                            wire:click='setInChange(false)'>
                            Retirar
                        </button>
                    </div>


                    <div class="col-1 text-end">
                        <button class="btn btn-danger d-inline-block" wire:click='proof' wire:keypress='$refresh'>
                            <i class='bi bi-info-circle-fill '></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-1">
                    <div class="col-5">
                        <div class="card  p-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="col-auto">
                                    @if ($inChange)
                                        Control A
                                    @else
                                        Predios a Asignar
                                    @endif
                                </div>
                                <div class=" col-4">
                                    <input type="text"
                                        class="form-control bg-success-subtle  @error('controlIdL') is-invalid @enderror  @error('controlId') is-invalid @enderror"
                                        wire:model.live='controlIdL' placeholder="Control"
                                        onkeypress="return onlyNumbers(event)" maxlength="3">
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

                                                    <button class="btn p-0" wire:click="toRight({{ $predio->id }})">
                                                        <i class='bi bi-arrow-right-square-fill'></i>
                                                    </button>


                                                </td>
                                            </tr>
                                        @empty
                                            @if ($controlIdL)
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

                            <div class="card-footer text-end">
                                <input class="form-control d-inline-block w-50" name="sum_coef"
                                    value="{{ $sumCoefL }}" id="sumCoef" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 align-items-center mb-auto  mx-auto">
                        <span class="btn-dark bx-w w-100 py-0 mb-4 ps-2 text-center ">
                            <i class="bi bi-shuffle fs-1 "></i>
                        </span>
                        <div class="card p-2 mt-5 ">
                            <button class="btn btn-warning ps-2 mb-4 py-0" wire:click='undo'>
                                <i class="bi bi-arrow-counterclockwise fs-4 "></i>
                            </button>

                            <button class="btn btn-primary ps-1 mb-0 py-0" wire:click='toRightAll'>
                                <i class="bi bi-box-arrow-in-right fs-4 "></i>
                            </button>
                        </div>
                        <button class="btn btn-success bx-w w-100 pb-0  mt-5"
                            wire:click='@if ($inChange) storeInChange  @else storeDetach @endif'>
                            <i class="bi bi-floppy-fill fs-4 "></i>
                        </button>

                    </div>
                    <div class="col-5">
                        <div class="card p-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="col-auto">
                                    @if ($inChange)
                                        Control B
                                    @else
                                        <p class="mb-0 py-2">Predios a Retirar</p>
                                    @endif
                                </div>
                                <div class=" col-4">
                                    <input type="text"
                                        class="form-control bg-success-subtle  @error('controlIdR') is-invalid @enderror  @error('controlId') is-invalid @enderror"
                                        wire:model.live='controlIdR' placeholder="Control"
                                        @if (!$inChange) hidden @endif
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
                                                    @if (!$inChange)
                                                        <button class="btn p-0"
                                                            wire:click="toLeft({{ $predio->id }})">
                                                            <i class='bi bi-arrow-left-square-fill'></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            @if ($controlIdR)
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
                            <div class="card-footer text-end">
                                <input class="form-control d-inline-block w-50" name="sum_coef"
                                    value="{{ $sumCoefR }}" id="sumCoef" readonly>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function noNumbers(event) {
        var charCode = event.charCode;
        if (charCode >= 48 && charCode <= 57) {
            return false; // Bloquear el input si es un número
        }
        return true; // Permitir el input si no es un número
    }

    function onlyNumbers(event) {
        var charCode = event.charCode;
        if (charCode < 48 || charCode > 57) {
            return false; // Bloquear el input si no es un número
        }
        return true; // Permitir el input si es un número
    }
</script>
