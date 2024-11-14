<div >
   <x-alerts/>

    <div class="col-12">
        <div class="card">
            <div class="card-header px-0">
                <div class=" d-flex px-2  justify-content-start">
                    <div class="col-1">
                        <button class="btn btn-danger" wire:click='cleanData'>
                            <i class='bi bi-trash-fill '></i>
                        </button>
                    </div>
                    <div class="col-10 text-center">
                        <h2 class="card-title mb-0">Asignacion de predios</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-1">
                    <div class="col-6">
                        <div class="card  p-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="col-auto">
                                    Predios a Asignar
                                </div>
                                <div class=" col-auto">
                                    <button class="btn btn-danger px-2 py-1" wire:click='dropAllSelected'>
                                        <i class="bi bi-x-square-fill fs-6"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body table-responsive table-fixed-header table-70">
                                <table class="w-100 table mb-0 ">

                                    <tbody>
                                        @forelse($predioSelected as $predio)
                                            <tr scope="row" style="width: 85%">
                                                <td>{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                    {{ $predio->descriptor2 }} {{ $predio->numeral2 }}</td>
                                                <td style="text-align: center;">
                                                    <button class="btn p-0"
                                                        wire:click="dropPredio({{ $predio->id }})">
                                                        <i class='bi bi-x-square-fill'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            @if ($controlId)
                                                <tr>
                                                    <td colspan="2">Sin predios para asignar</td>
                                                </tr>
                                            @endif
                                        @endforelse
                                        @if ($control)
                                            @foreach ($prediosAsigned as $predio)
                                                <tr scope="row" class="table-active">
                                                    <td colspan="2">{{ $predio->descriptor1 }}
                                                        {{ $predio->numeral1 }}
                                                        {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="col-5 align-items-center my-auto mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-center">
                                    <input type="text"
                                        class="form-control bg-success-subtle w-50 @error('controlId') is-invalid @enderror"
                                        wire:model.live='controlId' placeholder="Control" onclick="this.select()"
                                        onkeypress="return onlyNumbers(event)" maxlength="3">
                                    <button class="btn btn-primary ms-2 " wire:click='asignar'>
                                        Asignar
                                    </button>
                                </div>
                            </div>
                            <div class="card-body  px-0">
                                <div class="d-flex px-2  justify-content-between">
                                    <div class=" col-5">
                                        <input class="form-control " name="sum_coef" value="{{ $sumCoefA }}"
                                            id="sumCoef" readonly>
                                        <small id="helpId" class="text-muted ms-3">Coeficiente</small>
                                    </div>

                                    <div class="col-5 ">
                                        <input class="form-control "
                                            value="{{ count($predioSelected) + count($prediosAsigned) }}"
                                            id="votos" readonly>
                                        <small id="helpId" class="text-muted ms-3">Registros</small>
                                    </div>
                                </div>
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
