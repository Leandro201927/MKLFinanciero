<x-app-layout>
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <x-sidenav-white />
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 start-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute d-flex fixed-top ms-auto h-100 z-index-0 bg-cover me-n8"
                                    style="background-image:url('../img/image-sign-up.jpg')">
                                    <div class="my-auto text-start max-width-350 ms-7">
                                        <!-- <h1 class="mt-3 text-white font-weight-bolder">Comienza tu <br> nuevo viaje.</h1>
                                        <p class="text-white text-lg mt-4 mb-4">Utiliza estos formularios increíbles para iniciar sesión o crear una nueva cuenta en tu proyecto de forma gratuita.</p>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-group d-flex">
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Jessica Rowland">
                                                    <img alt="Image placeholder" src="../img/team-3.jpg"
                                                        class="">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Audrey Love">
                                                    <img alt="Image placeholder" src="../img/team-4.jpg"
                                                        class="rounded-circle">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Michael Lewis">
                                                    <img alt="Image placeholder" src="../img/marie.jpg"
                                                        class="rounded-circle">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Audrey Love">
                                                    <img alt="Image placeholder" src="../img/team-1.jpg"
                                                        class="rounded-circle">
                                                </a>
                                            </div>
                                            <p class="font-weight-bold text-white text-sm mb-0 ms-2">Únete a más de 2.5 millones de usuarios</p>
                                        </div> -->
                                    </div>
                                    <div class="text-start position-absolute fixed-bottom ms-7">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8">
                                <div class="card-header pb-0 text-left bg-transparent">
                                    <h3 class="font-weight-black text-dark display-6">Registrarse</h3>
                                    <p class="mb-0">¡Mucho gusto! Por favor ingresa tus datos.</p>
                                </div>
                                <div class="card-body">
                                    <form role="form">
                                        <label>Nombre</label>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" placeholder="Ingresa tu nombre"
                                                aria-label="Nombre" aria-describedby="name-addon">
                                        </div>
                                        <label>Dirección de correo electrónico</label>
                                        <div class="mb-3">
                                            <input type="email" class="form-control"
                                                placeholder="Ingresa tu dirección de correo electrónico" aria-label="Email"
                                                aria-describedby="email-addon">
                                        </div>
                                        <label>Contraseña</label>
                                        <div class="mb-3">
                                            <input type="email" class="form-control" placeholder="Crea una contraseña"
                                                aria-label="Contraseña" aria-describedby="password-addon">
                                        </div>
                                        <div class="form-check form-check-info text-left mb-0">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="flexCheckDefault">
                                            <label class="font-weight-normal text-dark mb-0" for="flexCheckDefault">
                                                Acepto los <a href="javascript:;"
                                                    class="text-dark font-weight-bold">Términos y Condiciones</a>.
                                            </label>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-dark w-100 mt-4 mb-3">Registrarse</button>
                                            <button type="button" class="btn btn-white btn-icon w-100 mb-3">
                                                <span class="btn-inner--icon me-1">
                                                    <img class="w-5" src="../img/logos/google-logo.svg"
                                                        alt="google-logo" />
                                                </span>
                                                <span class="btn-inner--text">Registrarse con Google</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-xs mx-auto">
                                        ¿Ya tienes una cuenta?
                                        <a href="javascript:;" class="text-dark font-weight-bold">Iniciar sesión</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</x-app-layout>