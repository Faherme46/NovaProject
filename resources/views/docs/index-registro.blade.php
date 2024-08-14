
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
    <footer>
        <hr class="w-25">
        <small class="txt-small">{{$firstFooter}}</small>
    </footer>
    <div class="body">
            <br><br>
            <h4 class="text-center">Contenido</h4>
            <br><br><br>
            <div class="anexos">
                <p>Resultados de las votaciones</p>

                <ul>
                    @for ($i = 0; $i < count($anexos); $i++)
                        <li>Anexo {{$i+1}} - {{$anexos[$i]}} </li>
                    @endfor
                </ul>
            </div>
            <hr >
            <br>
            <h5>Información General</h5>
            <br>
            <table class="table table-general">
                <tr>
                    <th class=" text-light bold bg-darkblue text-end" >Cliente:</th>
                    <td colspan="5">{{$client_name}}</td>
                </tr>
                <tr>
                    <th class=" text-light bold bg-darkblue text-end" >Referencia:</th>
                    <td colspan="5">{{$reference}}</td>
                </tr>
                <tr>
                    <th class=" text-light bold bg-darkblue text-end" >Tipo de Asamblea:</th>
                    <td colspan="5">{{$type}}</td>
                </tr>
                <tr>
                    <th class=" text-light bold bg-darkblue text-end">Fecha</th>
                    <td>{{$dateAsamblea}}</td>
                    <th class=" text-light bold bg-darkblue">Hora Inicio:</th>
                    <td>{{$h_start}}</td>
                    <th class=" text-light bold bg-darkblue">Hora Fin:</th>
                    <td>{{$h_end}}</td>
                </tr>
            </table>

    </div>




</body>

</html>
