<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @page {
            margin: 53mm 15mm 20mm;
        }

        header {
            position: fixed;
            top: -130;
            height: 20mm;
            display: flex;
            line-height: 35px;
            width: 100%;
        }

        .title {
            position: fixed;
            top: -110;
            right: 0;
            left: 0;
            text-align: center;
        }

        .body {

            height: 217mm;
        }

        td {

            white-space: nowrap; /* Evita que el contenido se divida en varias líneas */
            padding: 0; /* Opcional: Elimina el padding si no lo necesitas */
        }

        {!! file_get_contents(public_path('\assets\scss\blue.css')) !!} {!! file_get_contents(public_path('\assets\scss\_variables.scss')) !!} {!! file_get_contents(public_path('\assets\scss\docs.scss')) !!}
    </style>

    <title>Document</title>
</head>

<body>

    <header style="margin-bottom: 30mm">
        <div class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" width="100mm" height="auto">
        </div>
        <div class="title">
            <p class="fs-10"><u>ANEXO {{$index+1}} - {{ strtoupper($anexos[$index]) }}</u><sup>1</sup></p>
        </div>
        <hr class="blue">
        <div class="mt-1">
            <section class="fs-10 ">
                <table>
                    <tr>
                        <td>
                            <p class="p-0 my-0 no-line-spacing text-end ">
                                Unidad residencial: <br>
                                Fecha de asamblea: <br>
                                Cantidad total de predios: <br>
                                Predios que asistieron: <br>
                                Coeficiente de asistencia: <br>
                            </p>
                        </td>
                        <td>
                            <p class="p-0 ps-2 my-0 no-line-spacing text-left ">
                                {{ $asamblea->folder }} <br>
                                {{ $asamblea->fecha }} <br>
                                {{ $predios->count() }}<br>
                                {{ $prediosCount }} <span class="ms-5">(Asistió el
                                    {{ round(($prediosCount / $predios->count()) * 100, 3) }}% de los predios)</span><br>
                                {{ $quorum }}
                            </p>
                        </td>

                    </tr>
                </table>


            </section>
        </div>
    </header>
    <footer>
        <div class="">
            <small class="txt-small">(1) La información reflejada en este Anexo,
                tiene como base los datos suministrados por la Administración de {{ $asamblea->folder }}.</small>
        </div>
        <div class="text-end txt-small">
            Informe de Asistencia {{$asamblea->name}}
        </div>

    </footer>
    <div class="body ">
        <table class="table table-bordered border-black-1 table-anexos fs-10">
            <thead class="bg-darkblue text-light text-center">
                <tr>
                    <th class="p-0 pb-1" colspan="2">Predio</th>
                    <th class="p-0">Coeficiente </th>
                    <th class="p-0 pb-1">Asistente de</th>
                    <th class="p-0 pb-2" rowspan="2">Nombre</th>
                    <th class="pb-1 fs-10x">Hora</th>
                    <th class="pb-1 fs-10x">Hora</th>
                </tr>
                <tr>
                    <th class="p-0 pb-1 px-1"><small> Tipo</small></th>
                    <th class="p-0 pb-1 px-1"><small> # </small></th>
                    <th class="p-0">Propiedad </th>
                    <th class="p-0 pb-1">la Reunion</th>
                    <th class="pb-1 fs-10x">Llegada</th>
                    <th class="pb-1 fs-10x">Salida</th>
                </tr>
            </thead>
            <tbody class="fs-10px">
                @foreach ($predios as $predio)
                    <tr>
                        <td class="p-0">{{ $predio->numeral1 }}</td>
                        <td class="p-0">{{ $predio->numeral2 }}</td>
                        <td class="p-0">{{ $predio->coeficiente }}</td>
                        @if ($predio->control)
                            @if ($predio->personas->contains($predio->control->persona))
                                <td class="p-0">Propietario</td>
                            @else
                                <td class="p-0">Apoderado</td>
                            @endif
                            <td class="text-start ps-1">
                                {{ $predio->control->persona->nombre }} {{ $predio->control->persona->apellido }}
                            </td>
                            <td>
                                {{$predio->control->h_entrega}}
                            </td>
                            <td>{{$predio->control->h_recibe}}</td>
                        @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @endif



                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>

</html>
