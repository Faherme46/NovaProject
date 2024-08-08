@extends('layout.app')


@section('content')
    <div class="mt-5">

        <div class="row d-flex justify-content-center align-items-center ">
            <div class="col-10">
                <div class="card rounded-3 text-black">
                    <div class="row g-0">
                        <div class="col-6">
                            <div class="card-body  mx-5 px-5">
                                <style>
                                    /* Ejemplo de estilo personalizado */
                                    .card-title {
                                        color: red;
                                        /* Cambia el color para ver si se aplica correctamente */
                                    }

                                    .gradient-custom-2 {
                                        /* fallback for old browsers */
                                        background: #fccb90;

                                        /* Chrome 10-25, Safari 5.1-6 */
                                        background: -webkit-linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);

                                        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
                                        background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
                                    }

                                    @media (min-width: 768px) {
                                        .gradient-form {
                                            height: 90vh !important;
                                        }
                                    }

                                    @media (min-width: 769px) {
                                        .gradient-custom-2 {
                                            border-top-right-radius: .3rem;
                                            border-bottom-right-radius: .3rem;
                                        }
                                    }
                                </style>
                                <div class="text-center mb-4">
                                    <img src="/storage/images/logo.png"
                                        style="width: 185px;" alt="logo">
                                </div>

                                <form action="{{ route('users.authenticate') }}" method="POST">
                                    @csrf

                                    <div class="form-outline mb-4">
                                        <input type="text" id="form2Example11" class="form-control"
                                             name="username" />
                                        <small class="form-label text-muted" for="form2Example11">Usuario</small>

                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="password" id="form2Example22" class="form-control" name="password" />
                                        <small class="form-label text-muted" for="form2Example22">Contraseña</small>
                                    </div>

                                    <div class="text-center pt-1 mb-5 pb-1">
                                        <button class="btn btn-dark fa-lg gradient-custom-2 mb-3" type="submit">
                                            Login
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                        <div class="col-6 d-flex align-items-center gradient-custom-2">
                            <div class="text-white px-3  mx-4">
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
