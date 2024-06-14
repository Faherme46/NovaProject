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

        <div class="row d-flex">
            <div class="card col-md-4">
                <div class="card-header">
                    <h3 id="form-title">Crear Nueva asamblea</h3>
                </div>
                <div class="card-body">
                    <form class="row g-3" id="asamblea-form" action="{{ route('asambleas.store') }}" method="POST">
                        @csrf
                        <div class="form-group col-12">
                            <label for="folder">Cliente</label>
                            <select class="form-select" aria-label="Default select example" name="folder" required >
                                <option value="">Seleccionar un cliente</option>
                                @foreach ($folders as $folder)
                                    <option value="{{ $folder }}">{{ $folder }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" name="name" hidden>
                        <div class="form-group col-12">
                            <label for="lugar">Direccion</label>
                            <input type="text" class="form-control" placeholder="Direccion" id="lugar" name="lugar" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control"  id="fecha" name="fecha" required >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ciudad">Ciudad</label>
                            <select id="ciudad" class="form-select" name="ciudad">
                                <option value='Bucaramanga' selected>Bucaramanga</option>
                                <option value='Floridablanca' >Floridablanca</option>
                                <option value='Giron' >Giron</option>
                                <option value='Piedecuesta' >Piedecuesta</option>
                                <option value='Lebrija' >Lebrija</option>
                              </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="hora">Hora</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="controles">Numero de controles</label>
                            <input type="number" class="form-control" id="controles" name="controles" required>
                        </div>
                        <script>
                            // Obtener la fecha y hora actual
                            var fechaActual = new Date().toISOString().split('T')[0];
                            var horaActual = new Date().toTimeString().slice(0, 5);

                            // Establecer los valores por defecto en los campos de entrada
                            $('#fecha').val(fechaActual);
                            $('#hora').val(horaActual);
                            $('#lugar').val('Un lugar bonito')
                            $('#controles').val('100')
                        </script>

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

            {{-- mostrar el archivo seleccionado --}}
            {{-- <div class="col-md-7 card ms-4">

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
                                <th>Ds 1 </th>
                                <th>Nm 1 </th>
                                <th>Ds 2 </th>
                                <th>Nm 2 </th>
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
                                        <td>{{ $p->descriptor1 }}</td>
                                        <td> {{ $p->numeral1 }}</td>
                                        <td> {{ $p->descriptor2 }}</td>
                                        <td> {{ $p->numeral2 }}</td>
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


            </div> --}}





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
