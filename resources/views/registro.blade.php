@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/scss/registro.scss') }}">

    @if (session('showModal'))
        <script type="text/javascript">
            $(document).ready(function() {
                $('#myModal').modal('show');
            });
        </script>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dimissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dimissible fade" role="alert">
            <div class="row justify-content-between">
                <div class="col-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
                <div class="col-1 offset-md-5">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

            </div>

        </div>
    @endif

    <div class="row g-3">
        <div class="col-7">
            <div class="row mb-3">
                {{-- buscar --}}
                <div class="col-4 ">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 ">Asistente</h5>
                            <form class="me-0" action="{{ route('asistencia.limpiar') }}" method="get">
                                <button class="btn btn-danger">
                                    <i class='bi bi-trash-fill '></i>
                                </button>
                            </form>
                        </div>
                        <div class="card-body table-responsive pt-3">
                            <form class="" action="{{ route('asistencia.buscar') }}" method="GET">
                                <label for="cedula" class="form-label">Cédula</label>
                                <div class="mb-3 d-flex align-items-center ">

                                    <input class="me-2 form-control" type="text" class="form-control"
                                        onkeypress="return onlyNumbers(event)" maxlength="12" name="cedula"
                                        value="{{ isset($asistente) ? $asistente->id : '' }}" required>
                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                </div>

                            </form>
                            <div class="mb-3 ">
                                <input name="nombre" id="txtName" rows="1" cols="12" class="form-control"
                                    value="{{ isset($asistente) ? $asistente->nombre : '' }}" disabled></input>
                                <small class="">Nombre</small>
                            </div>
                            <div class="mb-3">
                                <input name="nombre" id="txtName" rows="1" cols="12" class="form-control"
                                    value="{{ isset($asistente) ? $asistente->apellido : '' }}" disabled></input>
                                <small class="">Apellido</small>
                            </div>
                        </div>

                    </div>

                    <!-- Mostrar el nombre de la persona encontrada aquí -->
                </div>

                {{-- poderdantes --}}
                <div class="col-8">
                    @if (session('errorPropietarios'))
                        <div class="alert alert-danger position-absolute alert-dismissible z-3 " role="alert">
                            {{ session('errorPropietarios') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card ">
                        <div class="card-header  d-flex align-items-center justify-content-between">
                            <h5 class="card-title me-5">Poderdantes</h5>


                            <form action="{{ route('asistencia.addPoderdante') }}" method="get">
                                <div class="d-flex align-items-baseline ">

                                    <input placeholder="cedula" type="text" name="cedulaPropietario" id="cc"
                                        class="form-control" placeholder="" aria-describedby="helpId" />
                                    <button type="submit" class="btn ms-1 btn-primary">
                                        <i class='bi bi-arrow-right-circle-fill '></i>
                                    </button>
                                </div>
                            </form>
                        </div>


                        <div class="card-body table-responsive table-fixed-header">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cedula</th>
                                        <th><a class="btn p-0" href="{{ route('asistencia.dropAllPoderdante') }}">
                                                <i class='bi bi-x-square-fill '></i></a></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($poderdantes)
                                        @foreach ($poderdantes as $p)
                                            <tr>
                                                <td>{{ $p->nombre }} {{ $p->apellido }} </td>
                                                <td>{{ $p->id }}</td>
                                                <td>
                                                    <form action="{{ route('asistencia.dropPoderdante') }}" method="get">
                                                        <input type="text" name="cedula" value="{{ $p->id }}"
                                                            hidden>
                                                        <button class="btn p-0" type="submit"><i
                                                                class='bi bi-x-square-fill'></i></button>

                                                </td>
                                                </form>
                                            </tr>
                                        @endforeach
                                    @endisset




                                </tbody>


                            </table>



                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-0">
                {{-- asignar --}}
                <div class="col-12 ">
                    <div class="card ">
                        @isset($asignacion)
                            <form id="formPredios" action="{{ route('asistencia.editAsignacion') }}" method="post">
                                <input type="text" name="asignacion_id" value="{{ $asignacion->id }}" hidden>
                            </form>
                        @else
                            <form id="formPredios" action="{{ route('asistencia.asignar') }}" method="post">

                                @csrf
                                <div class="card-header">
                                    <div class="row g-3">

                                        <input type="hidden" name="cc_asistente"
                                            value="{{ isset($asistente) ? $asistente->id : '' }}">
                                        <div class="mb-3 col-2 ">
                                            @isset($asignacion)
                                                <select name="control" id="id_control_selected" class="form-control  " required>
                                                    @foreach ($asistente->asignaciones as $a)
                                                        <option value="{{ $a->control_id }}" @selected($a->control_id == $asignacion->control_id)>
                                                            {{ $a->control_id }} </option>
                                                    @endforeach
                                                </select>
                                                <script>
                                                    $(document).ready(function() {
                                                        $('#id_control_selected').change(function() {
                                                            var selectedControlId = parseInt($(this).val());
                                                            window.location.href = 'changeAsignacion?control=' + selectedControlId;
                                                        })
                                                    })
                                                </script>
                                            @else
                                                <select name="control" id="id_control" class="form-control  " required>

                                                    @foreach ($controlIds as $control)
                                                        <option value="{{ $control }}" @selected($control == $controlTurn)>
                                                            {{ $control }} </option>
                                                    @endforeach
                                                </select>
                                            @endisset

                                        </div>
                                        <div class="col-2">
                                            <button type="submit" class="btn btn-primary" @disabled(isset($asistente) ? $prediosAvailable->isEmpty() : true)>
                                                @isset($asignacion)
                                                    Agregar
                                                @else
                                                    Asignar
                                                @endisset
                                            </button>
                                        </div>


                                        <div class="col-1">
                                            @isset($asignacion)
                                                <a href="{{ route('asistencia.dropAsignacion') }}" class="btn btn-primary">
                                                    <i class='bi bi-plus-circle-fill '></i></a>
                                            @endisset
                                        </div>

                                        <div class="col-2 offset-md-5 text-right">
                                            <input class="form-control" name="sum_coef" id="sumCoef" readonly></input>
                                            <small id="helpId" class="text-muted">Coeficiente total</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive table-fixed-header">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Cc Propietario</th>
                                                <th>Propietario</th>
                                                <th>Predio</th>
                                                <th>Coef.</th>
                                                <th>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="checkAll">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($prediosPersona)
                                                {{ $prediosAvailable = $prediosAvailable->concat($prediosPersona) }}
                                            @endisset



                                            @isset($prediosAvailable)
                                                @forelse ($prediosAvailable as $predio)
                                                    <tr>
                                                        <td>{{ $predio->persona->id }}</td>
                                                        <td>{{ $predio->persona->nombre }}
                                                            {{ $predio->persona->apellido }} </td>
                                                        <td>{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                            {{ $predio->descriptor2 }} {{ $predio->numeral2 }} </td>
                                                        <td>{{ $predio->coeficiente }}</td>
                                                        <td>
                                                            <input class="form-check-input checkbox-item" type="checkbox"
                                                                name="predios[]" data-coeficiente="{{ $predio->coeficiente }}"
                                                                value="{{ $predio->id }}" id="flexCheckDefault">
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5">No hay predios para asignar</td>
                                                    </tr>
                                                @endforelse
                                            @else
                                                <tr class="table-active">
                                                    <td colspan="5"></td>
                                                </tr>
                                            @endisset
                                            @isset($asignacion)
                                                @foreach ($asignacion->predios as $predio)
                                                    <tr class="table-active">
                                                        <td>{{ $predio->persona->id }}</td>
                                                        <td>{{ $predio->persona->nombre }}
                                                            {{ $predio->persona->apellido }}
                                                        </td>
                                                        <td>{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                                            {{ $predio->descriptor2 }} {{ $predio->numeral2 }} </td>
                                                        <td colspan="2">{{ $predio->coeficiente }}</td>

                                                    </tr>
                                                @endforeach
                                            @endisset
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        @endisset
                    </div>
                </div>
            </div>

        </div>
        <div class="col-5">
            <livewire:all-predios />
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">No esta registrado</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('asistencia.creaPersona') }}" method="post">
                    @csrf
                    <div class="modal-body">

                        <div class=" row g-3 d-flex">
                            <div class="col-2">
                                <select class="form-control" name="tipo_id">
                                    <option value="CC" selected>CC</option>
                                    <option value="CE">CE</option>
                                    <option value="NIT">NIT</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <input type="text" name="cedula" class="form-control"
                                    value="{{ session('cedula') }}" onkeypress="return onlyNumbers(event)"
                                    maxlength="12" required />
                                <small id="helpId" class="text-muted">Cedula</small>
                            </div>

                        </div>
                        <div class="mb-3">
                            <input type="text" name="nombre" id="" class="form-control"
                                onkeypress="return noNumbers(event)" value="{{ old('nombre') }}" required />
                            <small id="helpId" class="text-muted">Nombre</small>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="apellido" id="" class="form-control"
                                value="{{ old('apelldio') }}" placeholder="" />
                            <small id="helpId" class="text-muted">Apellido</small>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>


            </div>
        </div>
    </div>



    <script>
        function noNumbers(event) {
            var charCode = event.charCode;
            if (charCode >= 48 && charCode <= 57) {
                return false; // Bloquear el input si es un número
            }
            return true; // Permitir el input si no es un número
        }

        function onlyNumbers(event) {
            var charCode = event.charCode;
            if (charCode < 48 || charCode > 57) {
                return false; // Bloquear el input si no es un número
            }
            return true; // Permitir el input si es un número
        }
        $(document).ready(function() {

            $('#checkAll').prop('checked', true);
            $('.checkbox-item').prop('checked', true);
            sumarCoeficiente();



            $(document).on('click', '.remove-row-btn', function() {
                // Elimina la fila solo si hay más de una en el contenedor
                if ($('#row-container .row').length > 1) {
                    $(this).closest('.row').remove();
                } else {
                    alert('No se puede eliminar la última fila.');
                }
            });

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


            function sumarCoeficiente() {
                var coefActual = @json(isset($asignacion) ? $asignacion->sum_coef : 0);
                var sumaCoeficiente = 0;
                $('.checkbox-item').each(function() {
                    if ($(this).prop('checked')) {
                        sumaCoeficiente = sumaCoeficiente + $(this).data('coeficiente');
                    }
                });

                $('#sumCoef').val((sumaCoeficiente + parseFloat(coefActual)).toFixed(5));
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
@endsection
