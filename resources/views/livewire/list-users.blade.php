
<div class="card">
    <div class="card-header">
        Usuarios
    </div>
    <div class="card-body table-responsive table-fixed-header table-h100 ">

        <table class="table">
            <thead>
                <th>Nombre</th>
                <th>Username</th>
                <th>Contraseña</th>
                <th>Rol</th>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{$user->name}} {{$user->lastName}}</td>
                        <td>{{$user->username}}</td>
                        <th>{{$user->passwordTxt}}</th>
                        <td>{{$user->getRoleNames()->first()}}</td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>

</div>
