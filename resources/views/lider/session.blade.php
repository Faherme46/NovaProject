<link rel="stylesheet" href="{{ asset('assets/scss/creaasamblea.scss') }}">

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
                    <form action="{{ route('asambleas.inicia') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_asamblea" value="{{ $asambleaOn->id_asamblea }}">
                        <button type="submit" class="btn btn-danger mt-3 me-3">
                            Iniciar
                        </button>
                    </form>
                    <form action="{{ route('asambleas.termina') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_asamblea" value="{{ $asambleaOn->id_asamblea }}">
                        <button type="submit" class="btn btn-warning mt-3 me-3">
                            Terminar
                        </button>
                    </form>
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
                <livewire:list-users />
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
                                <th>propietario</th>
                                <th>cedula</th>
                                <th>Apoderado</th>
                                <th>Descriptor </th>
                                <th>Coef...</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($predios))
                                @forelse ($predios as $p)
                                    <tr>
                                        <td>{{ $p->id }}</td>
                                        <td>{{ $p->persona->nombre }} {{ $p->persona->apellido }}</td>
                                        <td>{{ $p->cc_propietario }}</td>
                                        <td>{{ $p->cc_apoderado }}</td>
                                        <td>{{ $p->descriptor1 }} {{ $p->numeral1 }} {{ $p->descriptor2 }}
                                            {{ $p->numeral2 }}</td>
                                        <td>{{ $p->coeficiente }}</td>
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




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('asamblea-form');
            const formTitle = document.getElementById('form-title');
            const submitButton = document.getElementById('submit-button');

            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nombre = this.dataset.nombre;
                    const lugar = this.dataset.lugar;
                    const fecha = this.dataset.fecha;
                    const hora = this.dataset.hora;
                    const estado = this.dataset.estado;
                    const nombreBd = this.dataset.nombrebd;

                    form.action = `asambleas/${id}`; // Correcto
                    form.method = 'POST';

                    document.getElementById('asamblea-id').value = id;
                    document.getElementById('nombre').value = nombre;
                    document.getElementById('lugar').value = lugar;
                    document.getElementById('fecha').value = fecha;
                    document.getElementById('hora').value = hora;
                    document.getElementById('estado').value = estado;

                    formTitle.textContent = 'Editar asamblea';
                    submitButton.textContent = 'Actualizar asamblea';

                    const hiddenMethodInput = document.createElement('input');
                    hiddenMethodInput.type = 'hidden';
                    hiddenMethodInput.name = '_method';
                    hiddenMethodInput.value = 'PUT';
                    form.appendChild(hiddenMethodInput);
                });
            });
        });
    </script>



@endsection
