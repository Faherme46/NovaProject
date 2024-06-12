@extends('layout.app')



@section('content')
    <link rel="stylesheet" href="assets/scss/registro.scss">
    <div class="container">
        <div class="row">
            <!-- Columna para el formulario de búsqueda -->
            <div class="col-md-4">
                <h2>Buscar Persona por Cédula</h2>
                <form action="{{ route('personas.find') }}" method="GET">
                    <div class="mb-3">
                        <label for="cedula" class="form-label">Cédula</label>
                        <input type="text" class="form-control" id="cedula" name="cedula" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
                <!-- Mostrar el nombre de la persona encontrada aquí -->
            </div>

            <!-- Columna para la tabla de predios -->
            <div class="col-md-4">
                <h2>Predios Asignados</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Coeficiente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($predios))
                            @forelse ($predios as $predio)
                                <tr>
                                    <td>{{ $predio->descripcion }}</td>
                                    <td>{{ $predio->coeficiente }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Sin predios</td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="2">Sin predios</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Columna para la tabla de asignación de predios a control -->
            <div class="col-md-4">
                <h2>Asignar Predios a Control</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Coeficiente</th>
                            <th>Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($predios))
                            @forelse ($predios as $predio)
                                <tr>
                                    <td>{{ $predio->descripcion }}</td>
                                    <td>{{ $predio->coeficiente }}</td>
                                    <td>
                                        <input type="checkbox" name="predios[]" value="{{ $predio->id_predio }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Sin predios</td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="2">Sin predios</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
                <form action="{{ route('registro.asignaPredios') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="control" class="form-label">Número de Control</label>
                        <input type="text" class="form-control" id="control" name="control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Asignar</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    Launch demo modal
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/registro.js"></script>
@endsection
