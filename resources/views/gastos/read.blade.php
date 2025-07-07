@if(isset($pdf))
<div style="color: black;">
    <table class="table align-items-center mb-0">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-secondary text-xs font-weight-semibold opacity-7">ID</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Código</th>
                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Descripcion</th>
                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Fecha_Gasto</th>
                <th class="text-secondary text-xs font-weight-     opacity-7">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gastos as $gasto)
                <tr>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center ms-1">
                                <h6 class="mb-0 text-sm font-weight-semibold">{{ $gasto->ID }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $gasto->Codigo }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                        <span class="text-secondary text-sm font-weight-normal">{{ $gasto->Descripcion }}</span>
                    </td>
                    <td class="align-middle text-center">
                        <span class="text-secondary text-sm font-weight-normal">{{ $gasto->Fecha_Gasto }}</span>
                    </td>
                    <td class="align-middle">
                        <a href="{{ route('gasto.edit', $gasto->ID) }}" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Actualizar">Actualizar</a>
                        <form action="{{ route('gasto.destroy', $gasto->ID) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="border-style:none; background-color: transparent;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Borrar">Borrar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
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
                            <h3 class="font-weight-bold mb-0">Gastos</h3>
                            <p class="mb-0">Vista general</p>
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Gastos recientes</h6>
                                    <p class="text-sm mb-sm-0 mb-2">Estos son los detalles sobre las (recientes) añadidas.</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <button type="button" onclick="window.location.href='{{ route('gasto.create') }}'" class="btn btn-sm btn-white mb-0 me-2">
                                        Crear gasto
                                    </button>
                                    <!-- <button type="button"
                                        onclick="window.location.href='{{url('imprimirVenta')}}'"
                                        class="btn btn-sm btn-dark btn-icon d-flex align-items-center mb-0">
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
                                <form method="GET" action="{{ route('gasto') }}" class="w-100">
                                    <div class="row g-2">
                                        <div class="col-md-2">
                                            <input type="date" name="fecha_desde" class="form-control form-control-sm" placeholder="Fecha desde" value="{{ request('fecha_desde') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="fecha_hasta" class="form-control form-control-sm" placeholder="Fecha hasta" value="{{ request('fecha_hasta') }}">
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
                                                <input type="text" name="buscar" class="form-control" placeholder="Buscar por código o descripción" value="{{ request('buscar') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-sm btn-dark">Filtrar</button>
                                            <a href="{{ route('gasto') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
                                        </div>
                                        <div class="col-md-2 text-end" style="display: none;">
                                            <a href="{{ route('gasto.exportar') }}" class="btn btn-sm btn-success">
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
                                          <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Código</th>
                                          <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Descripcion</th>
                                          <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Productos/Servicios</th>
                                          <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Fecha_Gasto</th>
                                          <th class="text-secondary text-xs font-weight-     opacity-7">Acciones</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($gastos as $gasto)
                                          <tr>
                                              <td>
                                                  <div class="d-flex px-2 py-1">
                                                      <div class="d-flex flex-column justify-content-center ms-1">
                                                          <h6 class="mb-0 text-sm font-weight-semibold">{{ $gasto->ID }}</h6>
                                                      </div>
                                                  </div>
                                              </td>
                                              <td>
                                                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ $gasto->Codigo }}</p>
                                              </td>
                                              <td class="align-middle text-center text-sm">
                                                  <span class="text-secondary text-sm font-weight-normal">{{ $gasto->Descripcion }}</span>
                                              </td>
                                              <td class="align-middle text-center">
                                                  @if($gasto->productos && $gasto->productos->count() > 0)
                                                      <div class="d-flex flex-column">
                                                          @foreach($gasto->productos as $productoGasto)
                                                              <small class="text-dark mb-1">
                                                                  <strong>{{ $productoGasto->producto->Nombre }}</strong>
                                                                  @if($productoGasto->producto->Descripcion && ($productoGasto->producto->Tipo === 'gasto' || $productoGasto->producto->Tipo === 'servicio'))
                                                                      <br><em class="text-muted">{{ $productoGasto->producto->Descripcion }}</em>
                                                                  @endif
                                                                  @if($productoGasto->producto->Tipo === 'producto')
                                                                      <br><span class="badge bg-light text-dark">Cant: {{ $productoGasto->Cantidad_Productos }}</span>
                                                                  @endif
                                                              </small>
                                                          @endforeach
                                                      </div>
                                                  @else
                                                      <span class="text-muted">-</span>
                                                  @endif
                                              </td>
                                              <td class="align-middle text-center">
                                                  <span class="text-secondary text-sm font-weight-normal">{{ $gasto->Fecha_Gasto }}</span>
                                              </td>
                                              <td class="align-middle">
                                                  <a href="{{ route('gasto.edit', $gasto->ID) }}" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Actualizar">Actualizar</a>
                                                  <!-- <form action="{{ route('gasto.destroy', $gasto->ID) }}" method="POST">
                                                      @csrf
                                                      @method('DELETE')
                                                      <button type="submit" style="border-style:none; background-color: transparent;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Borrar">Borrar</button>
                                                  </form> -->
                                              </td>
                                          </tr>
                                      @endforeach
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
