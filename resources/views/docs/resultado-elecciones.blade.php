@php
    $options = ['A', 'B', 'C', 'D', 'E', 'F'];
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        {!! file_get_contents(public_path('\assets\scss\blue.css')) !!} {!! file_get_contents(public_path('\assets\scss\_variables.scss')) !!} {!! file_get_contents(public_path('\assets\scss\docs.scss')) !!} @page {
            margin: 65mm 25mm 20mm;
        }

        header {
            position: fixed;
            top: -100;
            height: 20mm;
            display: flex;
            line-height: 35px;
            width: 100%;
        }

        .body {
            height: 212mm;
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
        }

        .myh-100 {
            height: 200mm;
        }

        th {
            background-color: var(--secondary-color);
            text-align: center;
        }

        td,
        th {
            border: solid 0.5px black;
        }
    </style>


    <title>Document</title>
</head>

<body>

    <header>
        <div class="logo justify-content-center text-center">
            <img src="{{ asset('assets/img/logo.png') }}" width="100mm" height="auto">
        </div>
    </header>
    <footer>

        <div class="text-end txt-small">
            Informe de Asistencia {{ $asambleaR->name }}
        </div>

    </footer>

    <div class="body">
        @php
            $i = 0;
        @endphp
        @foreach ($torres as $torre)
            @php
                $coeficienteTotal = 0;
                $votosTotal = 0;
            @endphp
            <div class="myh-100 ">
                <h4 class="w-100 bg-darkblue  text-center mb-3 py-2 text-light">
                    {{ $torre['name'] }}
                </h4>
                <table class="w-100 mb-4 table">
                    <thead class="table-active">
                        <tr class="bg-darkblue  ">
                            <td colspan="3">
                                <h4 class="w-100 text-center py-1 mb-0 text-light" style="font-size: 0.75rem">
                                    RESULTADOS DE ELECCIONES
                                </h4>
                            </td>
                        </tr>

                    </thead>
                    <tbody>
                        <tr class="bg-secondary-subtle">
                            <td class="text-center">
                                <b>Documento</b>
                            </td>
                            <td class="text-center">
                                <b>Nombre</b>
                            </td>
                            <td class="text-center">
                                <b>Coeficiente</b>
                            </td>
                        </tr>
                        @forelse ($torre['candidatos'] as $candidato)
                            @php
                                $votosTotal += $candidato['pivot']['votos'];
                                $coeficienteTotal += $candidato['pivot']['coeficiente'];
                            @endphp
                            <tr>
                                <td>{{ $candidato['tipo_id'] . ' ' . $candidato['id'] }}</td>
                                <td class="text-center">{{ $candidato['nombre'] . ' ' . $candidato['apellido'] }}</td>
                                
                                <td class="text-center">
                                    {{ sprintf('%.5f', $candidato['pivot']['coeficiente']) }}
                                </td>

                            </tr>

                        @empty
                            <tr class="text-center ">
                                <td colspan="3"><b>NO HUBO CANDIDATOS</b></td>
                            </tr>
                        @endforelse
                        <tr>
                            <td></td>
                            <td class="text-center">        
                                VOTO EN BLANCO
                            </td>
                            
                            <td class="text-center">{{ sprintf('%.5f', $torre['coeficienteBlanco']) }}</td>
                            
                        </tr>
                        <tr class="bg-secondary-subtle">
                            <td></td>
                            <td class="text-center"><b>TOTAL</b></td>
                            
                            <td class="text-center">{{ sprintf('%.5f', $coeficienteTotal + $torre['coeficienteBlanco']) }}
                            </td>

                            
                        </tr>
                    </tbody>
                </table>
                <div class="text-center mb-1">
                    
                    <img src="{{ asset('storage/images/results/'.$asambleaR->name.'/'. $torre['id'].'/coefChart.png') }}" class="w-100 h-auto"
                        alt="No se encontro la imagen">
                </div>

            </div>
        @endforeach


    </div>




</body>

</html>
