<div>

    <div class="d-flex me-3">
        <x-alerts />

        <div class="col-6 me-3">

            <div class="card mb-3">
                <div class="card-header d-flex">
                    <h5 class="card-title mb-0">
                        Crear Usuarios
                    </h5>
                </div>
                <form action="{{ route($editting ? 'users.update' : 'users.create') }}" method="POST">
                    <div class="card-body pb-0">

                        @csrf
                        <input type="text" class="form-control" id="idUser" name="idUser" hidden>
                        @if ($editting)
                            <input type="hidden" class="form-control" id="role" name="role" value="Operario"
                                hidden>
                        @endif
                        <div class="mb-2 row">
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Nombre</span>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Apellido</span>
                                    <input type="text" class="form-control" id="lastname" name="lastname"
                                        value="{{ old('lastname') }}" >
                                </div>
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-5">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Cédula</span>
                                    <input type="text" class="form-control" id="cedula" name="cedula"
                                        value="{{ old('cedula') }}" >
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Teléfono</span>
                                    <input type="text" class="form-control" id="telefono" name="telefono"
                                        value="{{ old('telefono') }}" >
                                </div>

                            </div>

                        </div>
                        <div class="mb-2 row">
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Username</span>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="{{ old('username') }}" required @readonly($editting)>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Contraseña</span>
                                    <input type="text" class="form-control" id="password" name="password"
                                        value="{{ old('password') }}" required>
                                </div>
                            </div>



                        </div>


                    </div>
                    <div class="card-footer d-flex justify-content-between">

                        <div class="input-group ms-3 w-30 ">
                            <span class="input-group-text bg-primary text-light">Rol</span>
                            <select name="role" id="role" class="form-control" >
                                <option value="Terminal" selected>Terminal</option>
                                <option value="Operario" selected>Operario</option>
                                <option value="Lider">Lider</option>
                                @if ($isAdmin)
                                    <option value="Admin">Administrador</option>
                                @endif
                            </select>
                        </div>
                        <button type="submit"
                            class="btn btn-primary">{{ $editting ? 'Guardar Cambios' : 'Crear Usuario' }}</button>
                    </div>
                </form>
            </div>

            @if ($showImport)
                <div class="card">
                    <div class="card-header">
                        Importar de archivo (Solo servidor)
                    </div>
                    <div class="card-body ">

                        <p class="card-text">Se importatan los usuarios contenidos en la ruta:
                            C:/Asambleas/usuarios.xlsx
                        </p>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="card-text">
                                Recuerde que el archivo debe presentar las siguientes columnas
                            </p>
                            <a href="{{ route('users.import') }}"class="btn btn-primary ">Importar</a>
                        </div>

                    </div>
                    <div class="card-footer">
                        <table class="table table-bordered-black">
                            <thead>
                                <tr>
                                    <th class="table-active">nombre</th>
                                    <th>apellido</th>
                                    <th>cedula</th>
                                    <th>telefono</th>
                                    <th class="table-active">username</th>
                                    <th class="table-active">password</th>
                                    <th class="table-active">rol</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            @endif

        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    Usuarios
                </div>
                <div class="card-body table-responsive table-fixed-header table-h100 p-0">

                    <table class="table mb-0">
                        <thead class="table-active">
                            <th class="">
                                <button type="button" wire:click="sortBy('name')"
                                    class="btn w-100 justify-content-between d-flex py-0">
                                    Nombre
                                    @if ($sortField === 'name')
                                        @if ($sortDirection === 'asc')
                                            <i class="bi bi-caret-down-square-fill"></i>
                                        @else
                                            <i class="bi bi-caret-up-square-fill"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="">
                                <button type="button" wire:click="sortBy('username')"
                                    class="btn w-100 justify-content-between d-flex py-0">
                                    Username
                                    @if ($sortField === 'username')
                                        @if ($sortDirection === 'asc')
                                            <i class="bi bi-caret-down-square-fill"></i>
                                        @else
                                            <i class="bi bi-caret-up-square-fill"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th>Contraseña</th>
                            <th class="">
                                <button type="button" wire:click="sortBy('roleTxt')"
                                    class="btn w-100 justify-content-between d-flex py-0">
                                    Rol
                                    @if ($sortField === 'role')
                                        @if ($sortDirection === 'asc')
                                            <i class="bi bi-caret-down-square-fill"></i>
                                        @else
                                            <i class="bi bi-caret-up-square-fill"></i>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="text-center"><i class="bi bi-pencil"></i></th>
                            <th class="text-center"><i class="bi bi-trash"></i></th>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="ps-3">{{ $user->name }} {{ $user->lastName }}</td>
                                    <td>{{ $user->username }}</td>
                                    <th>{{ $user->passwordTxt }}</th>
                                    <td>{{ $user->getRoleNames()->first() }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning p-0 px-1"
                                            wire:click='editUser({{ $user->id }})'>
                                            <i class="bi bi-pencil bx-b"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger p-0 px-1"
                                            wire:click='confirmDelete({{ $user->id }})'>
                                            <i class="bi bi-trash bx-w"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog  modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">¿Desea eliminar el usuario?</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('users.delete') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $toDeleteId }}">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-warning">Eliminar</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@script
    <script>
        $wire.on('show-modal-delete', () => {
            $('#modalDelete').modal('hide');
            $('#modalDelete').modal('show');
        });

        $wire.on('set-edit-values', (event) => {
            $('#lastname').val(event.user.lastName);
            $('#name').val(event.user.name);
            $('#cedula').val(event.user.cedula);
            $('#telefono').val(event.user.telefono);
            $('#username').val(event.user.username);
            $('#password').val(event.user.passwordTxt);
            $('#role').val(event.user.roleTxt);
            $('#idUser').val(event.user.id);
        });
    </script>
@endscript
