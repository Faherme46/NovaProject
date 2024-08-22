<div>
    <div class="row justify-content-center px-5">

        @foreach ($panels as $panel)
            @if ($panel['visible'])
                <button class="btn p-0 mx-1 my-1 " style="width: 300px;" {{ $panel['directives'] }}
                    @disabled(!$panel['enabled'])>
                    <div class="card ">
                        <div class="row g-0">
                            <div class="col-4">
                                <i class="bi {{ $panel['icon'] }}" style="font-size:80px"></i>
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $panel['title'] }}</h5>
                                    <p class="card-text"><small class="text-body-secondary">
                                            {{ $panel['body'] }}
                                        </small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </button>
            @endif
        @endforeach

    </div>

    <div class="modal fade" tabindex="-1" id="modalCreateAsamblea" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <form class="" id="asamblea-form" action="{{ route('asambleas.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Programar Asamblea</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row px-4 pb-2">


                        <div class="form-group col-12">
                            <label for="folder">Cliente</label>
                            <select id="folder" class="form-select" aria-label="Default select example"
                                name="folder" required>
                                <option value="" disabled selected>Seleccionar un cliente</option>
                                @foreach ($folders as $folder)
                                    <option value="{{ $folder }}">{{ $folder }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" name="name" hidden>
                        <div class="form-group col-12">
                            <label for="lugar">Direccion</label>
                            <input type="text" class="form-control" placeholder="Direccion" id="lugar"
                                name="lugar" required>
                        </div>
                        <div class="form-group col-4">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>


                        <div class="form-group col-4">
                            <label for="hora">Hora</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>




                        <div class="form-group col-6">
                            <label for="ciudad">Ciudad</label>
                            <select id="ciudad" class="form-select" name="ciudad">
                                <option value='Bucaramanga' selected>Bucaramanga</option>
                                <option value='Floridablanca'>Floridablanca</option>
                                <option value='Giron'>Giron</option>
                                <option value='Piedecuesta'>Piedecuesta</option>
                                <option value='Lebrija'>Lebrija</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="controles">Numero de controles</label>
                            <input type="number" class="form-control" id="controles" name="controles"
                                oninput="debouncedValidateMultipleOf50(this)" required>
                        </div>
                        <div class="row mt-2 justify-content-between ">
                            <div class="form-group text-center col-4 ">
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="registro" id="registro1"
                                        value="true" checked>
                                    <label class="btn btn-outline-primary" for="registro1">Registro</label>

                                    <input type="radio" class="btn-check" name="registro" id="registro2"
                                        value="false">
                                    <label class="btn btn-outline-primary" for="registro2">Votacion</label>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        // Función para habilitar/deshabilitar el campo basado en el radio seleccionado
                                        function toggleCampo() {
                                            if (!$('#registro1').is(':checked')) {
                                                $('#signature').prop('checked', false);
                                            }
                                        }

                                        // Inicializa el estado del campo al cargar la página
                                        toggleCampo();

                                        // Escucha el cambio de estado de los radios
                                        $('input[name="registro"]').change(function() {
                                            toggleCampo();
                                        });
                                    });
                                </script>
                            </div>
                            <div class="col-6 ps-2 d-flex align-items-center justify-content-end">
                                <div class="form-check pt-1">
                                    <input class="form-check-input" type="checkbox" value="1" name="signature"
                                        id="signature">
                                    <label class="form-check-label" for="signature">
                                        Firma electronica
                                    </label>
                                </div>
                            </div>

                        </div>


                        <script>
                            // Obtener la fecha y hora actual
                            var today = new Date();
                            var horaActual = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 10, 0, 0);
                            today.setDate(today.getDate() + 1);

                            // Establecer los valores por defecto en los campos de entrada
                            $('#fecha').val(today.toISOString().split('T')[0]);
                            $('#hora').val(horaActual.toTimeString().slice(0, 5));
                            $('#lugar').val('Un lugar bonito')
                            $('#controles').val('100')
                        </script>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="submit" class="btn btn-success w-25" id="submit-button">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if ($asamblea)

        @if ($registro)
            <div class="modal fade" id="modalFilePersonas" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header justify-content-between   ">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Archivo de personas </h1>

                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalFilePredios">
                                Archivo de predios
                            </button>
                        </div>
                        <div class="modal-body table-responsive table-fixed-header table-h100">

                            <table class="table table-bordered table-striped  mt-3 mb3">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cedula</th>
                                        <th>Predios en propiedad</th>
                                        <th>Predios en poder</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($personas as $persona)
                                        <tr>


                                            <td>{{ $persona->nombre }} {{ $persona->apellido }}</td>
                                            <td>{{ $persona->id }}</td>
                                            <td>
                                                @forelse ($persona->predios as $p)
                                                    {{ $p->getFullName() }}
                                                    <br>
                                                @empty
                                                    No hay Predios en propiedad
                                                @endforelse
                                            </td>
                                            <td>
                                                @forelse ($persona->prediosEnPoder as $p)
                                                    {{ $p->getFullName() }}
                                                    <br>
                                                @empty
                                                    No hay Predios en poder
                                                @endforelse
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No hay entradas</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>

            </div>
        @endif


        <div class="modal fade" id="modalFilePredios" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header justify-content-between   ">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Archivo de Predios </h1>
                        @if ($registro)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalFilePersonas">
                                Archivo de Personas
                            </button>
                        @endif


                    </div>
                    <div class="modal-body table-responsive table-fixed-header table-h100 pt-0">

                        <table class="table table-bordered table-striped   mb-3">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Descriptor </th>
                                    <th>Coef...</th>
                                    <th>Vota</th>
                                    @if ($asamblea['registro'])
                                        <th>Propietarios</th>
                                        <th>Cedula</th>
                                        <th>Apoderado</th>
                                    @endif

                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($predios as $p)
                                    <tr>
                                        <td>{{ $p->id }}</td>
                                        <td>{{ $p->getFullName() }}</td>
                                        <td>{{ $p->coeficiente }}</td>
                                        <td>{{ $p->vota ? 'Si' : 'No' }}</td>
                                        @if ($asamblea['registro'])
                                            <td>
                                                @foreach ($p->personas as $persona)
                                                    {{ $persona->nombre }} {{ $persona->apellido }}
                                                    <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($p->personas as $persona)
                                                    {{ $persona->id }}
                                                    <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                {{ $p->cc_apoderado }}
                                            </td>
                                        @endif

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No hay entradas</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>

        </div>
    @endif
</div>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('showModalFilePersona', (event) => {
            $('#modalFilePredios').modal('hide');
            $('#modalFilePersonas').modal('hide');
            $('#modalFilePersonas').modal('show');
        });

        Livewire.on('showModalFilePredio', (event) => {
            $('#modalFilePredios').modal('hide');
            $('#modalFilePersonas').modal('hide');
            $('#modalFilePredios').modal('show');
        });

        Livewire.on('showModalControl', (event) => {
            $('#modalFilePredios').modal('hide');
            $('#modalFilePersonas').modal('hide');
            $('#modalFilePersonas').modal('show');
        });

    });
</script>
