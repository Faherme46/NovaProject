<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>

        @page {
            margin: 35mm 15mm 20mm;
        }

        header {
            position: fixed;
            top: -80;
            height: 20mm;
            display: flex;
            line-height: 35px;
            width: 100%;
        }

        .title {
            position: fixed;
            top: -60;
            right: 0;
            left: 0;
            text-align: center;
        }

        .body {
            height: 235mm;
            padding-top: 2mm;
        }
        {!! file_get_contents(public_path('\assets\scss\_custom.css')) !!} {!! file_get_contents(public_path('\assets\scss\_variables.scss')) !!} {!! file_get_contents(public_path('\assets\scss\docs.scss')) !!}

    </style>

    <title>Document</title>
</head>

<body>

    <header>
        <div class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" width="100mm" height="auto">
        </div>
        <div class="title">
            <p class="fs-10"><u>ANEXO 1 - {{ strtoupper($anexos[0]) }}</u><sup>1</sup></p>
        </div>
        <hr class="blue">
        <div class="mt-1'">
            <section class="fs-10 ">
                <table>
                    <tr>
                        <td>
                            <p class="p-0 my-0 no-line-spacing text-end ">
                                Unidad Residencial <br>
                                Cantidad de predios: <br>
                            </p>
                        </td>
                        <td>
                            <p class="p-0 ps-2 my-0 no-line-spacing text-left ">
                                {{ $asamblea->folder }} <br>
                                <span class="border-1 border px-3 border-black">
                                    {{ $predios->count() }}
                                </span>
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
    <div class="body" style="">


        <table class="table mt-3 table-bordered border-black-1 table-anexos fs-10px">
            <thead class="bg-darkblue text-light text-center fs-10">
                <tr>
                    <td class="p-0" colspan="2">Predio</td>
                    <td class="p-0 pb-3" rowspan="2">Coeficiente</td>
                    <td class="p-0" colspan="2">Asistente</td>
                    <td class="p-0" colspan="2">Nombre</td>
                </tr>
                <tr>
                    <td class="p-0"><small> Torre</small></td>
                    <td class="p-0"><small> Apto</small></td>
                    <td class="p-0"><small>P</small></td>
                    <td class="p-0" ><small>A</small></td>
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
