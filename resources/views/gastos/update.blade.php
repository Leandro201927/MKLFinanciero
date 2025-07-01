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
                <div class="col-lg-8 col-md-12">
                    <div class="card shadow-xs border">
                        <div class="card-body px-3 py-4">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            
                            <div class="table-responsive p-0" style="overflow: hidden">
                                <form role="form" class="text-start" action="{{ route('gasto.update', $gasto->ID) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <label for="Descripcion">Descripcion:</label>
                                    <div class="mb-3">
                                        <input type="text" id="Descripcion" name="Descripcion" class="form-control @error('Descripcion') is-invalid @enderror" 
                                            placeholder="Ingresa la descripcion del gasto" 
                                            value="{{ old('Descripcion', $gasto->Descripcion) }}" 
                                            aria-label="Descripcion" aria-describedby="descripcion-addon">
                                        @error('Descripcion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Itera sobre los productos y cantidades preestablecidos -->
                                    <div class="mb-3" id="filas-container">
                                        @if (old('productos'))
                                            @foreach (old('productos') as $key => $productoID)
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label>Producto:</label>
                                                        <select name="productos[]" class="form-control producto-select @error('productos.'.$key) is-invalid @enderror">
                                                            @foreach ($productosDisponibles as $producto)
                                                                <option value="{{ $producto->ID }}" {{ $productoID == $producto->ID ? 'selected' : '' }}>
                                                                    {{ $producto->Nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('productos.'.$key)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Cantidad:</label>
                                                        <input type="number" name="cantidades[]" min="1" 
                                                            class="form-control cantidad-input @error('cantidades.'.$key) is-invalid @enderror" 
                                                            placeholder="Cantidad" value="{{ old('cantidades.'.$key) }}">
                                                        @error('cantidades.'.$key)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Valor Unitario:</label>
                                                        <input type="number" name="valores_unitarios[]" min="0" step="0.01"
                                                            class="form-control valor-input @error('valores_unitarios.'.$key) is-invalid @enderror" 
                                                            placeholder="Valor Unitario" value="{{ old('valores_unitarios.'.$key) }}">
                                                        @error('valores_unitarios.'.$key)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-3 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger w-100" onclick="eliminarFila(this)">Eliminar</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach ($productosCantidades as $productoCantidad)
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label>Producto:</label>
                                                        <select name="productos[]" class="form-control producto-select">
                                                            @foreach ($productosDisponibles as $producto)
                                                                <option value="{{ $producto->ID }}" {{ $producto->ID == $productoCantidad->ProductoID ? 'selected' : '' }}>
                                                                    {{ $producto->Nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Cantidad:</label>
                                                        <input type="number" name="cantidades[]" min="1" value="{{ $productoCantidad->Cantidad_Productos }}" class="form-control cantidad-input" placeholder="Cantidad">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Valor Unitario:</label>
                                                        <input type="number" name="valores_unitarios[]" min="0" step="0.01" value="{{ $productoCantidad->Valor_Unitario }}" class="form-control valor-input" placeholder="Valor Unitario">
                                                    </div>
                                                    <div class="col-md-3 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger w-100" onclick="eliminarFila(this)">Eliminar</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
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
                <div class="col-lg-4 col-md-12">
                    <div class="card shadow-xs border">
                        <div class="card-header pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Resumen del gasto</h6>
                        </div>
                        <div class="card-body px-3 py-4">
                            <div id="resumen-productos">
                                <!-- Aquí se mostrará el resumen de productos -->
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Total:</h6>
                                <h6 class="mb-0" id="total-gasto">$0,00</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function formatearMoneda(valor) {
            return parseFloat(valor).toLocaleString('es-ES', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        
        function eliminarFila(button) {
            const fila = button.closest('.row');
            fila.remove();
            actualizarResumen();
        }

        function agregarFila() {
            const fila = `
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Producto:</label>
                        <select name="productos[]" class="form-control producto-select">
                            @foreach ($productosDisponibles as $producto)
                                <option value="{{ $producto->ID }}">
                                    {{ $producto->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Cantidad:</label>
                        <input type="number" name="cantidades[]" min="1" value="1" class="form-control cantidad-input" placeholder="Cantidad">
                    </div>
                    <div class="col-md-3">
                        <label>Valor Unitario:</label>
                        <input type="number" name="valores_unitarios[]" min="0" step="0.01" value="0" class="form-control valor-input" placeholder="Valor Unitario">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100" onclick="eliminarFila(this)">Eliminar</button>
                    </div>
                </div>
            `;
            document.getElementById('filas-container').insertAdjacentHTML('beforeend', fila);
            actualizarResumen();
        }

        function actualizarResumen() {
            const filas = document.querySelectorAll('#filas-container .row');
            const resumenContainer = document.getElementById('resumen-productos');
            let total = 0;
            let html = '';

            filas.forEach((fila, index) => {
                const selectElement = fila.querySelector('.producto-select');
                const selectedOption = selectElement ? selectElement.querySelector('option:checked') : null;
                const producto = selectedOption ? selectedOption.text : 'Producto no seleccionado';
                const cantidad = parseFloat(fila.querySelector('.cantidad-input').value) || 0;
                const valorUnitario = parseFloat(fila.querySelector('.valor-input').value) || 0;
                const subtotal = cantidad * valorUnitario;
                total += subtotal;

                if (selectedOption && cantidad > 0 && valorUnitario > 0) {
                    html += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0 text-sm">${producto}</h6>
                                <p class="text-xs text-secondary mb-0">${cantidad} x $${formatearMoneda(valorUnitario)}</p>
                            </div>
                            <h6 class="mb-0">$${formatearMoneda(subtotal)}</h6>
                        </div>
                    `;
                }
            });

            resumenContainer.innerHTML = html;
            document.getElementById('total-gasto').textContent = `$${formatearMoneda(total)}`;
        }

        // Agregar event listeners para actualizar el resumen
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('producto-select') || 
                e.target.classList.contains('cantidad-input') || 
                e.target.classList.contains('valor-input')) {
                actualizarResumen();
            }
        });

        // Verificar que haya al menos una fila
        window.onload = function() {
            if (document.getElementById('filas-container').children.length === 0) {
                agregarFila();
            }
            actualizarResumen();
        };
    </script>

</x-app-layout>
