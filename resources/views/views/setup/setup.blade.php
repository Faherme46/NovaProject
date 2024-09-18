<div>


    <style>
        .radioNaranja {
            border-color: #fd7e14;
            color: #fd7e14;
        }

        .radioNaranja:checked {
            background-color: #fd7e14;
            color: #ffffff;
        }
    </style>
    <x-alerts />
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    Tema de color
                </h4>
            </div>

            <div class="card-body d-flex justify-content-between">
                @foreach ($themes as $t)
                    <input type="radio" class="btn-check  " name="{{ $t['color'] }}" >
                    <label class="btn btn-outline d-flex w-15" for="radio{{ $t['color'] }}" style="" >
                        <span class="btn " style="  width: 30px; height: 30px; background-color:{{ $t['rgb'] }}">
                        </span>
                        <h4 class="mb-0 ms-2">{{ $t['color'] }}
                        </h4>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            Tamaño de Fuente
                        </h4>
                    </div>
                    <div class="card-body">

                        <input type="range" class="form-range" min="0" max="22" id="customRange2">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
