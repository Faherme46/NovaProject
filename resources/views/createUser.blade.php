<link rel="stylesheet" href="{{ asset('assets/scss/crearUsuario.scss') }}">

@extends('layout.app')


@section('content')
    <div class="row">
        <div class="col-7">

            <div class="card mb-3">
                <div class="card-header d-flex">
                    <h5 class="card-title">
                        Crear Usuarios
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.create') }}" method="POST">
                        @csrf

                        <div class="mb-3 row">
                            <div class="col-6">

                                <input type="text" class="form-control" id="nombre" name="name"
                                    value="{{ old('name') }}" required>
                                <small for="nombre" class="form-label">Nombre</small>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control" id="apellido" name="lastname"
                                    value="{{ old('lastname') }}" required>
                                <small for="apellido" class="form-label">Apellido</small>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-6">
                                <input type="text" class="form-control" id="cedula" name="cedula"
                                    value="{{ old('cedula') }}">
                                <small for="cedula" class="form-label">Cédula</small>
                            </div>
                            <div class="col-6">

                                <input type="text" class="form-control" id="telefono" name="telefono"
                                    value="{{ old('telefono') }}">
                                <small for="telefono" class="form-label">Teléfono</small>
                            </div>
                        </div>



                        <div class="mb-3 row">
                            <div class="col-4">

                                <input type="text" class="form-control" id="username" name="username"
                                    value="{{ old('username') }}" required>
                                <small for="username" class="form-label">Nombre de Usuario</small>
                            </div>
                            <div class="col-4">

                                <input type="text" class="form-control" id="password" name="password"
                                    {{ old('password') }} required>
                                <small for="password" class="form-label">Contraseña</small>
                            </div>

                            <div class="col-4">
                                <select name="role" id="" class="form-control">

                                    <option value="Operario" selected>Operario</option>
                                    <option value="Lider">Lider</option>
                                    <option value="Admin">Administrador</option>
                                </select>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
                    </form>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    Importar de archivo
                </div>
                <div class="card-body">

                    <p class="card-text">Se importatan los usuarios contenidos en la ruta: C:/Asambleas/usuarios.xlsx
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


        </div>
        <div class="col-5">
            <livewire:list-users />
        </div>
    </div>
@endsection
