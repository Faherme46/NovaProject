<div>
    <x-alerts />

    @if ($activeQuestion)
        <div class="alert alert-info">
            Votacion iniciada: {{ $activeQuestion->title }}. No se pueden agregar candidatos ni lanzar otra votacion.
        </div>
    @endif

    <div class="card mb-2">
        <div class="card-header">
            <h5 class="card-title mb-0">Pregunta de eleccion</h5>
        </div>
        <div class="card-body">
            <label class="form-label">Titulo de la votacion</label>
            <input type="text" class="form-control @error('questionTitle') is-invalid @enderror" wire:model="questionTitle" @disabled($activeQuestion)>
            @error('questionTitle')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @if (!$activeQuestion)
            <div class="card-footer text-end">
                <button type="button" class="btn btn-success" wire:click="iniciarVotacion">Iniciar votacion</button>
            </div>
        @endif
    </div>

    <div class="row g-2">
        <div class="col-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Candidatos seleccionados</h5>
                    <span class="badge text-bg-primary">{{ count($candidatos) }}</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Candidato</th>
                                <th>Predios</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($candidatosSeleccionados as $candidato)
                                <tr>
                                    <td class="align-middle">
                                        {{ $candidato->nombre }} {{ $candidato->apellido }}<br>
                                        <small class="text-muted">{{ $candidato->id }}</small>
                                    </td>
                                    <td>
                                        @foreach ($candidato->predios->merge($candidato->prediosEnPoder)->unique('id') as $predio)
                                            <p class="mb-1">
                                                {{ $predio->getFullName() }}
                                                @if (cache('inRegistro') !== false)
                                                    <br>
                                                    <small class="{{ $predio->control ? 'text-success' : 'text-danger' }}">
                                                        Control: {{ $predio->control ? $predio->control->id : 'Sin control' }}
                                                    </small>
                                                @endif
                                            </p>
                                        @endforeach
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-danger btn-sm" wire:click="quitarCandidato({{ $candidato->id }})" @disabled($activeQuestion)>
                                            Quitar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Sin candidatos seleccionados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @error('candidatos')
                        <div class="text-danger p-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Buscar personas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <input type="text" class="form-control" placeholder="Nombre o cedula" wire:model.live.debounce.300ms="search" @disabled($activeQuestion)>
                    </div>

                    <div class="table-responsive" style="max-height: 520px;">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Persona</th>
                                    <th class="text-end">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($personas as $persona)
                                    <tr>
                                        <td>
                                            {{ $persona->nombre }} {{ $persona->apellido }}<br>
                                            <small class="text-muted">{{ $persona->id }}</small>
                                        </td>
                                        <td class="text-end align-middle">
                                            <button type="button" class="btn btn-primary btn-sm" wire:click="agregarCandidato({{ $persona->id }})" @disabled($activeQuestion || in_array($persona->id, $candidatos))>
                                                Agregar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">Sin resultados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>