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

                            <div class="table-responsive p-0">
                                <form role="form" class="text-start" action="{{ route('producto.store') }}" method="POST">
                                    @csrf

                                    <label for="Nombre">Nombre:</label>
                                    <div class="mb-3">
                                        <input type="text" id="Nombre" name="Nombre" class="form-control @error('Nombre') is-invalid @enderror" 
                                            placeholder="Ingresa el nombre del producto" value="{{ old('Nombre') }}" 
                                            aria-label="Nombre" aria-describedby="nombre-addon">
                                        @error('Nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <label for="Tipo">Tipo:</label>
                                    <div class="mb-3">
                                        <select id="Tipo" name="Tipo" class="form-control @error('Tipo') is-invalid @enderror" 
                                            aria-label="Tipo" aria-describedby="tipo-addon" onchange="toggleCantidadField()">
                                            <option value="">Selecciona el tipo</option>
                                            <option value="producto" {{ old('Tipo') == 'producto' ? 'selected' : '' }}>Producto</option>
                                            <option value="gasto" {{ old('Tipo') == 'gasto' ? 'selected' : '' }}>Gasto</option>
                                            <option value="servicio" {{ old('Tipo') == 'servicio' ? 'selected' : '' }}>Servicio</option>
                                        </select>
                                        @error('Tipo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div id="cantidad-field" style="display: none;">
                                        <label for="Cantidad">Cantidad Inicial:</label>
                                        <div class="mb-3">
                                            <input type="number" id="Cantidad" name="Cantidad" min="0" class="form-control @error('Cantidad') is-invalid @enderror" 
                                                placeholder="Ingresa la cantidad inicial del producto" value="{{ old('Cantidad') }}" 
                                                aria-label="Cantidad" aria-describedby="cantidad-addon">
                                            @error('Cantidad')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div id="clasificacion-section" style="display: none;">
                                        <label>Clasificación (opcional):</label>
                                        <div class="mb-3" id="clasificacion-container">
                                            <div class="row mb-2">
                                                <div class="col-md-5">
                                                    <input type="text" name="clasificacion_keys[]" class="form-control" placeholder="Categoría (ej: Marca, Color, Tamaño)">
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="clasificacion_values[]" class="form-control" placeholder="Valor (ej: Nike, Rojo, Grande)">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="removeClasificacion(this)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="addClasificacion()">
                                            + Agregar Clasificación
                                        </button>
                                    </div>

                                    <div id="descripcion-section" style="display: none;">
                                        <label for="Descripcion">Descripción:</label>
                                        <div class="mb-3">
                                            <textarea id="Descripcion" name="Descripcion" rows="3" class="form-control @error('Descripcion') is-invalid @enderror" 
                                                placeholder="Describe el servicio o gasto..." aria-label="Descripcion">{{ old('Descripcion') }}</textarea>
                                            @error('Descripcion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Crear</button>
                                    </div>
                                </form>
                            </div>

                            <script>
                                function toggleCantidadField() {
                                    const tipoSelect = document.getElementById('Tipo');
                                    const cantidadField = document.getElementById('cantidad-field');
                                    const clasificacionSection = document.getElementById('clasificacion-section');
                                    const descripcionSection = document.getElementById('descripcion-section');
                                    
                                    if (tipoSelect.value === 'producto') {
                                        cantidadField.style.display = 'block';
                                        clasificacionSection.style.display = 'block';
                                        descripcionSection.style.display = 'none';
                                        document.getElementById('Descripcion').value = '';
                                    } else if (tipoSelect.value === 'gasto' || tipoSelect.value === 'servicio') {
                                        cantidadField.style.display = 'none';
                                        clasificacionSection.style.display = 'none';
                                        descripcionSection.style.display = 'block';
                                        document.getElementById('Cantidad').value = '';
                                        // Limpiar clasificaciones
                                        clearClasificaciones();
                                    } else {
                                        cantidadField.style.display = 'none';
                                        clasificacionSection.style.display = 'none';
                                        descripcionSection.style.display = 'none';
                                        document.getElementById('Cantidad').value = '';
                                        document.getElementById('Descripcion').value = '';
                                        clearClasificaciones();
                                    }
                                }

                                function clearClasificaciones() {
                                    const container = document.getElementById('clasificacion-container');
                                    // Limpiar todos los inputs de clasificación
                                    const inputs = container.querySelectorAll('input');
                                    inputs.forEach(input => input.value = '');
                                    
                                    // Mantener solo la primera fila
                                    const rows = container.querySelectorAll('.row');
                                    for (let i = 1; i < rows.length; i++) {
                                        rows[i].remove();
                                    }
                                }

                                function addClasificacion() {
                                    const container = document.getElementById('clasificacion-container');
                                    const newRow = document.createElement('div');
                                    newRow.className = 'row mb-2';
                                    newRow.innerHTML = `
                                        <div class="col-md-5">
                                            <input type="text" name="clasificacion_keys[]" class="form-control" placeholder="Categoría (ej: Marca, Color, Tamaño)">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="clasificacion_values[]" class="form-control" placeholder="Valor (ej: Nike, Rojo, Grande)">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-secondary" onclick="removeClasificacion(this)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    `;
                                    container.appendChild(newRow);
                                }

                                function removeClasificacion(button) {
                                    button.closest('.row').remove();
                                }

                                // Mostrar cantidad inicial y clasificaciones si ya está seleccionado 'producto'
                                document.addEventListener('DOMContentLoaded', function() {
                                    toggleCantidadField();
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>
