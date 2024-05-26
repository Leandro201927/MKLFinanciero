<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">Gastos</h3>
                            <p class="mb-0">Crear gasto</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-lg-12 col-md-12">
                    <div class="card shadow-xs border">
                        <div class="card-body px-3 py-4">
                            <div class="table-responsive p-0" style="overflow: hidden">
                                <form role="form" class="text-start" action="{{ route('gasto.store') }}" method="POST">
                                    @csrf

                                    <label for="Descripcion">Descripcion:</label>
                                    <div class="mb-3">
                                        <input type="text" id="Descripcion" name="Descripcion" class="form-control" placeholder="Ingresa la descripción del gasto" aria-label="Descripcion" aria-describedby="nombre-addon">
                                    </div>

                                    <div class="mb-3" id="filas-container">
                                        <!-- Aquí se agregarán las filas dinámicas -->
                                    </div>
                                    <button type="button" class="btn btn-dark" onclick="agregarFila()">Agregar Producto</button>

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

    <script>
        function eliminarFila(button) {
            const fila = button.closest('.mb-3');
            fila.remove();
        }
        function agregarFila() {
            const fila = `
                <div class="row my-2">
                    <div class="col-md-6">
                        <select name="productos[]" class="form-control">
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->ID }}">{{ $producto->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="number" name="cantidades[]" class="form-control" placeholder="Cantidad">
                    </div>
                </div>
            `;
            document.getElementById('filas-container').insertAdjacentHTML('beforeend', fila);
        }
    </script>

</x-app-layout>
