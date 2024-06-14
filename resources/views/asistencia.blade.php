@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Formulario de búsqueda -->
            <div class="col-md-4">
                <h2>Buscar Persona por Cédula</h2>
                <form action="{{ route('asistentes.buscar') }}" method="GET">
                    <div class="mb-3">
                        <label for="cedula" class="form-label">Cédula</label>
                        <input type="text" class="form-control" id="cedula" name="cedula" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
                @isset($persona)
                    <div class="mt-4">
                        <h3>Persona: {{ $persona->nombre }}</h3>
                    </div>
                @endisset
            </div>

            <!-- Tabla de predios -->
            <div class="col-md-4">
                @isset($persona)
                    <h2>Predios Asignados</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Coeficiente</th>
                                <th>Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($persona->predios as $predio)
                                <tr>
                                    <td>{{ $predio->descripcion }}</td>
                                    <td>{{ $predio->coeficiente }}</td>
                                    <td>
                                        <input type="checkbox" name="predios[]" value="{{ $predio->id_predio }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endisset
            </div>

            <!-- Tabla de asignación -->
            <div class="col-md-4">
                @isset($persona)
                    <h2>Asignar Predios a Control</h2>
                    <form action="{{ route('asistentes.asignar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cc_asistente" value="{{ $persona->cedula }}">
                        <div id="selectedPredios"></div>
                        <div class="mb-3">
                            <label for="control" class="form-label">Número de Control</label>
                            <input type="text" class="form-control" id="control" name="control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Asignar</button>
                    </form>
                @endisset
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="predios[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let selectedPredios = document.getElementById('selectedPredios');
                selectedPredios.innerHTML = '';
                document.querySelectorAll('input[name="predios[]"]:checked').forEach(selected => {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'predios[]';
                    input.value = selected.value;
                    selectedPredios.appendChild(input);
                });
            });
        });
    </script>
@endsection
