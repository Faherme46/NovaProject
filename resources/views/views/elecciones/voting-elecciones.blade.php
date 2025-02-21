<div class="vh-100 align-content-center ">
    <x-alerts />

    @if ($torre)
        <div class="card">
            <div class="card-header justify-content-center d-flex">
                <h1 class="card-title mb-0" style="font-size: 3.5rem">TORRE {{ strtoupper($torre->name) }}</h1>

            </div>

            <div class="card-body text-center">
                <h3 class="mb-0">Por favor elija un candidato...</h3>
                <div class="row g-3 mt-3 justify-content-center">
                    @foreach ($candidatos as $id => $candidato)
                        <input type="radio" class="btn-check" name="candidato" id="radio-{{ $id }}"
                            value="{{ $id }}" wire:model.live='candidatoId'>
                        <label class="btn btn-outline-success w-45 me-3 fs-3"
                            for="radio-{{ $id }}">{{ $candidato }}</label>
                    @endforeach
                    <input type="radio" class="btn-check" name="candidato" id="radio-w" value="0"
                        wire:model.live='candidatoId'>
                    <label class="btn btn-outline-info w-45 me-3 fs-3" for="radio-w" >VOTO EN BLANCO</label>
                </div>
            </div>
            <div class="card-footer text-center">
                <button class="btn btn-primary fs-4" data-bs-toggle="modal"
                data-bs-target="#confirmModal" @disabled(!$candidatoId)>ACEPTAR</button>
            </div>
        </div>

    @endif

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-3" id="exampleModalLabel">CONFIRMAR</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    @if ($candidatoId)
                        <h3 class="mb-0">SU VOTO PARA TORRE
                            <span class="badge fs-3 text-bg-info">{{ strtoupper($torre->name) }} </span>
                            ES POR <span class="badge mt-1 fs-3 text-bg-primary">{{ $candidatos[$candidatoId]}}</span>
                        </h3>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click='storeVote' >CONTINUAR</button>
                </div>
            </div>
        </div>
    </div>
</div>
