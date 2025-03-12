<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        {!! file_get_contents(public_path('\assets\scss\blue.css')) !!} {!! file_get_contents(public_path('\assets\scss\_variables.scss')) !!} {!! file_get_contents(public_path('\assets\scss\docs.scss')) !!} @page {
            margin: 30mm 25mm 25mm;
        }

        header {
            position: fixed;
            top: -65;
            height: 20mm;
            display: flex;
            line-height: 35px;
            width: 100%;
        }

        .body {

            height: 217mm;
        }

        .table-general {
            border: solid 0.5px var(--black-color);
        }

        .bb-white {
            border-bottom: 0.5px solid whitesmoke;
        }

        td,
        th,
        tr {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
    </style>


    <title>Document</title>
</head>

<body>

    <header>
        <div class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" width="100mm" height="auto">
        </div>

        <hr class="blue">
    </header>
    <footer class="bg-pr">
        <hr class="w-25 mb-1">
        <span style="font-size: 8pt;">
            Los datos utilizados por TECNOVIS para la elaboración de los anexos relacionados en este informe (incluye
            los cálculos para las votaciones), y que comprende la lista de delegados, tiene como base la información
            suministrada por la Administración de {{ $asambleaR->folder }} a TECNOVIS, para el desarrollo de esta
            Asamblea.
            <hr>
        </span>
    </footer>
    <div class="body">
        <br><br>
        <h4 class="text-center">Contenido</h4>
        <br><br>
        <div class="anexos">
            <p>Resultados de las votaciones</p>

            <ul>
                @for ($i = 0; $i < count($anexos); $i++)
                    <li>Anexo {{ $i + 1 }} - {{ $anexos[$i] }} </li>
                @endfor
            </ul>
        </div>
        <hr>
        <br>
        <h5>Información General</h5>
        <br>
        <table class="table table-general">
            <tr>
                <th class="bb-white text-light bold bg-darkblue text-end" colspan="2">Cliente:</th>
                <td class="ps-2" colspan="5">{{ $asambleaR->folder }}</td>
            </tr>
            <tr>
                <th class="bb-white text-light bold bg-darkblue text-end" colspan="2">Referencia:</th>
                <td class="ps-2" >{{ $asambleaR->referencia }}</td>
                <th class="bb-white text-light bold bg-darkblue">Direccion:</th>
                <td class="ps-2" colspan="3">{{ $asambleaR->lugar }}</td>
            </tr>
            <tr>
                <th class=" text-light bold bg-darkblue text-end">Fecha:</th>
                <td class="ps-2" colspan="2">{{ $dateString }}</td>
                <th class=" text-light bold bg-darkblue">Hora Inicio:</th>
                <td class="ps-2">{{ $asambleaR->h_inicio }}</td>
                <th class=" text-light bold bg-darkblue">Hora Fin:</th>
                <td class="ps-2">{{ $asambleaR->h_cierre }}</td>
            </tr>
        </table>
        <br>
        <h5>Resumen de Participacion</h5>
        <br>
        <table class="table table-general">
            <thead>
                <tr>
                    <th class="" rowspan="2"></th>
                    <th rowspan='2' class="bb-white text-light bold bg-darkblue text-center align-middle" >Predios</th>
                    <th class="py-0 text-light bold bg-darkblue text-center" >Coeficiente</th>
                    <th class="py-0 text-light bold bg-darkblue text-center" >Coeficiente</th>
                    <th class="py-0 text-light bold bg-darkblue text-center" >Coeficiente</th>
                </tr>
                <tr>
                    
                    
                    <th class="py-0 text-light bold bg-darkblue text-center">de Participacion</th>
                    <th class="py-0 text-light bold bg-darkblue text-center">Habilitado</th>
                    <th class="py-0 text-light bold bg-darkblue text-center">no Habilitado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($torres as $torre)
                    <tr>
                        <th class="bb-white text-light bold bg-darkblue text-end" >{{ $torre['name'] }}
                        </th>
                        <td class="ps-2 text-center" >{{ $torre['votos'] }}</td>
                        <td class="ps-2 text-center"> {{ $torre['coefTotal'] }}</td>
                        <td class="ps-2 text-center">{{ $torre['coeficiente'] }}</td>
                        <td class="ps-2 text-center" >
                            {{ sprintf('%.5f', $torre['coefTotal'] - $torre['coeficiente']) }}
                        </td>
                    </tr>
                @endforeach
                <tr class="table-active">
                    <th class="bb-white text-light bold bg-darkblue text-end" >TOTAL</th>
                    <td class="bold bg-secondary-subtle ps-2 text-center" >{{ $general['prediosRegistrados'] }}</td>
                    <td class="bold bg-secondary-subtle ps-2 text-center" >{{ $general['coeficienteRegistrado'] }}</td>
                    <td class="bold bg-secondary-subtle ps-2 text-center" >{{ $general['coeficienteHabilitado'] }}</td>
                    <td class="bold bg-secondary-subtle ps-2 text-center" >{{ $general['coeficienteAbstencion'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
