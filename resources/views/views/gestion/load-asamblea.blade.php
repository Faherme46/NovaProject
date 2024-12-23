<div>
    <x-alerts />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h1 class="card-title mb-0">
                Asambleas Guardadas
            </h1>
        </div>
        <div class="card-body">
            <div class="list-group pe-0 border mt-3" style="max-height: 50vh; overflow-y: auto;">
                <table class="table table-bordered mb-0">
                    <thead class="table-active ">
                        <th class="fs-2 text-center">Cliente</th>
                        <th class="fs-2 text-center">Fecha</th>
                        <th class="fs-2 text-center">Hora</th>
                        <th class="fs-2 text-center">Tipo</th>
                        <th class="fs-2 text-center" > </th>
                    </thead>
                    <tbody>
                        @foreach ($asambleas as $a)
                            <tr>
                                <td class="fs-3">{{ $a->folder }}</td>
                                <td class="fs-3">{{ $a->fecha }}</td>
                                <td class="fs-3">{{ $a->hora }}</td>
                                <td class="fs-3 text-center">
                                    <span class="badge bg-primary">
                                        {{ $a->registro ? 'Registro' : 'Solo Votación' }}
                                    </span>
                                </td>
                                <td class="fs-3 text-center">
                                    <button type="button" class="btn fs-5 btn-info"
                                        wire:click='setNameAsamblea("{{ $a->name }}")'>
                                        <i class="bi bi-upload"></i>
                                        Cargar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="loadModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Cargar Informacion de
                        <span class="badge m-0 ms-1  text-bg-primary fs-5 ">{{ $asambleaName }}</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer justify-content-between align-items-center">
                    <span class="badge m-0 text-bg-info fs-6 ">Se cargara la informacion de la asamblea</span>
                    <form action="{{ route('backup.restore') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="{{ $asambleaName }}">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">CARGAR</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('asamblea.delete') }}" method="POST">
                    @method('DELETE')
                    @csrf

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                            Eliminar TODA la información de
                            <span class="badge m-0 ms-1  text-bg-primary fs-5 ">{{ $asambleaName }}</span>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <p>


                                    Se limpiaran las tablas y se borrará el registro de la asamblea
                                    <span class="m-0 ms-1  text-primary fs-5 ">{{ $asambleaName }}</span>
                                    se recomienda eliminar la carpeta de la asamblea de la Carpeta: "C:/Asambleas"
                                </p>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-8">
                                <h5>
                                    Se requiere la contraseña del administrador para esta accion
                                </h5>

                            </div>
                            <div class="col-4">
                                <input type="password" class="form-control" name="password" placeholder="Contraseña"
                                    required>
                                <input type="hidden" name="name" value="{{ $asambleaName }}">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between align-items-center">
                        <span class="badge m-0 text-bg-warning fs-6 ">Esta accion no se puede deshacer</span>

                        <input type="hidden" name="name" value="{{ $asambleaName }}">
                        <span>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">ELIMINAR</button>
                        </span>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@script
    <script>
        $wire.on('loadModalShow', () => {
            $('#loadModal').modal('show');
        });
        $wire.on('loadModalHide', () => {
            $('#loadModal').modal('hide');
        });
        $wire.on('deleteModalShow', () => {
            $('#deleteModal').modal('show');
        });
        $wire.on('deleteModalHide', () => {
            $('#deleteModal').modal('hide');
        });
    </script>
@endscript
