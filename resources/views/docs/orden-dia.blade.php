<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        {!! file_get_contents(public_path('\assets\scss\_custom.css')) !!} {!! file_get_contents(public_path('\assets\scss\_variables.scss')) !!} {!! file_get_contents(public_path('\assets\scss\docs.scss')) !!} @page {
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

        ul {
            list-style-type: none;
            /* Elimina las viñetas */
            padding-left: 0;
            /* Elimina el espacio de sangría predeterminado */
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

    <div class="body">
        @if (cache('ordenDia', ''))
            <div class="page-break">
                <br><br>

                <h5 class="text-center title">
                    <h6>ANEXO {{ $index + 1 }} - {{ strtoupper($anexos[$index]) }}</h6>
                </h5>
                <br><br><br>
                <div class="anexos">
                    <ul class="">
                        @for ($i = 0; $i < count($ordenDia); $i++)
                            <li>{{ $i + 1 }}. {{ $ordenDia[$i] }} </li>
                        @endfor
                    </ul>
                </div>
            </div>
            <div class="">

                <h6 class="text-center"><u>ANEXO {{ $index + 2 }} - {{ strtoupper($anexos[$index + 1]) }}</u></h6>
                <br>
                <div class="anexos">
                    <ul>
                        @foreach ($questions as $q)
                            <li>Ítem {{ $q->id - 13 }} - {{ $q->title }} </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <div class="">

                <h6 class="text-center"><u>ANEXO {{ $index +1 }} - {{ strtoupper($anexos[$index ]) }}</u></h6>
                <br>
                <div class="anexos">
                    <ul>
                        @foreach ($questions as $q)
                            <li>Ítem {{ $q->id - 13 }} - {{ $q->title }} </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif




    </div>




</body>

</html>
