<div class="">
    <div class="pt-2">
        <x-alerts />
    </div>
    <div class="row g-3 justify-content-start">


        @if (!$inAsamblea)
            <div class="col-5">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title mb-0">Programar Asamblea</h2>
                    </div>
                    <div class="card-body ">
                        <form id="asamblea-form" action="{{ route('asambleas.store') }}" method="POST">
                            @csrf
                            <div class="row">


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
                                <div class="form-group col-6">
                                    <label for="fecha">Fecha</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                                </div>


                                <div class="form-group col-5">
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
                                <div class="form-group col-5">
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
                                                        $('#signature').prop('disabled', true);
                                                    } else {
                                                        $('#signature').prop('disabled', false);
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
                                            <input class="form-check-input" type="checkbox" value="1"
                                                name="signature" id="signature">
                                            <label class="form-check-label" for="signature">
                                                Firma electronica
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-2">
                                    <button type="submit" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#spinnerModal"> Guardar</button>
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
                        </form>

                    </div>
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="card-title mb-0">Archivo de @if (!$inPredios)Personas @else Predios @endif</h2>
                        @if ($asamblea['registro'])
                            
                        <button type="button" class="btn btn-primary fs-4 py-1" wire:click='setInPredios'>
                            @if (!$inPredios)
                            Personas
                            @else
                            Predios
                            @endif
                        </button>
                        @endif
                    </div>
                    <div class="card-body table-responsive p-0 table-fixed-header table-70">
                        @if (!$inPredios)
                            <table class="table table-bordered table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th class="fs-5">ID</th>
                                        <th class="fs-5">Descriptor </th>
                                        <th class="fs-5">Coeficiente</th>
                                        <th class="fs-5">Vota</th>
                                        <th class="fs-5">Grupo</th>
                                        @if ($asamblea['registro'])
                                            <th class="fs-5">Propietarios</th>
                                            <th class="fs-5">Cedula</th>
                                            <th class="fs-5">Apoderado</th>
                                        
                                        @endif

                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($predios as $p)
                                        <tr>
                                            <td>{{ $p->id }}</td>
                                            <td class="align-middle">{{ $p->getFullName() }}</td>
                                            <td>{{ $p->coeficiente }}</td>
                                            <td>{{ $p->vota ? 'Si' : 'No' }}</td>
                                            <td>{{$p->group!='0'?$p->group:''}}</td>
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
                        @else
                            <table class="table table-bordered table-striped mb-0 ">
                                <thead>
                                    <tr>
                                        <th>
                                            <button type="button" class="btn btn-black  p-0 fs-5"
                                                wire:click='orderPersonasByName'>
                                                Nombre
                                            </button>
                                        </th>
                                        <th>
                                            <button type="button" class="btn btn-black  p-0 fs-5"
                                                wire:click='orderPersonasByCc'>
                                                Cedula
                                            </button>
                                        </th>
                                        <th class="fs-5">Predios en propiedad</th>
                                        <th class="fs-5">Predios en poder</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($personas as $persona)
                                        <tr>


                                            <td class="align-middle">{{ $persona->fullName() }}</td>
                                            <td class="align-middle">{{ $persona->id }}</td>
                                            <td>
                                                @forelse ($persona->predios as $p)
                                                    {{ $p->getFullName() }}
                                                    <br>
                                                @empty
                                                    -
                                                @endforelse
                                            </td>
                                            <td>
                                                @forelse ($persona->prediosEnPoder as $p)
                                                    {{ $p->getFullName() }}
                                                    <br>
                                                @empty
                                                    -
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
                        @endif

                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="spinnerModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="spinner-grow text-primary" style="width: 4rem; height: 4rem;" role="status">

                    </div>
                    <span class="ms-3 " style="font-size: 5rem">Cargando ...</span>
                </div>

            </div>
        </div>
    </div>
</div>
