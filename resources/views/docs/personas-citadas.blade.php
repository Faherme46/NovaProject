<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @page {
            margin: 27mm 15mm 20mm;
        }
        {!! file_get_contents(public_path('\assets\scss\custom.css')) !!} {!! file_get_contents(public_path('\assets\scss\_variables.scss')) !!} {!! file_get_contents(public_path('\assets\scss\docs.scss')) !!}
        .body{
            padding-top:20mm;
            background-color: red
        }
    </style>

    <title>Document</title>
</head>

<body>

    <header>
        <div class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" width="100mm" height="auto">
        </div>
        <div class="title">
            <p class="fs-6"><u>ANEXO 1 - LISTADO DE PERSONAS CITADAS (1)</u></p>
        </div>
        <hr class="blue">
        <div class="mt-0'">
            <small class="m-0 d-block ">Unidad residencial: {{ $client_name }} </small>
            <small class="m-0 d-block " style="margin-top:-2mm ">Cantidad de predios:
                <span class="border-1 border px-3 border-black">{{ $predios->count() }}
                </span></small>
        </div>
    </header>
    <footer>
        <div class="">
            <small class="txt-small">(1) La información reflejada en este Anexo,
                tiene como base los datos suministrados por la Administración de {{ $client_name }}.</small>
        </div>
        <div class="text-end txt-small">
            Informe de Asistencia Bosque del Hato (25feb24).xlsx

        </div>

    </footer>
    <div class="body" style="">


        <table class="table mt-3 table-bordered border-black-1 table-anexos">
            <thead class="bg-darkblue text-light text-center">
                <tr>
                    <td class="p-0" colspan="2">Predio</td>
                    <td class="p-0 pb-3" rowspan="2">Coeficiente</td>
                    <td class="p-0" colspan="2">Asistente</td>
                    <td class="p-0" colspan="2">Nombre</td>
                </tr>
                <tr>
                    <td class="p-0"><small> Torre</small></td>
                    <td class="p-0"><small> Apto</small></td>
                    <td class="p-0 txt-small"><small>Propietario</small></td>
                    <td class="p-0 txt-small" c><small>Apoderado</small></td>
                    <td class="p-0"><small> Propietario</small></td>
                    <td class="p-0"><small> Apoderado</small></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($predios as $predio)
                    <tr>
                        <td>{{ $predio->numeral1 }}</td>
                        <td>{{ $predio->numeral2 }}</td>
                        <td>{{ $predio->coeficiente }}</td>

                        @if ($predio->apoderado)
                            <td></td>
                            <td>X</td>
                        @else
                            <td>X</td>
                            <td></td>
                        @endif


                        <td class="text-start ps-1">
                            @foreach ($predio->personas as $persona)
                                {{ $persona->nombre }} {{ $persona->apellido }}
                            @endforeach
                        </td>
                        <td class="text-start ps-1 text-uppercase">
                            @if ($predio->apoderado)
                                {{ $predio->apoderado->nombre }} {{ $predio->apoderado->apellido }}
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>




</body>

</html>
