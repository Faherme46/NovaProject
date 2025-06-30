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

        th {
            border: 1px wheat solid;
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
            <p class="fs-10"><u>ANEXO {{ $index + 1 }} - {{ strtoupper($anexos[$index]) }}</u><sup>1</sup></p>
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
                                Asistentes Contabilizados: <br>
                                Coeficiente de asistencia: <br>
                            </p>
                        </td>
                        <td>
                            <p class="p-0 ps-2 my-0 no-line-spacing text-left ">
                                {{ $asambleaR->folder }} <br>
                                {{ $asambleaR->fecha }} <br>
                                {{ count($predios) }}<br>
                                {{ cache('predios_init') }} <br>
                                {{ cache('quorum_init') }}
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
                tiene como base los datos suministrados por la Administración de {{ $asambleaR->folder }}.</small>
        </div>
        <div class="text-end txt-small">
            Informe de Asistencia {{ $asambleaR->name }}
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
                </tr>
                <tr>
                    <th class="p-0 pb-1 px-1"><small> Tipo</small></th>
                    <th class="p-0 pb-1 px-1"><small> # </small></th>
                    <th class="p-0">Propiedad </th>
                    <th class="p-0 pb-1">la Reunion</th>
                    <th class="pb-1 fs-10x">Llegada</th>
                </tr>
            </thead>
            <tbody class="fs-12px">
                @foreach ($predios as $predio)
                    <tr>
                        <td class="p-0 px-1 ">{{ $predio['numeral1'] }}</td>
                        <td class="p-0 px-1 ">{{ $predio['numeral2'] }}</td>
                        <td class="px-0">{{ $predio['coeficiente'] }}</td>
                        @if ($predio['control_id'] && $predio['quorum_start'])
                            @if (!$predio['apoderado'])
                                <td class="p-0">Propietario</td>
                                <td class="text-start ps-1">{{ $predio['personas'][0]['nombre'] }}</td>
                            @else
                                <td class="p-0">Apoderado</td>
                                <td class="text-start ps-1">{{ $predio['apoderado']['nombre'].' '. $predio['apoderado']['apellido']}}</td>
                            @endif
                            <td>
                                {{ $predio['h_entrega'] }}
                            </td>
                        @else
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
