

@extends('layout.app')

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    <a href="{{route('asistencia')}}">Registro</a><br>
    <a href="login">login</a> <br>
    <a href="crearUsuarios">crearusuarios</a> <br>
    <a href="preguntas">preguntas</a><br>
    <a href="resultados">resultados</a><br>
    <a href="votos">votos</a><br>
    <a href="admin/asambleas">Crear asamblea</a><br>
    <a href="asistenciaa">Asistencia</a><br>
@endsection
