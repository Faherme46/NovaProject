@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Control</th>
                        {{-- <th>Nombre</th> --}}
                        <th>Predios</th>
                        <th>Vota</th>
                        <th>Coeficiente</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($allControls as $control)
                        <tr>
                            <td>{{ $control->id }}</td>
                            {{-- <td>{{ $control->persona->nombre }} {{ $control->persona->apellido }}</td> --}}

                            @foreach ($control->predios as $predio)
                                <td>
                                    {{ $predio->descriptor1 }} {{ $predio->numeral1 }} {{ $predio->descriptor2 }}
                                    {{ $predio->numeral2 }} <br>
                                </td>
                                <td>
                                    {{ $predio->vota ? 'Si' : 'No' }}
                                </td>
                            @endforeach

                            <td>{{ $control->sum_coef }}</td>
                            <td>{{ $control->state }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Sin registros</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    @endsection
