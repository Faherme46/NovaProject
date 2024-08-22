
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        {!! file_get_contents(public_path('\assets\scss\custom.css')) !!}
        {!! file_get_contents(public_path('\assets\scss\_variables.scss')) !!}
        {!! file_get_contents(public_path('\assets\scss\docs.scss')) !!}

        @page {
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
        .bb-white{
            border-bottom: 0.5px solid whitesmoke;
        }

        td,th,tr{
    padding-top:0.5rem;
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
    <div class="body">
            <br><br>
            <h4 class="text-center">Contenido</h4>
            <br><br><br>
            <div class="anexos">
                <p>Resultados de las votaciones (Consultas)</p>

                <ul>
                    @for ($i = 0; $i < count($anexos); $i++)
                        <li>Anexo {{$i+1}} - {{$anexos[$i]}} </li>
                    @endfor
                </ul>
            </div>
            <hr>
            <br>
            <br>
            <br>
            <h5>Información General</h5>
            <br>
            <table class="table table-general">
                <tr>
                    <th class="bb-white text-light bold bg-darkblue text-end" >Cliente:</th>
                    <td class="ps-2" colspan="3">{{$asamblea->folder}}</td>
                </tr>
                <tr>
                    <th class="bb-white text-light bold bg-darkblue text-end" >Referencia:</th>
                    <td class="ps-2" colspan="3">{{$asamblea->referencia}}</td>
                </tr>
                <tr>
                    <th class="bb-white text-light bold bg-darkblue text-end" >Tipo de Asamblea:</th>
                    <td class="ps-2" >{{$asamblea->tipo}}</td>
                    <th class=" text-light bold bg-darkblue text-end">Fecha</th>
                    <td class="ps-2">{{$asamblea->fecha}}</td>
                </tr>
            </table>

    </div>




</body>

</html>
