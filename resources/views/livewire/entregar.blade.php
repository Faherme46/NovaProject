<div>
    <h1>Entrega de Controles</h1>

    <div class="card d-flex p-3 ">
        <div class="col-12 ">
            @foreach ($controles as $control)
                <button class="btn @if ($control->asignacion)
                    @if ($control->asignacion->estado==3) btn-warning @else btn-primary @endif @else btn-black @endif mb-2 me-2 fs-3"
                     wire:click='entregar({{$control->id}})'> {{ $control->id }}
                </button>
            @endforeach
        </div>
    </div>
</div>
