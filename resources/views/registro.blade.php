@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/scss/registro.scss') }}">
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

        @if ($name_asamblea == '-')
            <div class="alert alert-danger">
                No hay una asamblea en sesion
            </div>
        @else
            <div class="row mb-5">
                <!-- Columna para el formulario de búsqueda -->
                <div class="col-md-3 ">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 ">Propietario</h5>
                            <form class="me-0" action="asistentes/limpiar" method="get">
                                <button class="btn btn-danger">
                                    <i class='bx bxs-trash bx-w'></i>
                                </button>
                            </form>
                        </div>
                        <div class="card-body">
                            <form class="" action="{{ route('asistentes.buscar') }}" method="GET">
                                <label for="cedula" class="form-label">Cédula</label>
                                <div class="mb-3 d-flex align-items-center ">
                                    @isset($persona)
                                        <input class="me-2 form-control" type="text" class="form-control" id="cedula"
                                        name="cedula" value="{{$persona->id}}"  readonly >
                                    @else
                                        <input class="me-2 form-control" type="text" class="form-control" id="cedula"
                                        name="cedula" required >
                                    @endisset

                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                </div>

                            </form>
                            <div class="mb-3 d-flex align-items-center ">
                                <label class="me-2" for="txtName">Nombre: </label>
                                <input name="nombre" id="txtName" rows="1" cols="12" class="form-control"
                                    value="{{ isset($persona) ? $persona->nombre : '' }}" disabled></input>
                            </div>
                            <div class="mb-3 d-flex align-items-center ">
                                <label class="me-2" for="txtLastname">Apellido: </label>
                                <input name="apellido" id="txtLastname" rows="1" cols="12" class="form-control"
                                    value="{{ isset($persona) ? $persona->apellido : '' }}" disabled></input>
                            </div>
                        </div>

                    </div>

                    <!-- Mostrar el nombre de la persona encontrada aquí -->
                </div>

                <!-- Columna para la tabla de predios -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Predios</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Predio</th>
                                        <th scope="col">Coef.</th>
                                        <th scope="col">
                                            @if ($selectedAll)
                                                <a href="asistentes/allPrediosUncheck">
                                                    <i class='bx bx-checkbox-checked bx-b'></i>
                                                </a>
                                            @else
                                                <a href="asistentes/allPrediosCheck">
                                                    <i class='bx bx-checkbox bx-b '></i>
                                                </a>
                                            @endif
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($persona)
                                        @forelse ($persona->predios as $predio)
                                            <tr>

                                                <td scope="row">{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                    {{ $predio->descriptor2 }} {{ $predio->numeral2 }} </td>
                                                <td>{{ $predio->coeficiente }}</td>
                                                <td>
                                                    <form id="formSelectPredio" action="asistentes/anadir" method="get">
                                                        <input name="predioId" value="{{ $predio->id }}" hidden>

                                                        @if ($predios->contains($predio))
                                                            <i class='bx bx-checkbox-checked bx-b' onclick="submitForm()"></i>
                                                        @else
                                                            <i class='bx bx-checkbox bx-b' onclick="submitForm()"></i>
                                                        @endif


                                                    </form>
                                                    <script>
                                                        function submitForm() {
                                                            document.getElementById('formSelectPredio').submit();
                                                        }
                                                    </script>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td>Sin predios</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="3"></td>
                                        </tr>
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Columna para la tabla de asignación de predios a control -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Asignación</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Descripción</th>
                                        <th>Coeficiente</th>
                                        <th scope="col">
                                            @if ($selectedAll)
                                                <a href="asistentes/allPrediosUncheck">
                                                    <i class='bx bx-checkbox-checked bx-b'></i>
                                                </a>
                                            @else
                                                <a href="asistentes/allPrediosCheck">
                                                    <i class='bx bx-checkbox bx-b'> </i>
                                                </a>
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($predios as $predio)
                                        <tr>
                                            <td scope="row">{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                {{ $predio->descriptor2 }} {{ $predio->numeral2 }} </td>
                                            <td>{{ $predio->coeficiente }}</td>
                                            <td>
                                                <form id="formSelectPredio" action="asistentes/anadir" method="get">
                                                    <input type="ext" name="id_predio" value="{{ $predio }}"
                                                        hidden>

                                                    @if ($predios->contains($predio))
                                                        <i class='bx bx-checkbox-checked bx-b' onclick="submitForm()"></i>
                                                    @else
                                                        <i class='bx bx-checkbox bx-b' onclick="submitForm()"></i>
                                                    @endif

                                                </form>
                                                <script>
                                                    function submitForm() {
                                                        document.getElementById('formSelectPredio').submit();
                                                    }
                                                </script>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"></td>
                                        </tr>
                                    @endforelse




                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <form class="row g-3" action="{{ route('asistentes.asignar') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cc_asistente"
                                    value="{{ isset($persona) ? $persona->id : '' }}">
                                <div id="selectedPredios"></div>
                                <div class="mb-3 col-md-4 ">
                                    <select name="control" id="id_control" class="form-control  " required>
                                        <option value="{{$controlTurn}}" selected>{{$controlTurn}}</option>
                                        @foreach($availableControls as $control)
                                            <option value="{{ $control }}">{{ $control }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Asignar</button>
                                </div>
                                <div class="col-md-4">
                                    <label for="sumCoef">Coeficiente</label>
                                    <input value="{{ $predios ? $predios->sum('coeficiente') : 0 }}" class="form-control"
                                        name="sum_coef" id="sumCoef" cols="4" rows="1" readonly></input>
                                </div>
                            </form>

                        </div>
                    </div>


                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Control</th>
                                <th>Nombre</th>
                                <th>Predios</th>
                                <th>Coeficiente</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($asignaciones as $asignacion)
                                <tr>
                                    <td>{{ $asignacion->id_control }}</td>
                                    <td>{{ $asignacion->persona->nombre }} {{ $asignacion->persona->apellido }}</td>
                                    <td>
                                        @foreach ($asignacion->predios as $predio)
                                            {{$predio->descriptor1}} {{$predio->numeral1}} {{$predio->descriptor2}} {{$predio->numeral2}}
                                        @endforeach
                                    </td>
                                    <td>{{ $asignacion->sum_coef }}</td>
                                    <td>{{$asignacion->estado}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Sin registros</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
    <script src="{{ asset('assets/js/registro.js') }}"></script>
@endsection
