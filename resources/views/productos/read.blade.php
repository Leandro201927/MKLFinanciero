@if(isset($pdf))
    <!-- Solo renderiza la tabla para el PDF -->
    <div style="color: black;">
        <table class="table align-items-center mb-0">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-secondary text-xs font-weight-semibold opacity-7">ID</th>
                    <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Nombre</th>
                    <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Tipo</th>
                    <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Cantidad</th>
                    <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Clasificación</th>
                    <th class="text-secondary text-xs font-weight-semibold opacity-7">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($productos)
                    @foreach ($productos as $producto)
                        <tr>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center ms-1">
                                        <h6 class="mb-0 text-sm font-weight-semibold">{{ $producto->ID }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="text-sm text-dark font-weight-semibold mb-0">{{ $producto->Nombre }}</p>
                            </td>
                            <td class="align-middle text-center text-sm">
                                @php
                                    $tipoColors = [
                                        'producto' => 'bg-success',
                                        'gasto' => 'bg-warning', 
                                        'servicio' => 'bg-info'
                                    ];
                                    $tipoColor = $tipoColors[$producto->Tipo] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $tipoColor }} text-dark">{{ ucfirst($producto->Tipo ?? 'N/A') }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="text-secondary text-sm font-weight-normal">
                                    {{ $producto->Tipo === 'producto' ? $producto->Cantidad : 'N/A' }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                @if($producto->Clasificacion && is_array($producto->Clasificacion))
                                    <div class="d-flex flex-wrap justify-content-center">
                                        @foreach($producto->Clasificacion as $key => $value)
                                            <small class="badge bg-light text-dark me-1 mb-1">{{ $key }}: {{ $value }}</small>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('producto.edit', $producto->ID) }}" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Actualizar">Actualizar</a>
                                @if($producto->puedeEliminar)
                                    <form action="{{ route('producto.destroy', $producto->ID) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="border-style:none; background-color: transparent;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Borrar" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Borrar</button>
                                    </form>
                                @else
                                    <span class="text-secondary font-weight-bold text-xs" style="opacity: 0.5; cursor: not-allowed;" data-bs-toggle="tooltip" data-bs-title="Este producto no puede ser eliminado porque tiene ventas o gastos asociados">Borrar</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@else
<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">Productos</h3>
                            <p class="mb-0">Vista general</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mensajes de éxito o error -->
            @if (session('success'))
                <div class="row my-2">
                    <div class="col-lg-12 col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="row my-2">
                    <div class="col-lg-12 col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="row my-4">
                <div class="col-lg-12 col-md-12">
                    <div class="card shadow-xs border">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Productos recientes</h6>
                                    <p class="text-sm mb-sm-0 mb-2">Estos son los detalles sobre los productos (recientes) añadidos.</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <button type="button" onclick="window.location.href='{{ route('producto.create') }}'" class="btn btn-sm btn-white mb-0 me-2">
                                        Crear producto
                                    </button>
                                    <!-- <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center mb-0" onclick="window.location.href='{{url('imprimirProductos')}}'">
                                        <span class="btn-inner--icon">
                                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" class="d-block me-2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                        </span>
                                        <span class="btn-inner--text">Descargar</span>
                                    </button> -->
                                </div>
                            </div>
                            <div class="pb-3 d-sm-flex align-items-center">
                                <form method="GET" action="{{ route('producto') }}" class="w-100">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <select name="tipo" class="form-control form-control-sm" onchange="this.form.submit()">
                                                @foreach($tipos as $value => $label)
                                                    <option value="{{ $value }}" {{ request('tipo') == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre" value="{{ request('buscar') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-sm btn-dark">Filtrar</button>
                                            <a href="{{ route('producto') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
                                        </div>
                                        <div class="col-md-2 text-end" style="display: none;">
                                            <a href="{{ route('producto.exportar') }}" class="btn btn-sm btn-success">
                                                <i class="fa fa-download"></i> Exportar CSV
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                              <table class="table align-items-center mb-0">
                                                                        <thead class="bg-gray-100">
                                          <tr>
                                              <th class="text-secondary text-xs font-weight-semibold opacity-7">ID</th>
                                              <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Nombre</th>
                                              <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Tipo</th>
                                              <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Cantidad</th>
                                              <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Clasificación</th>
                                              <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Descripción</th>
                                              <th class="text-secondary text-xs font-weight-semibold opacity-7">Acciones</th>
                                          </tr>
                                      </thead>
                                  <tbody>
                                    @if($productos)
                                        @foreach ($productos as $producto)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center ms-1">
                                                            <h6 class="mb-0 text-sm font-weight-semibold">{{ $producto->ID }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-dark font-weight-semibold mb-0">{{ $producto->Nombre }}</p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    @php
                                                        $tipoColors = [
                                                            'producto' => 'bg-success',
                                                            'gasto' => 'bg-warning', 
                                                            'servicio' => 'bg-info'
                                                        ];
                                                        $tipoColor = $tipoColors[$producto->Tipo] ?? 'bg-secondary';
                                                    @endphp
                                                    <span class="badge {{ $tipoColor }} text-dark">{{ ucfirst($producto->Tipo ?? 'N/A') }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm font-weight-normal">
                                                        {{ $producto->Tipo === 'producto' ? $producto->Cantidad : 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if($producto->Clasificacion && is_array($producto->Clasificacion))
                                                        <div class="d-flex flex-wrap justify-content-center">
                                                            @foreach($producto->Clasificacion as $key => $value)
                                                                <small class="badge bg-light text-dark me-1 mb-1">{{ $key }}: {{ $value }}</small>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if($producto->Descripcion && ($producto->Tipo === 'gasto' || $producto->Tipo === 'servicio'))
                                                        <span class="text-sm text-secondary" style="max-width: 200px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $producto->Descripcion }}">
                                                            {{ $producto->Descripcion }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('producto.edit', $producto->ID) }}" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Actualizar">Actualizar</a>
                                                    @if($producto->puedeEliminar)
                                                        <form action="{{ route('producto.destroy', $producto->ID) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" style="border-style:none; background-color: transparent;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Borrar" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Borrar</button>
                                                        </form>
                                                    @else
                                                        <span class="text-secondary font-weight-bold text-xs" style="opacity: 0.5; cursor: not-allowed;" data-bs-toggle="tooltip" data-bs-title="Este producto no puede ser eliminado porque tiene ventas o gastos asociados">Borrar</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                  </tbody>
                              </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>
@endif
