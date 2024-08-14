<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>

        {!! file_get_contents(public_path('\assets\scss\custom.css')) !!} {!! file_get_contents(public_path('\assets\scss\_variables.scss')) !!} {!! file_get_contents(public_path('\assets\scss\docs.scss')) !!}

        @page {
            margin: 27mm 15mm 20mm;
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
            <p class="fs-6"><u>ANEXO 2 - {{strtoupper($anexos[1])}}</u></p>
        </div>
        <hr class="blue">
        <div class="mt-1">
            <table class="">
                <tr class="p-0">
                    <td class="p-0"><small> Unidad residencial: </small></td>
                    <td class="p-0 text-start"><small> {{ $client_name }} </small></td>
                    <td></td>
                    <td></td>
                    <td class="p-0"><small> Total de asistentes; </small></td>
                    <td class="p-0"><small> {{ $predios->count() }}</small></td>
                </tr>
                <tr class="p-0">

                </tr>
                <tr class="p-0">
                    <td class="p-0"><small>Asistentes contabilizados: </small></td>
                    <td class="p-0"><small>{{cache('asistentes_init')}}</small></td>
                     <td></td><td></td>
                    <td class="p-0"><small> Coeficiente de asistencia:  </small></td>
                    <td class="p-0"><small> {{cache('quorum_init')}}</small></td>
                </tr>
            </table>
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
    <div class="body" style="padding-top: 30mm ">


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
