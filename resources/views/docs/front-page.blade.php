
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

    <div class="body">
        <div class="text-center">
            <br>
            <h3>{{ strtoupper($title) }}</h3>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <h3>{{ strtoupper($client_name) }}</h3>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br>
            <h3>{{ strtoupper($date) }}</h3>


    </div>
</body>

</html>
