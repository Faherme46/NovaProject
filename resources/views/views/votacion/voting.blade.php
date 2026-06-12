<div class="col-12">
    <x-alerts />

    <div class="card mt-2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="col-4">
                <div class="d-flex align-items-center">

                    <button class="btn btn-primary  rounded-3 me-2" wire:click='goPresent'data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Volver  ">
                        <i class="bi bi-arrow-left-circle-fill fs-3"></i>
                    </button>
                    <a href="{{ route('questions.view', ['questionId' => $question->id, 'inRondas' => $inRondas, 'currentRonda' => $dataRondas['currentRonda']]) }}"
                        target="_blank" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        data-bs-custom-class="custom-tooltip" data-bs-title="Ver Pregunta">
                        <i class="bi bi-card-list fs-3"></i>
                    </a>
                    @if ($inRondas)
                        <h4 class="mb-0 ms-2 text-center text-primary">Ronda {{ $dataRondas['currentRonda'] }}</h4>
                    @endif
                </div>

            </div>
            <div class="col-4 ">
                <span style="white-space: nowrap">
                    <img src="/storage/images/loguito.png" style="width: 4rem;" alt="logo">
                    <img src="/storage/images/letras.png" style="height: 3.5rem;" alt="logo">
                </span>
            </div>
            <div class="col-4 justify-content-end d-flex">
                <h1 class="mb-0 me-3" id="temporizador">
                    {{ $countdown }}
                </h1>
                <button class="btn btn-warning p-0 rounded-3 me-2" onclick="pausarTemporizador()" id="pause-button"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                    data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="Pausar">

                    @if ($stopped)
                        <i class="bi bi-play-fill fs-2 py-0 px-1"></i>
                    @else
                        <i class="bi bi-pause-fill fs-2 py-0 px-1"></i>
                    @endif
                </button>
                <button class="btn btn-danger p-0 rounded-3" wire:click='stopVote'data-bs-toggle="tooltip"
                    data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Detener">
                    <i class="bi bi-stop-fill fs-2 px-1"></i>
                </button>
            </div>
        </div>
        <div class="card-body " @if (!$stopped) wire:poll.1000ms='updateVotes' @endif>
            <div class=" p-0 mx-0">
                @foreach ($controls as $id => $control)
                    <span
                        class="btn  ms-0 mb-1 me-0 fs-2
                    @if ($control['voted'] == 1) btn-secondary
                    @elseif ($control['vote'] != null) btn-primary
                    @elseif($control['state'] == 1)) btn-outline-primary underline @else btn-black @endif ">
                        {{ $id < 10 ? '0' : '' }}{{ $id }}
                    </span>
                @endforeach
            </div>

        </div>
    </div>
    <div class="modal fade" id="modalConfirm" data-bs-keyboard="false" tabindex="-1" wire:ignore.self >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Se ha acabado el tiempo.
                        @if ($inRondas)
                            {{ $dataRondas['currentRonda'] < $dataRondas['numRondas'] ? ' Continuar a la ronda ' . $dataRondas['currentRonda'] + 1 : '' }}
                        @endif
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" wire:click='oneMoreMinut'>
                        +1 min
                    </button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" data-bs-toggle="modal"
                        data-bs-target="#spinnerModal" wire:click='store()'>
                        @if ($inRondas)
                            Continuar
                        @else
                            Guardar
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="spinnerModal" tabindex="-1" wire:ignore>
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="spinner-grow text-primary" style="width: 4rem; height: 4rem;" role="status">

                    </div>
                    <span class="ms-3 " style="font-size: 5rem">Cargando...</span>
                </div>

            </div>
        </div>
    </div>
</div>
@script
    <script>
        $wire.on('modal-show', async () => {

            setTimeout(() => {

                $('#modalConfirm').modal('toggle');
            }, 500);
        });
        $wire.on('modal-close', () => {

            setTimeout(() => {

                $('#modalConfirm').modal('hide');
            }, 500);
        });
        $wire.on('modal-spinner-close', () => {
            $('#spinnerModal').modal('hide');
            fetch("http://localhost:5000/stop-votes", {
                method: 'GET',
                // mode: 'no-cors' si estás evitando CORS, pero ojo, limita headers/respuesta
            }).catch(err => console.log('No se pudo enviar, pero seguimos: ', err));
        })
        $wire.on('modal-all-close', () => {
            $('#modalConfirm').modal('hide');
            $('#spinnerModal').modal('hide');
        })

        $wire.on('pause-timer', () => {
            clearInterval(timeInterval);
        });

        window.intervalo = null;
        window.paused = false;
        $wire.on('iniciarTemporizador', (event) => {

            if (window.intervalo) {
                clearInterval(window.intervalo);
            }


            let tiempo = $wire.seconds;
            window.intervalo = setInterval(() => {
                if (window.paused == true) {
                    return;
                }
                tiempo--;
                const minutos = Math.floor(tiempo / 60);
                const segundos = tiempo % 60;



                $wire.countdown = `${minutos}:${segundos.toString().padStart(2, '0')}`;
                if (tiempo <= 0) {
                    clearInterval(window.intervalo);
                    window.intervalo = null;
                    Livewire.dispatch('tiempoAgotado');
                }
            }, 1000);
        });

        window.pausarTemporizador = function() {
            console.log('Pausado');
            window.paused = !window.paused;
            $wire.stopped = window.paused;
        }

        $wire.on('detenerTemporizador', (event) => {
            clearInterval(window.intervalo);
            window.intervalo = null;
            window.paused = false;
        })
    </script>
@endscript
