<link rel="stylesheet" href="{{ asset('assets/scss/creaasamblea.scss') }}">

@extends('layout.app')

@section('content')


    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <div class="row">

            <div class="row">
                <div class="col-md-4">
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
                    <h2></h2>
                </div>
                <div class="col-md-8 card">
                    <div class="card-header mt-3 row d-flex align-items-center">
                        <h2 class="card-title">Archivo importado</h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>propietario</th>
                                    <th>cedula</th>
                                    <th>Descriptor </th>
                                    <th>Coeficiente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($predios))
                                    @forelse ($predios as $p)
                                        <tr>
                                            <td>{{ $p->id }}</td>
                                            <td>{{ $p->persona->nombre }}</td>
                                            <td>{{ $p->cc_propietario }}</td>
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
            <p>

            </p>



            <!-- Historial de asambleas -->
            {{-- <div class="col-md-8">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Lugar</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($asambleas as $asamblea)
                            <tr>
                                <td>{{ $asamblea->id_asamblea }}</td>
                                <td>{{ $asamblea->nombre }}</td>
                                <td>{{ $asamblea->lugar }}</td>
                                <td>{{ $asamblea->fecha }}</td>
                                <td>{{ $asamblea->hora }}</td>
                                @if ($asamblea->registro)
                                    <td>Si</td>
                                @else
                                    <td>No</td>
                                @endif

                                <td>{{ $asamblea->nombreBd }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> --}}
            <!-- Modals -->

            <!-- Button trigger modal -->


            <!-- Modal -->

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
