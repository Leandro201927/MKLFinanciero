<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\ProductoVenta;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function exportarExcel(Request $request)
    {
        $ventas = Venta::where('UsuarioID', Auth::id())
                        ->with('productos')
                        ->orderBy('Fecha_Venta', 'desc')
                        ->get();
        
        $data = [];
        $data[] = ['Código', 'Descripción', 'Fecha', 'Productos', 'Total']; // Headers
        
        foreach ($ventas as $venta) {
            $productos = $venta->productos->pluck('Nombre')->implode(', ');
            $total = $venta->productos->sum('pivot.Valor_Total');
            
            $data[] = [
                $venta->Codigo,
                $venta->Descripcion,
                $venta->Fecha_Venta,
                $productos,
                '$' . number_format($total, 2, ',', '.')
            ];
        }
        
        return $this->generateCSV($data, 'ventas_' . date('Y-m-d_H-i-s') . '.csv');
    }

    private function generateCSV($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Venta::where('UsuarioID', Auth::id());
        
        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->where('Fecha_Venta', '>=', $request->fecha_desde);
        }
        
        if ($request->filled('fecha_hasta')) {
            $query->where('Fecha_Venta', '<=', $request->fecha_hasta);
        }
        
        // Filtro por búsqueda en descripción o código
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('Descripcion', 'like', '%' . $request->buscar . '%')
                  ->orWhere('Codigo', 'like', '%' . $request->buscar . '%');
            });
        }
        
        $ventas = $query->with('productos')->orderBy('Fecha_Venta', 'desc')->get(); // -> SELECT * FROM 'Producto';
        return view('ventas.read', compact('ventas'));
    }

    public function imprimirVenta(Request $request)
    {
        $ventas = Venta::orderBy('id', 'ASC')->get();
        $pdf = \PDF::loadView('ventas.read', ['ventas' => $ventas, 'pdf' => true]);
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'isJavascriptEnabled' => false, 'isCssFloatEnabled' => false]);
        $pdf->setPaper('carta', 'A4');
        return $pdf->stream();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Solo productos de tipo 'producto' para ventas
        $productos = Producto::where('UsuarioID', Auth::id())
                            ->where('Tipo', 'producto')
                            ->get();
        return view('ventas.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'Descripcion' => 'nullable|string|max:255',
            'cantidades.*' => 'required|integer|min:1',
            'productos.*' => 'required|exists:producto,ID',
            'valores_unitarios.*' => 'required|numeric|min:0',
        ], [
            'cantidades.*.required' => 'La cantidad es obligatoria para todos los productos.',
            'cantidades.*.integer' => 'La cantidad debe ser un número entero.',
            'cantidades.*.min' => 'La cantidad debe ser mayor a 0.',
            'productos.*.required' => 'Debes seleccionar un producto.',
            'productos.*.exists' => 'El producto seleccionado no existe.',
            'valores_unitarios.*.required' => 'El valor unitario es obligatorio.',
            'valores_unitarios.*.numeric' => 'El valor unitario debe ser un número.',
            'valores_unitarios.*.min' => 'El valor unitario no puede ser negativo.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar que haya al menos un producto
        $productos = $request->input('productos', []);
        if (empty($productos)) {
            return redirect()->back()
                ->withErrors(['productos' => 'Debe agregar al menos un producto a la venta.'])
                ->withInput();
        }

        // Verificar productos duplicados
        if (count($productos) !== count(array_unique($productos))) {
            return redirect()->back()
                ->withErrors(['productos' => 'No se pueden agregar productos duplicados.'])
                ->withInput();
        }

        // Verificar stock disponible para cada producto
        $cantidades = $request->input('cantidades');
        $valores_unitarios = $request->input('valores_unitarios');

        foreach ($productos as $key => $productoID) {
            $producto = Producto::find($productoID);
            if ($producto->Cantidad < $cantidades[$key]) {
                return redirect()->back()
                    ->withErrors(['stock' => "No hay suficiente stock disponible para el producto {$producto->Nombre}. Stock actual: {$producto->Cantidad}"])
                    ->withInput();
            }
        }
        
        $venta = new Venta;
    
        // Generar código automático
        $ultimaVenta = Venta::where('UsuarioID', Auth::id())->orderBy('ID', 'desc')->first();
        $numero = $ultimaVenta ? intval(substr($ultimaVenta->Codigo, 6)) + 1 : 1;
        $codigo = 'VENTA#' . str_pad($numero, 3, '0', STR_PAD_LEFT);
        
        // Actualiza los campos del producto con los datos del $request
        $venta->UsuarioID = Auth::id();
        $venta->Codigo = $codigo;
        $venta->Descripcion = $request->Descripcion;
        $venta->Fecha_Venta = now();
        $venta->save();

        // Guarda los datos en la tabla de relación ProductoVenta y actualiza el stock
        foreach ($productos as $key => $productoID) {
            $producto = Producto::find($productoID);
            $cantidad = $cantidades[$key];
            $valor_unitario = $valores_unitarios[$key];
            $valor_total = $cantidad * $valor_unitario;

            // Crear la entrada en ProductoVenta
            ProductoVenta::create([
                'VentaID' => $venta->ID,
                'ProductoID' => $productoID,
                'Cantidad_Productos' => $cantidad,
                'Valor_Unitario' => $valor_unitario,
                'Valor_Total' => $valor_total
            ]);

            // Actualizar el stock del producto
            $producto->Cantidad -= $cantidad;
            $producto->save();
        }
        
        return redirect()->route('venta')->with('success', 'Venta registrada correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Verificar que la venta pertenece al usuario actual
        $venta = Venta::where('ID', $id)
                      ->where('UsuarioID', Auth::id())
                      ->first();
                      
        if (!$venta) {
            return redirect()->route('venta')->with('error', 'Venta no encontrada.');
        }
        
        $productosCantidades = $venta->productos; // Asume que tienes una relación 'productos' en el modelo Venta
        $productosDisponibles = Producto::where('UsuarioID', Auth::id())
                                       ->where('Tipo', 'producto')
                                       ->get();
        return view('ventas.update', compact('venta', 'productosCantidades', 'productosDisponibles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Verificar que la venta pertenece al usuario actual
        $venta = Venta::where('ID', $id)
                      ->where('UsuarioID', Auth::id())
                      ->first();
                      
        if (!$venta) {
            return redirect()->route('venta')->with('error', 'Venta no encontrada.');
        }
        
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'Descripcion' => 'nullable|string|max:255',
            'cantidades.*' => 'required|integer|min:1',
            'productos.*' => 'required|exists:producto,ID',
            'valores_unitarios.*' => 'required|numeric|min:0',
        ], [
            'cantidades.*.required' => 'La cantidad es obligatoria para todos los productos.',
            'cantidades.*.integer' => 'La cantidad debe ser un número entero.',
            'cantidades.*.min' => 'La cantidad debe ser mayor a 0.',
            'productos.*.required' => 'Debes seleccionar un producto.',
            'productos.*.exists' => 'El producto seleccionado no existe.',
            'valores_unitarios.*.required' => 'El valor unitario es obligatorio.',
            'valores_unitarios.*.numeric' => 'El valor unitario debe ser un número.',
            'valores_unitarios.*.min' => 'El valor unitario no puede ser negativo.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar que haya al menos un producto
        $productos = $request->input('productos', []);
        if (empty($productos)) {
            return redirect()->back()
                ->withErrors(['productos' => 'Debe agregar al menos un producto a la venta.'])
                ->withInput();
        }

        // Verificar productos duplicados
        if (count($productos) !== count(array_unique($productos))) {
            return redirect()->back()
                ->withErrors(['productos' => 'No se pueden agregar productos duplicados.'])
                ->withInput();
        }

        // Obtener los productos y cantidades actuales
        $productosActuales = ProductoVenta::where('VentaID', $id)->get();
        $cantidadesActuales = [];
        
        // Guardar las cantidades actuales por producto
        foreach ($productosActuales as $productoActual) {
            $cantidadesActuales[$productoActual->ProductoID] = $productoActual->Cantidad_Productos;
        }
    
        // Actualiza los campos de la venta con los datos del $request
        $venta->Descripcion = $request->Descripcion;
        $venta->save();
    
        // Actualiza los productos y cantidades relacionados
        $cantidades = $request->input('cantidades', []);
    
        // Primero, restaurar el stock de todos los productos actuales
        foreach ($productosActuales as $productoActual) {
            $producto = Producto::find($productoActual->ProductoID);
            $producto->Cantidad += $productoActual->Cantidad_Productos;
            $producto->save();
        }

        // Eliminar todas las entradas anteriores en ProductoVenta
        ProductoVenta::where('VentaID', $id)->delete();
    
        // Crear nuevas entradas en ProductoVenta con los datos actualizados
        foreach ($productos as $key => $productoID) {
            $producto = Producto::find($productoID);
            $cantidadNueva = $cantidades[$key];
            $valor_unitario = $request->valores_unitarios[$key];
            $valor_total = $valor_unitario * $cantidadNueva;

            // Verificar si hay suficiente stock para la nueva cantidad
            if ($producto->Cantidad < $cantidadNueva) {
                // Restaurar las cantidades originales antes de redirigir
                foreach ($productosActuales as $productoActual) {
                    $producto = Producto::find($productoActual->ProductoID);
                    $producto->Cantidad -= $productoActual->Cantidad_Productos;
                    $producto->save();
                }
                return redirect()->back()
                    ->withErrors(['stock' => "No hay suficiente stock disponible para el producto {$producto->Nombre}. Stock actual: {$producto->Cantidad}, Cantidad requerida: {$cantidadNueva}"])
                    ->withInput();
            }

            // Descontar la nueva cantidad del stock
            $producto->Cantidad -= $cantidadNueva;
            $producto->save();

            ProductoVenta::create([
                'VentaID' => $id,
                'ProductoID' => $productoID,
                'Cantidad_Productos' => $cantidadNueva,
                'Valor_Unitario' => $valor_unitario,
                'Valor_Total' => $valor_total
            ]);
        }
    
        return redirect()->route('venta')->with('success', 'Venta actualizada correctamente.');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Verificar que la venta pertenece al usuario actual
        $venta = Venta::where('ID', $id)
                      ->where('UsuarioID', Auth::id())
                      ->first();
                      
        if (!$venta) {
            return redirect()->route('venta')->with('error', 'Venta no encontrada.');
        }
        
        $venta->delete();

        // Delete all ProductoVenta entries for the specified $id
        ProductoVenta::where('VentaID', $id)->delete();

        return redirect()->route('venta')->with('success', 'Venta eliminada correctamente.');
    }
}
