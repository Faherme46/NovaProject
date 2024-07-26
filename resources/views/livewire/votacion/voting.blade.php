<div class="col-12">
    <div class="card mt-2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="col-2">
                <button class="btn btn-primary px-1 rounded-3" wire:click='goPresent'>
                    <i class="bi bi-arrow-left-circle-fill fs-3"></i>
                </button>
            </div>
            <div class="col-2">
                <h1 class="mb-0">
                    {{ $countdown }}
                </h1>
            </div>
            <div class="col-2 justify-content-end d-flex">
                <button class="btn btn-warning py-0 rounded-3 me-2" wire:click='proof1'>
                    proof
                </button>
                <button class="btn btn-success py-0 rounded-3 me-2" wire:click='createResults'>
                    results
                </button>
                <button class="btn btn-info py-0 rounded-3 me-2" wire:click='proofGenerateResults'>
                    Generar
                </button>
                <button class="btn btn-warning py-0 rounded-3 me-2" wire:click='playPause({{ !$stopped }})'>

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
            @foreach ($controls as $control)
                <span class="btn  ms-0 mb-1 me-0 fs-2
                    @if (array_key_exists($control, $votes)) btn-primary
                    @elseif( array_key_exists($control, $controlsAssigned)) btn-secondary @else btn-black @endif ">
                    {{ $control < 10 ? '0' : '' }}{{ $control}}
                </span>
            @endforeach
        </div>
    </div>
    <div class="modal fade" id="modalConfirm"  data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Se ha acabado el tiempo
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" wire:click='oneMoreMinut'>+1
                        min</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                        wire:click='store'>Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@script
    <script>

        $wire.on('modal-show', async() => {


            await $wire.sleep(1);
            $('#modalConfirm').modal('toggle');
        });
        $wire.on('modal-close', () => {

            $('#modalConfirm').modal('hide');
        });

        $wire.on('pause-timer', () => {
            clearInterval(timeInterval);

        });

        $wire.on('start-timer', () => {
            startTimer();
        });

        function startTimer() {
            timeInterval = setInterval(() => {
                $wire.decrement()
            }, 1000)
        }
    </script>
@endscript
