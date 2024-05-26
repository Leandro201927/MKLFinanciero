<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">Gastos</h3>
                            <p class="mb-0">Editar gasto</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-lg-12 col-md-12">
                    <div class="card shadow-xs border">
                        <div class="card-body px-3 py-4">
                            <div class="table-responsive p-0" style="overflow: hidden">
                                <form role="form" class="text-start" action="{{ route('gasto.update', $gasto->ID) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <label for="Descripcion">Descripcion:</label>
                                    <div class="mb-3">
                                        <input type="text" id="Descripcion" name="Descripcion" value="{{ $gasto->Descripcion }}" class="form-control" placeholder="Ingresa la descripcion del gasto" aria-label="Nombre" aria-describedby="nombre-addon">
                                    </div>

                                    <!-- Itera sobre los productos y cantidades preestablecidos -->
                                    <div class="mb-3" id="filas-container">
                                        @foreach ($productosCantidades as $productoCantidad)
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label for="producto{{ $productoCantidad->ProductoID }}">Producto:</label>
                                                    <select id="producto{{ $productoCantidad->ProductoID }}" name="productos[]" class="form-control">
                                                        @foreach ($productosDisponibles as $producto)
                                                            <option value="{{ $producto->ID }}" {{ $producto->ID == $productoCantidad->ProductoID ? 'selected' : '' }}>
                                                                {{ $producto->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="cantidad{{ $productoCantidad->ProductoID }}">Cantidad:</label>
                                                    <input type="number" name="cantidades[]" value="{{ $productoCantidad->Cantidad_Productos }}" class="form-control" placeholder="Cantidad">
                                                </div>
                                                <div class="col-md-2 d-flex">
                                                    <button type="button" class="btn btn-danger w-100" style="margin-top: auto; margin-bottom: 0;" onclick="eliminarFila(this)">Eliminar</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="button" class="btn btn-dark" onclick="agregarFila()">Agregar Producto</button>

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

    <script>
        function eliminarFila(button) {
            const fila = button.closest('.mb-3');
            fila.remove();
        }
        function agregarFila() {
            const fila = `
                <div class="row mb-3">
                    <div class="col-md-5">
                        <label for="producto{{ $productoCantidad->ProductoID }}">Producto:</label>
                        <select id="producto{{ $productoCantidad->ProductoID }}" name="productos[]" class="form-control">
                            @foreach ($productosDisponibles as $producto)
                                <option value="{{ $producto->ID }}" {{ $producto->ID == $productoCantidad->ProductoID ? 'selected' : '' }}>
                                    {{ $producto->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="cantidad{{ $productoCantidad->ProductoID }}">Cantidad:</label>
                        <input type="number" name="cantidades[]" value="0" class="form-control" placeholder="Cantidad">
                    </div>
                    <div class="col-md-2 d-flex">
                        <button type="button" class="btn btn-danger w-100" style="margin-top: auto; margin-bottom: 0;" onclick="eliminarFila(this)">Eliminar</button>
                    </div>
                </div>
            `;
            document.getElementById('filas-container').insertAdjacentHTML('beforeend', fila);
        }
    </script>

</x-app-layout>
