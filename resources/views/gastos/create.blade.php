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
                                <form role="form" class="text-start" action="{{ route('gasto.store') }}" method="POST">
                                    @csrf

                                    <label for="Descripcion">Descripcion:</label>
                                    <div class="mb-3">
                                        <input type="text" id="Descripcion" name="Descripcion" class="form-control @error('Descripcion') is-invalid @enderror" 
                                            placeholder="Ingresa la descripción del gasto" value="{{ old('Descripcion') }}" 
                                            aria-label="Descripcion" aria-describedby="descripcion-addon">
                                        @error('Descripcion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3" id="filas-container">
                                        <!-- Aquí se agregarán las filas dinámicas -->
                                        @if (old('productos'))
                                            @foreach (old('productos') as $key => $productoID)
                                                <div class="row my-2">
                                                    <div class="col-md-4">
                                                        <select name="productos[]" class="form-control @error('productos.'.$key) is-invalid @enderror">
                                                            @foreach ($productos as $producto)
                                                                <option value="{{ $producto->ID }}" {{ $productoID == $producto->ID ? 'selected' : '' }}>{{ $producto->Nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('productos.'.$key)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" name="cantidades[]" min="1" class="form-control @error('cantidades.'.$key) is-invalid @enderror" 
                                                            placeholder="Cantidad" value="{{ old('cantidades.'.$key) }}">
                                                        @error('cantidades.'.$key)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" step="0.01" name="valores_unitarios[]" min="0" class="form-control @error('valores_unitarios.'.$key) is-invalid @enderror" 
                                                            placeholder="Valor Unitario" value="{{ old('valores_unitarios.'.$key) }}">
                                                        @error('valores_unitarios.'.$key)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
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
            const fila = button.closest('.row');
            fila.remove();
        }
        function agregarFila() {
            const fila = `
                <div class="row my-2">
                    <div class="col-md-4">
                        <select name="productos[]" class="form-control">
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->ID }}">{{ $producto->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="cantidades[]" min="1" class="form-control" placeholder="Cantidad">
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.01" name="valores_unitarios[]" min="0" class="form-control" placeholder="Valor Unitario">
                    </div>
                </div>
            `;
            document.getElementById('filas-container').insertAdjacentHTML('beforeend', fila);
        }

        // Agregar al menos una fila si no hay ninguna
        window.onload = function() {
            if (document.getElementById('filas-container').children.length === 0) {
                agregarFila();
            }
        };
    </script>

</x-app-layout>
