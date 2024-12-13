<div class="col-12">
    <x-alerts />

    <div class="card mt-2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="col-4">
                <div class="d-flex">

                    <button class="btn btn-primary  rounded-3 me-2" wire:click='goPresent'>
                        <i class="bi bi-arrow-left-circle-fill fs-3"></i>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-card-list fs-3"></i>
                    </button>
                </div>

            </div>
            <div class="col-4">
                <img src="/storage/images/loguito.png" style="width: 4rem;" alt="logo">
                <img src="/storage/images/letras.png" style="height: 3.5rem;" alt="logo">
            </div>
            <div class="col-4 justify-content-end d-flex">
                <h1 class="mb-0 me-3">
                    {{ $countdown }}
                </h1>
                <button class="btn btn-warning p-0 rounded-3 me-2" wire:click='playPause({{ !$stopped }})'
                    @if (!$stopped) wire:poll.1000ms='decrement' @endif>

                    @if ($stopped)
                        <i class="bi bi-play-fill fs-2 py-0 px-1"></i>
                    @else
                        <i class="bi bi-pause-fill fs-2 py-0 px-1"></i>
                    @endif
                </button>
                <button class="btn btn-danger p-0 rounded-3" wire:click='stopVote'>
                    <i class="bi bi-stop-fill fs-2 px-1"></i>
                </button>

            </div>
        </div>
        <div class="card-body ">
            <div class=" p-0 mx-0">
                @foreach ($controls as $control)
                    <span
                        class="btn  ms-0 mb-1 me-0 fs-2
                    @if (array_key_exists($control, $votes) && array_key_exists($control, $controlsAssigned)) btn-primary
                    @elseif(array_key_exists($control, $controlsAssigned)) btn-secondary @else btn-black @endif ">
                        {{ $control < 10 ? '0' : '' }}{{ $control }}
                    </span>
                @endforeach
            </div>

        </div>
    </div>
    <div class="modal fade" id="modalConfirm" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Se ha acabado el tiempo
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" wire:click='oneMoreMinut'>
                        +1 min
                    </button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" wire:click='store'
                        data-bs-toggle="modal" data-bs-target="#spinnerModal">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="spinnerModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="spinner-grow text-primary" style="width: 4rem; height: 4rem;" role="status">

                    </div>
                    <span class="ms-3 " style="font-size: 5rem">Cargando Resultados...</span>
                </div>

            </div>
        </div>
    </div>
</div>
@script
    <script>
        $wire.on('modal-show', async () => {

            await $wire.sleep(1);
            $('#modalConfirm').modal('toggle');
        });
        $wire.on('modal-close', () => {
            $('#modalConfirm').modal('hide');

        });
        $wire.on('modal-spinner-close', () => {
            $('#spinnerModal').modal('hide');
        })
        $wire.on('pause-timer', () => {
            clearInterval(timeInterval);
        });
    </script>
@endscript
