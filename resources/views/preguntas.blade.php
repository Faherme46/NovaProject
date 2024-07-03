<link rel="stylesheet" href="asstes/scss/preguntas.scss">

@extends('layout.app')


@section('content')
        <table>
            <colgroup span="2"></colgroup>
            <colgroup span="1"></colgroup>
            <tr>
              <th scope="colgroup">Personal</th>
              <th scope="colgroup">Profesional</th>
            </tr>
            <tr>
              <th scope="col">Nombre</th>
              <th scope="col">Edad</th>
              <th scope="col">Ciudad</th>
            </tr>
            <tr>
              <td>Ana</td>
              <td>28</td>
              <td>Madrid</td>
            </tr>
          </table>
@endsection
