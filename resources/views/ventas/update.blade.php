<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">Ventas</h3>
                            <p class="mb-0">Editar venta</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-lg-12 col-md-12">
                    <div class="card shadow-xs border">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                <div>
                                    <!-- <h6 class="font-weight-semibold text-lg mb-0">Editando producto</h6>
                                    <p class="text-sm mb-sm-0 mb-2">Estos son los detalles sobre los productos (recientes) añadidos.</p> -->
                                </div>
                            </div>
                            <div class="pb-3 d-sm-flex align-items-center">
                              <!--
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable1"
                                        autocomplete="off" checked>
                                    <label class="btn btn-white px-3 mb-0" for="btnradiotable1">All</label>
                                    <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable2"
                                        autocomplete="off">
                                    <label class="btn btn-white px-3 mb-0" for="btnradiotable2">Monitored</label>
                                    <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable3"
                                        autocomplete="off">
                                    <label class="btn btn-white px-3 mb-0" for="btnradiotable3">Unmonitored</label>
                                </div>
                              -->
                            </div>
                        </div>
                        <div class="card-body px-3 py-4">
                            <div class="table-responsive p-0">
                                <form role="form" class="text-start" action="{{ route('venta.update', $venta->ID) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <label for="Descripcion">Descripcion:</label>
                                    <div class="mb-3">
                                        <input type="text" id="Descripcion" name="Descripcion" value="{{ $venta->Descripcion }}" class="form-control" placeholder="Ingresa la descripcion de la venta" aria-label="Nombre" aria-describedby="nombre-addon">
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Actualizar</button>
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
