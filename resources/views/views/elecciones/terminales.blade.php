<div>
    <x-alerts/>
    <div class="row">
        <div class="col-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h2 class="card-title mb-0">
                        Lista de Terminales
                    </h2>
                    <a href="/elecciones/terminales" class="btn btn-primary">Actualizar</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Ip</th>
                            <th>Host</th>
                            <th class="text-center"><i class="bi bi-trash"></i></th>
                        </thead>
                        <tbody>
                            @forelse ($terminales as $terminal)
                                <tr>
                                    <td class="align-middle">{{ $terminal->id }}</td>
                                    <td class="align-middle">{{ $terminal->usuario->username }}</td>
                                    <td class="align-middle">{{ $terminal->user_name }}</td>
                                    <td>
                                        @if ($terminal->available)
                                            <span class="badge fs-6 text-bg-success">Disponible</span>
                                        @else
                                            <span class="badge fs-6 text-bg-warning">Ocupado</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $terminal->ip_address }}</td>
                                    <td class="align-middle">{{ $terminal->host }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger p-1"
                                            wire:click="selectTerminalToDelete({{ $terminal->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">NO HAY TERMINALES EN SESIÓN</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>
    @if ($terminal)
    <div class="modal fade" id="deleteSessionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Desea cerrar esta sesión de terminal?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $terminal->user_name }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger" wire:click='deleteSession'>
                    Cerrar la sesion
                </button>
            </div>
        </div>
    </div>
</div>
    
        
    @endif
    
</div>
@script
    <script>
        $wire.on('show-delete-modal', () => {
            $('#deleteSessionModal').modal('hide');
            $('#deleteSessionModal').modal('show');
        });
        $wire.on('hide-delete-modal', () => {
            $('#deleteSessionModal').modal('hide');
        });
    </script>
@endscript
