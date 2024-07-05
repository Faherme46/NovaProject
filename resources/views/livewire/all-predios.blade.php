
@if (session('error'))
    <div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
<div class="card">
    <div class="card-header">
        <h5 class="card-title"> Predios Disponibles</h5>
    </div>
    <div class="card-header row g-1">
        <div class="col-3">

            <input wire:model.live='searchId' type="text" id="searchId" name="cc_propietario" class="form-control" placeholder="Propietario">

        </div>
        <div class="col-2">
            <select wire:model.live='decriptor1' class="form-control" name="descriptor1" id="">
                @foreach ($distincts['descriptor1'] as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-1">
            <select wire:model.live='numeral1' class="form-control" name="numeral1" id="">
                <option value="">#</option>
                @foreach ($distincts['numeral1'] as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <select class="form-control" name="descriptor2" id="">

                @foreach ($distincts['descriptor2'] as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <select wire:model.live='numeral2' class="form-control" name="numeral2" id="">
                <option value="">#</option>
                @foreach ($distincts['numeral2'] as $item)

                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-1 fpr">
            <button wire:click='clean' class=" btn btn-danger"><i class='bi bi-x-circle-fill ' ></i></button>
        </div>


    </div>
    <div class="card-body table-responsive table-fixed-header table-h100 ">

        <table class="table">

            <thead>
                <th>Añadir</th>
                <th>Propietario</th>
                <th>Predio</th>
                <th>Coef</th>
            </thead>
            <tbody>
                @foreach ($prediosAll as $predio)
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
                                <form action="{{ route('asistencia.addPredio') }}" method="get">
                                    <input type="text" name="predio_id" value="{{ $predio->id }}" hidden>
                                    <button type="submit" class="btn"><i
                                            class='bi bi-plus-circle-fill '></i></button>
                                </form>
                            </td>
                            <td><a class="nav-link"
                                    href="{{ route('asistencia.addPoderdanteId', ['id' => $predio->cc_propietario]) }}">{{ $predio->cc_propietario }}
                                </a></td>
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

