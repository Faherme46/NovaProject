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

        <div class="row">

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">

                                <h2 class="card-title">{{ $asambleaOn->folder }}</h2>
                                <h6>{{ $asambleaOn->fecha }} {{ $asambleaOn->hora }}</h6>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success mt-3">
                                    iniciar
                                </button>
                                <form action="{{ route('session.destroy') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger mt-3">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                        <h2></h2>
                    </div>
                    <div class="col-md-8 card">
                        <div class="card-header mt-3 row d-flex align-items-center">

                            <form method="POST" action="{{ route('propiedades.import') }}"
                                enctype="multipart/form-data" class="d-flex align-items-center">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="file" id="file" class="form-control-file">
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg ms-4">
                                    <i class='bx bxs-download'></i>
                                </button>

                                <button type="button" class="btn btn-danger btn-lg ms-4" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">
                                    <i class='bx bxs-trash'></i>
                                </button>

                            </form>
                            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" >
                                <div class="modal-dialog" >
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de que quieres eliminar el archivo y propiedades cargadas?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('propiedades.destroyAll') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger mt-3">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>




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
                                    @if (isset($propiedades))
                                        @forelse ($propiedades as $p)
                                            <tr>
                                                <td>{{ $p->id }}</td>
                                                <td>nombre</td>
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
