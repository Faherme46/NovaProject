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
            Informe de Asistencia {{ $asamblea->name }}
        </div>

    </footer>

    <div class="body">

        @foreach ($questions as $key => $q)
            <div class="myh-100 ">
                <h4 class="w-100 bg-darkblue  text-center mb-3 py-2 text-light">
                    ÍTEM {{ $key+1}}
                </h4>
                <table class="w-100 mb-4 table">
                    <thead class="table-active">
                        <tr class="bg-darkblue  ">
                            <td colspan="4">
                                <h4 class="w-100 text-center py-1 mb-0 text-light" style="font-size: 0.75rem">
                                    {{ $q->title }}
                                </h4>
                            </td>
                        </tr>

                    </thead>
                    <tbody>
                        <tr class="bg-secondary-subtle">
                            <td class="text-center">
                                <b>Opción</b>
                            </td>
                            <td class="text-center">
                                <b>Votos</b>
                            </td>
                            <td class="text-center">
                                <b>Coeficiente</b>
                            </td>
                            <td class="text-center col-6">
                                <b>Descripción</b>
                            </td>
                        </tr>
                        @foreach ($options as $option)
                            @if ($q['option' . $option])
                                <tr>
                                    <td>{{ $option }}</td>
                                    <td class="text-center">{{ $q->resultNom['option' . $option] }}</td>
                                    <td class="text-center">{{ sprintf('%.4f',$q->resultCoef['option' . $option]) }}</td>
                                    <td>{{ $q['option' . $option] }}</td>
                                </tr>
                            @endif
                        @endforeach


                        <tr>
                            <td>
                                Abstención
                            </td>
                            <td class="text-center">{{ $q->resultNom->abstainted }}</td>
                            <td class="text-center">{{ sprintf('%.4f',$q->resultCoef->abstainted) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                Ausencia
                            </td>
                            <td class="text-center">{{ $q->resultNom->absent }}</td>
                            <td class="text-center">{{ sprintf('%.4f',$q->resultCoef->absent) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                @if ($q->type == 1)
                                    Presentes
                                @else
                                    Nulo
                                @endif
                            </td>
                            <td class="text-center">{{ $q->resultNom->nule }}</td>
                            <td class="text-center">{{ sprintf('%.4f',$q->resultCoef->nule) }}</td>
                            <td></td>
                        </tr>
                        <tr class="bg-secondary-subtle">
                            <td class="text-center" ><b>TOTAL</b></td>
                            <td class="text-center">{{ $q->resultNom->total}}</td>
                            <td class="text-center">{{ sprintf('%.4f', $q->resultCoef->total)}}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center mb-1">
                    @if ($q->coefGraph)
                        <img src="{{ asset('storage/images/results/' . $q->resultCoef->chartPath) }}"
                            class="w-100 h-auto" alt="No se encontro la imagen">
                    @else
                        <img src="{{ asset('storage/images/results/' . $q->resultNom->chartPath) }}"
                            class="w-100 h-auto" alt="No se encontro la imagen">
                    @endif
                </div>
                @if ($q->type != 1 && $q->type != 5 )
                    <h4 class="w-100 bg-darkblue text-light text-center mb-3 py-2">
                        {{ $q->resultTxt }}
                    </h4>
                @endif

            </div>
        @endforeach


    </div>




</body>

</html>
