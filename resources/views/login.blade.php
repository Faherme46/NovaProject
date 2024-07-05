<link rel="stylesheet" href="assets/scss/login.scss">

@extends('layout.app')


@section('content')
    <div class="mt-5">

        <div class="row d-flex justify-content-center align-items-center ">
            <div class="col-10">
                <div class="card rounded-3 text-black">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="card-body  mx-5 px-5">
                                <style>
                                    /* Ejemplo de estilo personalizado */
                                    .card-title {
                                        color: red;
                                        /* Cambia el color para ver si se aplica correctamente */
                                    }
                                </style>
                                <div class="text-center">
                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp"
                                        style="width: 185px;" alt="logo">
                                    <h4 class="mt-1 mb-5 pb-1 card-title">Bienvenido al equipo Tecnovis</h4>
                                </div>

                                <form action="{{ route('users.authenticate') }}" method="POST">
                                    @csrf

                                    <div class="form-outline mb-4">
                                        <input type="text" id="form2Example11" class="form-control"
                                            placeholder="Username" name="username" />
                                        <small class="form-label text-muted" for="form2Example11">Username</small>

                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="password" id="form2Example22" class="form-control" name="password" />
                                        <small class="form-label text-muted" for="form2Example22">Password</small>
                                    </div>

                                    <div class="text-center pt-1 mb-5 pb-1">
                                        <button class="btn btn-dark fa-lg gradient-custom-2 mb-3" type="submit">
                                            Login
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                        <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                            <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                                <h3 class="mb-4">Somos mas que una compañia</h3>
                                <p class=" mb-0 ">En Tecnovis, no solo gestionamos logística;creamos experiencias que unen
                                    comunidades.
                                    Nuestra pasión por la innovación y el compromiso con la excelencia nos impulsa a
                                    transformar
                                    cada experiencia en una oportunidad para construir juntos la mejor alternativa.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
