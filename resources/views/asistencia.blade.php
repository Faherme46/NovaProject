@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Control</th>
                            <th>Nombre</th>
                            <th>Predios</th>
                            <th>Coeficiente</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($asignacionesAll as $asignacion)
                            <tr>
                                <td>{{ $asignacion->control_id }}</td>
                                <td>{{ $asignacion->persona->nombre }} {{ $asignacion->persona->apellido }}</td>
                                <td>
                                    @foreach ($asignacion->predios as $predio)
                                        {{ $predio->descriptor1 }} {{ $predio->numeral1 }} {{ $predio->descriptor2 }}
                                        {{ $predio->numeral2 }} <br>
                                    @endforeach
                                </td>
                                <td>{{ $asignacion->sum_coef }}</td>
                                <td>{{ $asignacion->estado }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Sin registros</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
@endsection
