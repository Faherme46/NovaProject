

@extends('layout.app')

@section('content')

    <div class="row">


        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">{{ $asambleaOn->folder }}</h2>
                    <h6>{{ $asambleaOn->fecha }} {{ $asambleaOn->hora }}</h6>
                    <h6>Controles: {{ $asambleaOn->controles }}</h6>
                </div>
                <div class="card-footer d-flex align-items-center">
                    
                    <form action="{{ route('session.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mt-3">
                            Eliminar sesion
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-3">
               @livewire('list-users', ['height100' => false])
            </div>

        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h2 class="card-title">Archivo importado</h2>
                </div>
                <div class="card-body table-responsive table-fixed-header table-h100">
                    <table class="table table-bordered mt-3 mb3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @if ($asambleaOn->registro)
                                    <th>propietario</th>
                                    <th>cedula</th>
                                    <th>Apoderado</th>
                                @endif

                                <th>Descriptor </th>
                                <th>Coef...</th>
                                <th>Vota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($predios))
                                @forelse ($predios as $p)
                                    <tr>
                                        <td>{{ $p->id }}</td>
                                        @if ($asambleaOn->registro)
                                            <td>{{ $p->persona->nombre }} {{ $p->persona->apellido }}</td>
                                            <td>{{ $p->cc_propietario }}</td>
                                            <td>{{ $p->cc_apoderado }}</td>
                                        @endif

                                        <td>{{ $p->descriptor1 }} {{ $p->numeral1 }} {{ $p->descriptor2 }}
                                            {{ $p->numeral2 }}</td>
                                        <td>{{ $p->coeficiente }}</td>
                                        <td>{{ ($p->vota)?'Si':'No' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No hay entradas</td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="7"> No se ha seleccionado archivo</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>




    </div>








@endsection
