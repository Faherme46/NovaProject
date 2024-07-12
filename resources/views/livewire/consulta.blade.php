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
                        <button class="btn btn-primary @if ($inChange) btn-info @endif ms-2" wire:click='setInChange(true)'>
                            Cambiar
                        </button>
                        <button class="btn btn-primary @if (!$inChange) btn-info @endif ms-2" wire:click='setInChange(false)'>
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
                                    <input type="text" class="form-control bg-success-subtle  @error('controlId') is-invalid @enderror"
                                    wire:model.live='controlIdL' placeholder="Control"
                                    onkeypress="return onlyNumbers(event)" maxlength="3">
                                </div>
                            </div>
                            <div class="card-body table-responsive table-fixed-header">
                                <table class="w-100 table mb-0 ">

                                    <tbody>
                                        @foreach ($prediosL as $predio)
                                            <tr scope="row">
                                                <td style="width: 85%">{{ $predio->descriptor1 }}
                                                    {{ $predio->numeral1 }}
                                                    {{ $predio->descriptor2 }} {{ $predio->numeral2 }}</td>

                                                <td>
                                                    <button class="btn p-0"
                                                        wire:click="toRight({{ $predio->id }})">
                                                        <i class='bi bi-arrow-right-square-fill'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer text-end">
                                <input class="form-control d-inline-block w-50" name="sum_coef"
                                    value="{{ $sumCoefL }}" id="sumCoef" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 align-items-center my-auto mx-auto">
                        <div class="card p-2 mb-2">
                            <button class="btn btn-warning ps-2 mb-4 py-0" wire:click='undo'> <i
                                class="bi bi-arrow-counterclockwise fs-4 "></i> </button>
                            <button class="btn btn-primary ps-2 mb-2 py-0" wire:click='toLeftAll'> <i
                                    class="bi bi-box-arrow-in-left fs-4 "></i> </button>
                            <button class="btn btn-primary ps-0 mb-2 py-0" wire:click='exchange'>
                                <i class="bi bi-arrow-repeat ms-2 fs-4 "></i> </button>
                            <button class="btn btn-primary ps-1 py-0" wire:click='toRightAll'> <i
                                    class="bi bi-box-arrow-in-right fs-4 "></i> </button>
                        </div>
                        <button class="btn btn-success bx-w w-100 py-0" wire:click=''> <i
                            class="bi bi-floppy-fill fs-4 "></i> </button>

                    </div>
                    <div class="col-5">
                        <div class="card p-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="col-auto">
                                    @if ($inChange)
                                        Control B
                                    @else
                                        Predios a Retirar
                                    @endif
                                </div>
                                    <div class=" col-4">
                                        <input type="text" class="form-control bg-success-subtle  @error('controlId') is-invalid @enderror"
                                        wire:model.live='controlIdR' @if ($inChange)  placeholder="Control"  @endif @disabled(!$inChange)
                                        onkeypress="return onlyNumbers(event)" maxlength="3">
                                    </div>
                            </div>
                            <div class="card-body table-responsive table-fixed-header">


                                <table class="w-100 table mb-0 ">

                                    <tbody>
                                        @foreach ($prediosR as $predio)
                                            <tr scope="row" style="width: 85%">
                                                <td>{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                    {{ $predio->descriptor2 }} {{ $predio->numeral2 }}</td>

                                                <td>
                                                    <button class="btn p-0"
                                                        wire:click="toLeft({{ $predio->id }})">
                                                        <i class='bi bi-x-square-fill'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach

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
