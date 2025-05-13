<x-guest-layout>
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <x-guest.sidenav-guest />
            </div>
        </div>
    </div>
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-md-6 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8">
                                <div class="card-header pb-0 text-left bg-transparent text-center">
                                    <h3 class="font-weight-black text-dark display-6">Verificación de código</h3>
                                    <p class="mb-0">Hemos enviado un código de 6 dígitos a <strong>{{ $email }}</strong></p>
                                </div>
                                <div class="text-center">
                                    @if (session('status'))
                                        <div class="alert alert-success text-sm" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @error('code')
                                        <div class="alert alert-danger text-sm" role="alert">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="card-body">
                                    <form role="form" class="text-start" method="POST" action="{{ route('verify.code') }}">
                                        @csrf
                                        <label>Código de verificación</label>
                                        <div class="mb-3">
                                            <input type="text" id="code" name="code" class="form-control"
                                                placeholder="Ingresa el código de 6 dígitos"
                                                aria-label="Code" pattern="[0-9]{6}" required autofocus>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Verificar código</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-xs mx-auto">
                                        ¿No recibiste el código?
                                        <a href="{{ route('verify.email.form') }}" class="text-dark font-weight-bold">Volver a intentar</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 end-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute fixed-top ms-auto h-100 z-index-0 bg-cover ms-n8"
                                    style="background-image:url('http://cygnus.uniajc.edu.co/MKLFinanciero/public/img/image-sign-in.jpg')">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-guest-layout> 