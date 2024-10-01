<div>
    <h1>Entrega de Controles</h1>

    <div class="card d-flex p-3 ">
        <div class="col-12 ">
            @foreach ($controls as $control)
                <button class="btn {{ $colors[$control->state] }} mb-2 me-2 fs-3"
                    @if ($control->state != 4) wire:click='confirm({{ $control->id }})' @endif>
                    {{ $control->id }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="modal fade " id="modalConfirm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cambiar estado del Control {{ $controlId }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click='close'></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click='close'>Cancelar</button>
                    <button type="button" wire:click='change(2)' class="btn btn-info">Ausente</button>
                    <button type="button" wire:click='change(5)' class="btn btn-danger">Entregado</button>
                    <button type="button" wire:click='change(1)' class="btn btn-primary">Activo</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('showModalConfirm', (event) => {
            $('#modalConfirm').modal('hide');
            $('#modalConfirm').modal('show');
        });

    });
</script>
