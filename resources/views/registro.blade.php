@extends('layout.app')

@section('content')

    <script>
        // Verificar si jQuery está definido
        if (typeof jQuery != 'undefined') {
            // jQuery está cargado
            console.log('jQuery está instalado y disponible.');
        } else {
            // jQuery no está cargado
            console.log('jQuery no está instalado o no está disponible.');
        }
    </script>
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

                                        <input class="me-2 form-control" type="text" class="form-control" id="cedula"
                                            name="cedula" value="{{isset($persona)?$persona->id:''}}" required>


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

                <!-- Columna para la tabla de asignación de predios a control -->
                <div class="col-md-4">
                    <div class="card">
                        <form  id="formPredios" action="asistentes/asignar" method="post">
                            @csrf
                            <div class="card-header">
                                <h5 class="card-title mb-0">Asignación</h5>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Predios</th>
                                            <th>Coeficiente</th>
                                            <th>
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="checkAll">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($prediosAvailable)
                                            @forelse ($prediosAvailable as $predio)
                                                <tr>
                                                    <td >{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                        {{ $predio->descriptor2 }} {{ $predio->numeral2 }} </td>
                                                    <td>{{ $predio->coeficiente }}</td>
                                                    <td>
                                                        <input class="form-check-input checkbox-item" type="checkbox" name="predios[]" data-coeficiente="{{ $predio->coeficiente }}" value="{{ $predio->id }}" id="flexCheckDefault">
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3"></td>
                                                </tr>
                                            @endforelse
                                        @endisset

                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3">
                                    <input type="hidden" name="cc_asistente"
                                        value="{{ isset($persona) ? $persona->id : '' }}">
                                    <div class="mb-3 col-md-4 ">
                                        <select name="control" id="id_control" class="form-control  " required>
                                            @foreach ($controlIds as $control)
                                                <option value="{{ $control }}"@if($control==$controlTurn)@selected(true) @endif>{{ $control }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary" @isset($prediosAvailable) {{$prediosAvailable->isEmpty()?'disabled':''}} @endisset> Asignar</button>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="sumCoef">Coeficiente</label>
                                        <input class="form-control" name="sum_coef" id="sumCoef" readonly></input>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>


                </div>

                <!-- Columna para las Asignaiciones existentes -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Asignaciones</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Predio</th>
                                        <th>Coef.</th>
                                        <th>Control </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($persona)
                                        @forelse ($persona->asignaciones as $asignacion)
                                            <tr onclick="">
                                                <td>
                                                    @foreach ($asignacion->predios as $predio)
                                                        {{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                        {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td>{{ $asignacion->sum_coef }}</td>
                                                <td>{{ $asignacion->control_id }} </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">-</td>
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

                            @forelse ($asignacionesAll as $asignacion)
                                <tr>
                                    <td>{{ $asignacion->control_id }}</td>
                                    <td>{{ $asignacion->persona->nombre }} {{ $asignacion->persona->apellido }}</td>
                                    <td>
                                        @foreach ($asignacion->predios as $predio)
                                            {{ $predio->descriptor1 }} {{ $predio->numeral1 }} {{ $predio->descriptor2 }}
                                            {{ $predio->numeral2 }} <br>
                                        @endforeach
                                    </td>
                                    <td>{{ $asignacion->sum_coef }}</td>
                                    <td>{{ $asignacion->estado }}</td>
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
    <script>
        $(document).ready(function() {
            $('#checkAll').prop('checked',true);
            $('.checkbox-item').prop('checked',true);
            sumarCoeficiente();

            // Manejar el cambio del checkbox global
            $('#checkAll').change(function() {
                $('.checkbox-item').prop('checked', $(this).prop('checked'));
                sumarCoeficiente();
            });


            // Manejar el cambio de los checkboxes individuales
            $('.checkbox-item').change(function() {
                var allChecked = true;
                var allUnchecked = true;

                // Verificar el estado de todos los checkboxes individuales
                $('.checkbox-item').each(function() {
                    if ($(this).prop('checked')) {
                        allUnchecked = false;
                    } else {
                        allChecked = false;

                    }
                });

                // Actualizar el estado del checkbox global
                $('#checkAll').prop('checked', allChecked);

                // Establecer el estado indeterminado si no todos están seleccionados o deseleccionados
                if (!allChecked && !allUnchecked) {
                    $('#checkAll').prop('indeterminate', true);
                } else {
                    $('#checkAll').prop('indeterminate', false);
                }
                sumarCoeficiente();
            });
            function sumarCoeficiente(){
                var sumaCoeficiente=0;
                $('.checkbox-item').each(function() {
                    if ($(this).prop('checked')) {
                        sumaCoeficiente=sumaCoeficiente+$(this).data('coeficiente');
                    }
                });

                $('#sumCoef').val(sumaCoeficiente);
            }
            // Manejar el envío del formulario
            $('#formPredios').submit(function(event) {
                event.preventDefault(); // Evitar el envío por defecto del formulario

                // Obtener los IDs de los checkboxes marcados
                var prediosSeleccionados = $('.checkbox-item:checked').map(function() {
                    return $(this).val();
                }).get();

                // Crear un campo oculto con los IDs seleccionados
                var inputPredios = $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'prediosSelect')
                    .val(prediosSeleccionados); // Convertir array a cadena de texto separada por comas

                // Adjuntar el campo oculto al formulario
                $(this).append(inputPredios);

                // Enviar el formulario
                this.submit();
            });
        });
    </script>
    <script src="{{ asset('assets/js/registro.js') }}"></script>
@endsection
