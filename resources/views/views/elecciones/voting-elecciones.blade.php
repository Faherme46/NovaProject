<div class="vh-100 align-content-center bg-primary   bg-opacity-25 ">
    <div class="z-3 position-fixed top-50 start-50 " style="max-width: 95%">

        @session('success')
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession

        @session('error')
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession



        @session('warning')
            <div class="alert alert-warning alert-dismissible" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession

        @session('info')
            <div class="alert alert-info alert-dismissible" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession


        @if ($errors->any())
            <div class="alert alert-danger alert-dimissible d-flex" role="alert">
                <div class="col-11">

                    @foreach ($errors->all() as $error)
                        <h6 class="mb-0">{{ $error }}</h6>
                    @endforeach

                </div>
                <div class="col-1 justify-content-end d-flex">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    @if ($torre)
        <div class="card">

            @if ($inTratamiento)
                <div class="card-header justify-content-center d-flex">
                    <h1 class="card-title mb-0" style="font-size: 3rem"> {{ strtoupper($asistente->fullName()) }}</h1>
                </div>
                <div class="card-body">
                    <h5 class="mb-5">
                        TECNOVIS En cumplimiento de la ley <i>Habeas Data</i> (Ley 1581 de 2012) es responsable por el
                        tratamiento de la informacion recopilada en la presente asamblea; por esta razon, nos
                        permitimos consultar si de forma personal desea que sus datos sean de forma
                        <i>PUBLICA* O PRIVADA*</i>.
                    </h5>
                    <div class="row my-4 justify-content-center">

                        <input type="radio" class="btn-check" id="checkPublico" name="tratamiento"
                            wire:model.live='tratamientoDatos' value="1">
                        <label class="btn btn-outline-info w-auto " for="checkPublico">
                            <h4 class="mb-0">PUBLICO</h4>
                        </label>

                        <input type="radio" class="btn-check" name="tratamiento" id="checkPrivado"
                            wire:model.live='tratamientoDatos' value="2">
                        <label class="btn btn-outline-info w-auto ms-4" for="checkPrivado">
                            <h4 class="mb-0">PRIVADO</h4>
                        </label>

                    </div>



                </div>

                <div class="card-footer d-flex justify-content-between">
                    <div class="col-9">
                        <small>
                            *<b>Publico:</b> Los datos podran ser solicitados por la Administracion de
                            {{ cache('asamblea')['folder'] }} en cualquier momento <br>
                            *<b>Privado:</b> Los datos unicamente podran ser solicitados mediante una orden judicial
                        </small>
                    </div>
                    <div class="col-auto">
                        <input type="text" name="persona_id" value="{{ $asistente->id }}" hidden>
                        <button type="button" class="btn btn-primary d-flex align-items-center"
                            wire:click='setTratamiento' @disabled(!$tratamientoDatos)>
                            <i class="bi bi-feather me-2 fs-5"></i>
                            <h4 class="mb-0">SIGUIENTE</h4>
                        </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card-header text-center">
                    <h1 class="card-title mb-0" style="font-size: 3.5rem"> {{ strtoupper($torre->name) }}</h1>
                    
                    <h3 class="text-muted mb-0">PREDIOS: {{$this->control->predios->count()}}</h1>
                </div>
                
                <div class="card-body text-center">
                    <h3 class="mb-0">Por favor elija un candidato...</h3>
                    <div class="row g-3 mt-3 justify-content-center">
                        @foreach ($candidatos as $id => $candidato)
                            <input type="radio" class="btn-check" name="candidato" id="radio-{{ $id }}"
                                value="{{ $id }}" wire:model.live='candidatoId'>
                            <label class="btn btn-outline-success w-45 me-3 fs-3"
                                for="radio-{{ $id }}">{{ $candidato }}</label>
                        @endforeach
                        <input type="radio" class="btn-check" name="candidato" id="radio-w" value="-1"
                            wire:model.live='candidatoId'>
                        <label class="btn btn-outline-info w-45 me-3 fs-3" for="radio-w">VOTO EN BLANCO</label>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-primary fs-4" data-bs-toggle="modal" data-bs-target="#confirmModal"
                        @disabled(!$candidatoId)>VOTAR</button>
                </div>
            @endif
        </div>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-3" id="exampleModalLabel">CONFIRMAR</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    @if ($candidatoId)
                        <h3 class="mb-0">SU VOTO PARA
                            <span class="badge fs-3 text-bg-info bg-opacity-100">{{ strtoupper($torre->name) }} </span>
                            ES POR <span
                                class="badge mt-1 fs-3 text-bg-primary bg-opacity-100">{{ $candidatoId != -1 ? $candidatos[(string)$candidatoId] : 'VOTO EN BLANCO' }}</span>
                        </h3>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                        wire:click='storeVote'>VOTAR</button>
                </div>
            </div>
        </div>
    </div>
</div>
