<div>
    <div class="row mt-5 g-3 justify-content-center">
        <div class="col-auto align-items-center ">
            <div class="card ">
                <div class="card-header d-flex align-items-center py-3">
                    <div class="col-2">
                        <div class="dropdown">
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Preguntas
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item d-flex align-items-center active" href="#">Action</a>
                                </li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-8 d-flex align-items-center ms-2">

                        <input type="text" wire:model='questionTag' class="form-control">
                    </div>
                    <div class="col-1 mx-2">
                        <button class="btn btn-primary" wire:click=''>Presentar</button>
                    </div>
                </div>
                <div class="card-header d-flex align-items-center py-3  ">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="radioType" id="radioType1" autocomplete="off" checked>
                        <label class="btn btn-outline-primary" for="radioType1" >Quorum</label>

                        <input type="radio" class="btn-check" name="radioType" id="radioType2" autocomplete="off">
                        <label class="btn btn-outline-primary" for="radioType2">Aprobacion</label>

                        <input type="radio" class="btn-check" name="radioType" id="radioType3" autocomplete="off">
                        <label class="btn btn-outline-primary" for="radioType3">SI/NO</label>

                        <input type="radio" class="btn-check" name="radioType" id="radioType4" autocomplete="off">
                        <label class="btn btn-outline-primary" for="radioType4">Seleccion</label>

                        <input type="radio" class="btn-check" name="radioType" id="radioType5" autocomplete="off">
                        <label class="btn btn-outline-primary" for="radioType5">TD</label>
                    </div>
                    <div class="mx-2 vr"></div>
                    <div class="btn-group" role="group" >
                        <input type="radio" class="btn-check" name="radioCoef" id="radioCoef1" autocomplete="off" checked>
                        <label class="btn btn-outline-primary " for="radioCoef1">Nominal</label>

                        <input type="radio" class="btn-check" name="radioCoef" id="radioCoef2" autocomplete="off">
                        <label class="btn btn-outline-primary " for="radioCoef2">Coeficiente</label>
                    </div>
                    <div class="mx-2 vr"></div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input " type="checkbox" role="switch" id="switchBlanco">
                        <label class="form-check-label" for="switchBlanco">Blanco</label>
                    </div>
                </div>
                <div class="card-body d-flex    ">

                    <ul class="list-group">
                        <li class="list-group-item"><h2 class='mb-0'>A</h2></li>
                        <li class="list-group-item"><h2 class='mb-0'>B</h2></li>
                        <li class="list-group-item"><h2 class='mb-0'>C</h2></li>
                        <li class="list-group-item"><h2 class='mb-0'>D</h2></li>
                        <li class="list-group-item"><h2 class='mb-0'>E</h2></li>
                        <li class="list-group-item"><h2 class='mb-0'>F</h2></li>
                    </ul>
                    <ul class="list-group w-100 ">
                        <li class="list-group-item"><h2 class='mb-0'>An item </h2></li>
                        <li class="list-group-item"><h2 class='mb-0'>A second item </h2></li>
                        <li class="list-group-item"><h2 class='mb-0'>A third item </h2></li>
                        <li class="list-group-item"><h2 class='mb-0'>A fourth item </h2></li>
                        <li class="list-group-item"><h2 class='mb-0'>A fifth item </h2></li>
                        <li class="list-group-item"><h2 class='mb-0'>And a six one </h2></li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="col-3 ms-2">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0 text-center">Presentes </h3>
                </div>
                <div class="card-body ">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Quorum: </h5>
                            <input type="text" class="form-control px-1 ms-2 w-50" wire:model='quorumRegistered' >
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Predios: </h5>
                            <input type="text" class="form-control px-1 ms-2 w-50" wire:model='prediosRegistered' >
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Controles: </h5>
                            <input type="text" class="form-control px-1 ms-2 w-50" wire:model='controlsRegistered' >
                        </li>
                      </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0 text-center">Votantes </h3>
                </div>
                <div class="card-body ">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Quorum: </h5>
                            <input type="number " class="form-control px-1 ms-2 w-50" wire:model='quorumVote' >
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Predios: </h5>
                            <input type="text" class="form-control px-1 ms-2 w-50" wire:model='prediosVote' >
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Controles: </h5>
                            <input type="text" class="form-control px-1 ms-2 w-50" wire:model='controlsVote' >
                        </li>
                      </ul>
                </div>
            </div>
        </div>
    </div>
</div>
