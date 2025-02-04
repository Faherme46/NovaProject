
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        {!! file_get_contents(public_path('\assets\scss\blue.css')) !!}
        {!! file_get_contents(public_path('\assets\scss\_variables.scss')) !!}
        {!! file_get_contents(public_path('\assets\scss\docs.scss')) !!}
        @page {
            margin: 40mm 25mm 25mm;
        }
    </style>

    <title>Document</title>
</head>

<body>

    <div class="body">
        <div class="text-center">
            <br><br>
            <h3>ASAMBLEA DE COPROPIETARIOS</h3>
            <br><br><br><br><br><br><br><br>
            <h3>{{ strtoupper($asambleaR->folder) }}</h3>
            <br><br><br><br><br><br><br><br>
            <h3>{{ strtoupper('Informe de '.$asambleaR->referencia) }}</h3>
            <br><br><br><br><br><br>
            <br><br><br><br><br><br>
            <h3>{{ strtoupper($date) }}</h3>

        </div>
    </div>
</body>

</html>
