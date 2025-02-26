<div>
    <x-alerts />
    <div class="row justify-content-center px-5">
        @if ($host)
            <div class="text-center mb-1"><span class="badge fs-2 text-bg-primary"><h1 class="mb-0">Conectado al servidor: {{ $host }}</h1></span></div>
        @endif
        @foreach ($panels as $panel)
            @if ($panel['visible'])
                <button class="btn p-0 mx-1 my-1 " style="width: 300px;" {{ $panel['directives'] }}
                    @disabled(!$panel['enabled'])>
                    <div class="card ">
                        <div class="row g-0">
                            <div class="col-4">
                                <i class="bi {{ $panel['icon'] }}" style="font-size:80px"></i>
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $panel['title'] }}</h5>
                                    <p class="card-text"><small class="text-body-secondary">
                                            {{ $panel['body'] }}
                                        </small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </button>
            @endif
        @endforeach

    </div>



    <div class="modal fade" id="spinnerModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="spinner-grow text-primary" style="width: 4rem; height: 4rem;" role="status">

                    </div>
                    <span class="ms-3 " style="font-size: 5rem">Cargando ...</span>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="logOutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">¿Desea cerrar sesión?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="{{ route('users.logout') }}" class="btn btn-warning">Continuar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="connectionModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Conectarse a una sesión</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @if ($host)
                    <form action="{{ route('session.disconnect') }}" method="POST">
                        @csrf
                        <div class="modal-body d-flex align-items-center justify-content-between">

                            <span for="ip">Desconectarse del servidor</span>
                            <button type="submit" class="btn btn-danger"
                                data-bs-dismiss="modal">Desconectar</button>


                        </div>
                    </form>
                @else
                    <form action="{{ route('session.connect') }}" method="POST">
                        @csrf
                        <div class="modal-body d-flex align-items-center justify-content-between">
                            <div class="form-group">
                                <label for="ip">Por favor digite la ip del servidor:</label>
                                <input type="text" class="form-control" placeholder="IP" id="ip"
                                    name="ip">
                            </div>
                            <div class=" ms-2">
                                <button type="submit" class="btn btn-success"
                                    data-bs-dismiss="modal">Conectar</button>
                            </div>

                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>


</div>
