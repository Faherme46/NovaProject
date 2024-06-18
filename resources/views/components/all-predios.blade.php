
<div class="card">
    <div class="card-header">
        <h5 class="card-title"> Predios Disponibles</h5>
    </div>
    {{-- <div class="card-header">
        <table>
            <tr>
                <form action="" method="GET">
                    <td></td>
                    <td>
                        <input type="text" name="cc_propietario" class="form-control" placeholder="Propietario">
                    </td>
                    <td>
                        <input type="text" name="predio" class="form-control" placeholder="Predio">
                    </td>
                    <td>
                        <input type="text" name="coeficiente" class="form-control" placeholder="Coeficiente">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </td>
                </form>
            </tr>
        </table>
    </div> --}}
    <div class="card-body table-responsive table-fixed-header table-h100 ">

        <table class="table">

            <thead>
                <th>Añadir</th>
                <th>Propietario</th>
                <th>Predio</th>
                <th>Coef</th>
            </thead>
            <tbody>
                @foreach ($allPredios as $predio)
                    @if (!$predio->asignacion->isEmpty())
                        <tr class="table-active">
                            <td></td>
                            <td>{{ $predio->cc_propietario }}</td>
                            <td>{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                            </td>
                            <td>{{ $predio->coeficiente }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <form action="{{route('asistencia.addPredio')}}" method="get">
                                    <input type="text" name="predio_id" value="{{$predio->id}}" hidden>
                                    <button type="submit" class="btn"><i
                                            class='bx bx-plus-circle bx-b'></i></button>
                                </form>
                            </td>
                            <td>{{ $predio->cc_propietario }}</td>
                            <td>{{ $predio->descriptor1 }} {{ $predio->numeral1 }}
                                {{ $predio->descriptor2 }} {{ $predio->numeral2 }}
                            </td>
                            <td>{{ $predio->coeficiente }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
