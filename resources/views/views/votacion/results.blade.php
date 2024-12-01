<div class="col-12">
    <x-alerts />
    <div class="position-fixed w-100 z-2 top-2 end-0 px-4 pt-2 d-flex justify-content-between align-items-center">

        <div class="d-flex">
            <button wire:click='goBack' class="btn me-1 fs-4  btn-primary py-0">
                Volver
            </button>
            <button wire:click='getOut' class="btn btn-danger fs-4 py-0">
                Salir
            </button>

        </div>



        <div class="btn-group" role="group">
            <input type="radio" class="btn-check" wire:model='inCoefResult' value="0" wire:change='$refresh'
                id="btnradio1">
            <label class="btn btn-outline-primary" for="btnradio1">Nominal</label>

            <input type="radio" class="btn-check" wire:model='inCoefResult' value="1" wire:change='$refresh'
                id="btnradio2" checked>
            <label class="btn btn-outline-primary" for="btnradio2">Coeficiente</label>
        </div>
        @if ($this->plancha)
            <div class="d-flex">
                <button wire:click='toPlanchas' class="btn me-1 d-flex align-items-center fs-4 btn-success py-0">
                    <h4 class="mb-0">
                        SIGUIENTE
                    </h4>

                    <span class="bx-w"><i class="bi bi-caret-right-fill"></i></span>
                </button>
            </div>
        @endif

    </div>
    <div class="card mt-2">

        <div class="card-body pt-4 pb-3 px-0 mt-3">

            @if ($inCoefResult)
                <img src="{{ $chartCoef }}" alt="Full Screen Image" style="max-width: 100%">
            @else
                <img src="{{ $chartNom }}" alt="Full Screen Image" style="max-width: 100%">
            @endif


        </div>
    </div>
</div>
