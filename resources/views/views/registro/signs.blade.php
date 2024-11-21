<div>

    <style>

    </style>
    <x-alerts />
    <p wire:poll.1s='getPersona' class="hidden">
        
    </p>

    <div class="position-fixed z-2 top-0 start-0 px-4 pt-2 d-flex justify-content-between align-items-center">
        <button wire:click='goBack' class="btn btn-primary py-0">
            <i class="bi bi-arrow-bar-left fs-3"></i>
        </button>
    </div>
    <div class="mt-5 ">

        <div class=" py-5  my-5 d-flex justify-content-center align-content-center">

            @if ($persona)
                <div class="card my-5" style="width:70%;">
                    @if ($tratamiento)
                        <div class="card-body">
                            <h5 class="mb-5">
                                TECNOVIS S.A. En cumplimiento de la ley <i>Habeas Data</i> es responsable por el
                                tratamiento de la informacion recopilada en la presente asamblea; por esta razon, nos
                                permitimos consultar si de forma personal desea que sus datos sean de forma
                                <i>PUBLICA* O PRIVADA*</i>.
                            </h5>
                            <div class="row my-4 justify-content-center">

                                <input type="radio" class="btn-check" id="checkPublico" name="tratamiento"
                                    wire:change='changed' wire:model='tratamientoPublico' value="1">
                                <label class="btn btn-outline-info w-auto " for="checkPublico">
                                    <h4 class="mb-0">PUBLICO</h4>
                                </label>

                                <input type="radio" class="btn-check" name="tratamiento" id="checkPrivado"
                                    wire:change='changed' wire:model='tratamientoPublico' value="0">
                                <label class="btn btn-outline-info w-auto ms-4" for="checkPrivado">
                                    <h4 class="mb-0">PRIVADO</h4>
                                </label>

                            </div>



                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <div class="col-9">
                                <small>
                                    *La calidad de PUBLICO o PRIVADO en el tratamiento de los datos esta establecido en
                                    Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                                </small>
                            </div>
                            <div class="col-3">
                                <form action="{{ route('asistencia.signing') }}" method="get" id="formSign"
                                class="justify-content-end d-flex">
                                    @csrf
                                    <input type="text" name="persona_id" value="{{ $persona->id }}" hidden>
                                    <button type="button" class="btn btn-primary d-flex align-items-center"
                                        wire:click='setTratamiento' @disabled(!$enabled)>
                                        <i class="bi bi-feather me-2 fs-5"></i>
                                        <h4 class="mb-0">FIRMAR</h4>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="card-body" >
                            <h1 class="card-title">{{ $persona->nombre }} {{ $persona->apellido }}</h1>
                            <h3 class="card-subtitle mb-2 text-muted ">{{ $persona->tipo_id }}. {{ $persona->id }}
                            </h3>

                        </div>

                        <div class="card-footer d-flex justify-content-end">


                            <button type="sumbit" class="btn btn-primary d-flex align-items-center"
                                wire:click='goTratamiento'>
                                <h4 class="mb-0">Siguiente</h4>
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@script
    <script>
        $wire.on('submit-form', () => {
            document.getElementById('formSign').submit()
        });
    </script>
@endscript
