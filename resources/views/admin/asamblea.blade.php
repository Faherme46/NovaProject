<link rel="stylesheet" href="{{ asset('assets/scss/creaasamblea.scss') }}">

@extends('layout.app')




@section('content')

    <div class="container">
        

        <div class=" d-flex">
            <div class="card col-md-4">
                <div class="card-header">
                    <h3 id="form-title">Crear Nueva asamblea</h3>
                </div>
                <div class="card-body">
                    <form class="row g-3" id="asamblea-form" action="{{ route('asambleas.store') }}" method="POST">
                        @csrf
                        <div class="form-group col-12">
                            <label for="folder">Cliente</label>
                            <select id="folder" class="form-select" aria-label="Default select example" name="folder"
                                required>
                                <option value="">Seleccionar un cliente</option>
                                @foreach ($folders as $folder)
                                    <option value="{{ $folder }}">{{ $folder }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" name="name" hidden>
                        <div class="form-group col-12">
                            <label for="lugar">Direccion</label>
                            <input type="text" class="form-control" placeholder="Direccion" id="lugar" name="lugar"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ciudad">Ciudad</label>
                            <select id="ciudad" class="form-select" name="ciudad">
                                <option value='Bucaramanga' selected>Bucaramanga</option>
                                <option value='Floridablanca'>Floridablanca</option>
                                <option value='Giron'>Giron</option>
                                <option value='Piedecuesta'>Piedecuesta</option>
                                <option value='Lebrija'>Lebrija</option>
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







        </div>



    </div>










@endsection
