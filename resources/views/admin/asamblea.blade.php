<link rel="stylesheet" href="{{ asset('assets/scss/creaasamblea.scss') }}">

@extends('layout.app')




@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">


            <div class="card mb-4">
                <div class="card-header">
                    <h3 id="form-title">Crear Nueva asamblea</h3>
                </div>
                <div class="card-body">
                    <form id="asamblea-form" action="{{ route('asambleas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="form-method" name="_method" value="POST">
                        <input type="hidden" id="asamblea-id" name="id_asamblea">
                        <div class="form-group">
                            <label for="folder">Cliente</label>
                            <select class="form-select" aria-label="Default select example" name="folder" required>
                                <option value="">Seleccionar un cliente</option>
                                @foreach ($folders as $folder)
                                    <option value="{{ $folder }}">{{ $folder }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" id="lugar" name="lugar" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="form-group">
                            <label for="hora">Hora</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>
                        <div class="form-group" hidden>
                            <label for="estado">Estado</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="pendiente" selected>Pendiente</option>
                                <option value="en_progreso">En Progreso</option>
                                <option value="finalizada">Finalizada</option>
                            </select>
                        </div>
                        <div class="form-group ">
                            <div class="row">
                                <div class="form-check col ms-3">
                                    <input class="form-check-input" type="radio" name="registro" id="registro"
                                        value="true" checked>
                                    <label class="form-check-label" for="registro">
                                        Registro
                                    </label>
                                </div>
                                <div class="form-check col">
                                    <input class="form-check-input" type="radio" name="registro" id="registro"
                                        value="false">
                                    <label class="form-check-label" for="registro">
                                        Solo votacion
                                    </label>
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary" id="submit-button">Crear asamblea</button>
                    </form>
                </div>
            </div>



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
