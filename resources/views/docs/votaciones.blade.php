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
        {!! file_get_contents(public_path('\assets\scss\custom.css')) !!} {!! file_get_contents(public_path('\assets\scss\_variables.scss')) !!} {!! file_get_contents(public_path('\assets\scss\docs.scss')) !!} @page {
            margin: 65mm 25mm 20mm;
        }

        header {
            position: fixed;
            top: -150;
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
            Informe de Asistencia Bosque del Hato (25feb24).xlsx
        </div>

    </footer>

    <div class="body">

        @foreach ($questions as $q)
            <div class="myh-100 ">
                <h4 class="w-100 bg-darkblue text-light text-center mb-3 py-2">
                    ÍTEM {{ $q->id - 13 }}
                </h4>
                <table class="w-100 mb-4">
                    <thead>
                        <tr class="bg-darkblue  ">
                            <td colspan="4">
                                <h4 class="w-100 text-center py-1     text-light">
                                    {{ $q->title }}
                                </h4>
                            </td>
                        </tr>
                        <tr class="table-active">

                            <th>
                                Opción
                            </th>
                            <th>
                                Votos
                            </th>
                            <th>
                                Coeficiente
                            </th>
                            <th>
                                Descripción
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($options as $option)
                            @if ($q['option' . $option])
                                <tr>
                                    <td>{{ $option }}</td>
                                    <td>{{ $q->resultNom['option' . $option] }}</td>
                                    <td>{{ $q->resultCoef['option' . $option] }}</td>
                                    <td>{{ $q['option' . $option] }}</td>
                                </tr>
                            @endif
                        @endforeach


                        <tr>
                            <td>
                                Abstención
                            </td>
                            <td>{{ $q->resultNom->abstainted }}</td>
                            <td>{{ $q->resultCoef->abstainted }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                Ausencia
                            </td>
                            <td>{{ $q->resultNom->absent }}</td>
                            <td>{{ $q->resultCoef->absent }}</td>
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
                            <td>{{ $q->resultNom->nule }}</td>
                            <td>{{ $q->resultCoef->nule }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center mb-1">
                    @if ($q->coefGraph)
                        <img src="{{ asset('storage/images/results/' . $q->resultCoef->chartPath) }}"
                            class="w-100 h-auto">
                    @else
                        <img src="{{ asset('storage/images/results/' . $q->resultNom->chartPath) }}"
                            class="w-100 h-auto">
                    @endif
                </div>
                @if ($q->type != 1)
                    <h4 class="w-100 bg-darkblue text-light text-center mb-3 py-2">
                        {{ $q->resultTxt }}
                    </h4>
                @endif

            </div>
        @endforeach


    </div>




</body>

</html>
