<div class="">
    <div class="pt-2">
        <x-alerts />
    </div>
    <div class="row g-3 justify-content-center">

        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0">Programar Elecciones</h2>
                </div>
                @if ($inAsamblea)
                    <form action="{{ route('elecciones.torres.create') }}" method="post">
                        @csrf
                        <input type="text" name="delegadosArray" value="{{json_encode($delegados)}}" hidden>
                        <div class="card-body p-0">
                            <table class="list-group">
                                <thead @if (count($torres) > 8) class="pe-3" @endif>
                                    <tr class="list-group-item list-group-item-action ">
                                        <th class="text-end fs-5">Torres :</th>
                                        <th class="fs-5 w-25 ">
                                            <input type="number" class="form-control " name="torres"
                                                value="{{ count($torres) }}" readonly>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody style="max-height: 60vh; overflow-y: auto;">
                                    @foreach ($torres as $torre)
                                        <tr class="list-group-item list-group-item-action">
                                            <td class="text-end fs-6">Delegados por 
                                                <span class="badge text-bg-primary fs-6 p-1 ">
                                                    {{ $torre['numeral1'] }}
                                                </span> :
                                            </td>
                                            <td class="w-25">
                                                <input type="number" class="form-control " min="0"
                                                    name="delegados[{{ $torre['numeral1']}}]"
                                                    wire:model='delegados.{{ $torre['numeral1'] }}.delegados' required>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="card-footer d-flex justify-content-between ">
                            <div class="form-group d-flex">
                                <input type="number" class="form-control w-20 me-2" name="delegadosAll"
                                    wire:model='delegadosAll' min="0">
                                <button type="button" class="btn btn-primary" wire:click='setDelegados'>
                                    Aplicar a Todos
                                </button>
                            </div>
                            <button type="submit" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#spinnerModal">
                                Guardar

                            </button>
                        </div>
                    </form>
                @else
                    <div class="card-body ">
                        <form id="asamblea-form" action="{{ route('elecciones.store') }}" method="POST">
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
                                <div class="form-group col-12">
                                    <label for="lugar">Direccion</label>
                                    <input type="text" class="form-control" placeholder="Direccion" id="lugar"
                                        name="lugar" required>
                                </div>
                                <div class="form-group col-6">
                                    <label for="fecha">Fecha</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                                </div>
                                <div class="form-group col-6">
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
                                <div class="form-group col-6 pt-4 justify-content-end d-flex">
                                    <button type="submit" class="btn btn-success " id="submit-button"
                                    data-bs-toggle="modal" data-bs-target="#spinnerModal">Importar
                                        archivos</button>
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
                                </script>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="card-title mb-0">Archivo de personas</h2>

                    <button type="button" class="btn btn-primary fs-4 py-1" wire:click='setInPredios'>
                        @if ($inPredios)
                            Personas
                        @else
                            Predios
                        @endif
                    </button>
                </div>
                <div class="card-body table-responsive px-0 table-fixed-header table-70">
                    @if ($inPredios)
                        <table class="table table-bordered table-striped   mb-3">
                            <thead>
                                <tr>
                                    <th class="fs-5">ID</th>
                                    <th class="fs-5">Descriptor </th>
                                    <th class="fs-5">Coef...</th>
                                    <th class="fs-5">Vota</th>
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
                        <table class="table table-bordered table-striped  ">
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
