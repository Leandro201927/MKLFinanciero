<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">Productos</h3>
                            <p class="mb-0">Editar producto</p>
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

                            <div class="table-responsive p-0">
                                <form role="form" class="text-start" action="{{ route('producto.update', $producto->ID) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <label for="Nombre">Nombre:</label>
                                    <div class="mb-3">
                                        <input type="text" id="Nombre" name="Nombre" class="form-control @error('Nombre') is-invalid @enderror" 
                                            placeholder="Ingresa el nombre del producto" 
                                            value="{{ old('Nombre', $producto->Nombre) }}" 
                                            aria-label="Nombre" aria-describedby="nombre-addon">
                                        @error('Nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <label for="Precio">Precio:</label>
                                    <div class="mb-3">
                                        <input type="text" id="Precio" name="Precio" class="form-control @error('Precio') is-invalid @enderror" 
                                            placeholder="Ingresa el precio del producto" 
                                            value="{{ old('Precio', $producto->Precio) }}" 
                                            aria-label="Precio" aria-describedby="precio-addon">
                                        @error('Precio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
