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
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                
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
                                <div class="input-group w-sm-25 ms-auto">
                                    <span class="input-group-text text-body">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z">
                                            </path>
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Buscar">
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                              <table class="table align-items-center mb-0">
                                  <thead class="bg-gray-100">
                                      <tr>
                                          <th class="text-secondary text-xs font-weight-semibold opacity-7">ID</th>
                                          <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">UsuarioID</th>
                                          <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Nombre</th>
                                          <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Precio</th>
                                          <th class="text-secondary text-xs font-weight-     opacity-7">Acciones</th>
                                      </tr>
                                  </thead>
                                  <tbody>
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
                                                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ $producto->UsuarioID }}</p>
                                              </td>
                                              <td class="align-middle text-center text-sm">
                                                  <span class="text-secondary text-sm font-weight-normal">{{ $producto->Nombre }}</span>
                                              </td>
                                              <td class="align-middle text-center">
                                                  <span class="text-secondary text-sm font-weight-normal">{{ $producto->Precio }}</span>
                                              </td>
                                              <td class="align-middle">
                                                  <a href="{{ route('producto.edit', $producto->ID) }}" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Actualizar">Actualizar</a>
                                                  <form action="{{ route('producto.destroy', $producto->ID) }}" method="POST">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>
