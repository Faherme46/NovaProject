<div>
    <x-alerts />

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="card-title mb-0">Eleccion</h2>
                    @if ($activeQuestion)
                        <span class="badge text-bg-success fs-6">Votacion iniciada</span>
                    @endif
                </div>
                <div class="card-body">
                    <label class="form-label">Titulo de la votacion</label>
                    <input type="text" class="form-control" wire:model="title" @disabled($activeQuestion)>
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    @if ($activeQuestion)
                        <div class="alert alert-info mt-3 mb-0">
                            Ya existe una votacion de eleccion iniciada. No se pueden crear candidatos ni lanzar otras votaciones.
                        </div>
                    @endif
                </div>
                @if (!$activeQuestion)
                    <div class="card-footer text-end">
                        @error('candidateIds')
                            <small class="text-danger me-2">{{ $message }}</small>
                        @enderror
                        <button type="button" class="btn btn-success" wire:click="iniciar">
                            Iniciar votacion
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-6">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">Candidatos seleccionados</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <tbody>
                            @forelse ($selectedCandidates as $candidate)
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>{{ $candidate->nombre }} {{ $candidate->apellido }}</strong>
                                                <div class="text-muted">{{ $candidate->id }}</div>
                                            </div>
                                            @if (!$activeQuestion)
                                                <button type="button" class="btn btn-danger btn-sm" wire:click="dropCandidato({{ $candidate->id }})">
                                                    Quitar
                                                </button>
                                            @endif
                                        </div>

                                        <div class="mt-2">
                                            @foreach ($candidate->predios as $predio)
                                                <div class="border rounded p-2 mb-1">
                                                    {{ $predio->getFullName() }}
                                                    @if ($inRegistro)
                                                        <span class="badge {{ $predio->control ? 'text-bg-success' : 'text-bg-secondary' }} ms-2">
                                                            {{ $predio->control ? 'Control ' . $predio->control->id : 'Sin control' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach

                                            @foreach ($candidate->prediosEnPoder as $predio)
                                                <div class="border rounded p-2 mb-1">
                                                    {{ $predio->getFullName() }} <span class="text-muted">Apoderado</span>
                                                    @if ($inRegistro)
                                                        <span class="badge {{ $predio->control ? 'text-bg-success' : 'text-bg-secondary' }} ms-2">
                                                            {{ $predio->control ? 'Control ' . $predio->control->id : 'Sin control' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>Sin candidatos seleccionados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">Buscar personas</h4>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Nombre o cedula" wire:model="search" wire:keydown.enter="searchPersonas" @disabled($activeQuestion)>
                        <button class="btn btn-primary" type="button" wire:click="searchPersonas" @disabled($activeQuestion)>
                            Buscar
                        </button>
                    </div>

                    <table class="table table-hover table-sm">
                        <tbody>
                            @forelse ($searchResults as $persona)
                                <tr>
                                    <td>
                                        {{ $persona->nombre }} {{ $persona->apellido }}
                                        <div class="text-muted">{{ $persona->id }}</div>
                                    </td>
                                    <td class="text-end align-middle">
                                        <button type="button" class="btn btn-success btn-sm" wire:click="addCandidato({{ $persona->id }})" @disabled($activeQuestion)>
                                            Agregar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>Sin resultados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>