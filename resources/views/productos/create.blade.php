<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">Productos</h3>
                            <p class="mb-0">Crear producto</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-lg-12 col-md-12">
                    <div class="card shadow-xs border">
                        <div class="card-body px-3 py-4">
                            <div class="table-responsive p-0">
                                <form role="form" class="text-start" action="{{ route('producto.store') }}" method="POST">
                                    @csrf

                                    <label for="Nombre">Nombre:</label>
                                    <div class="mb-3">
                                        <input type="text" id="Nombre" name="Nombre" class="form-control" placeholder="Ingresa el nombre del producto" aria-label="Nombre" aria-describedby="nombre-addon">
                                    </div>

                                    <label for="Precio">Precio:</label>
                                    <div class="mb-3">
                                        <input type="text" id="Precio" name="Precio" class="form-control" placeholder="Ingresa el precio del producto" aria-label="Precio" aria-describedby="precio-addon">
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Crear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>
