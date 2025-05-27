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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = Venta::where('UsuarioID', Auth::id())->orderBy('Fecha_Venta', 'desc')->get(); // -> SELECT * FROM 'Producto';
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
        $productos = Producto::where('UsuarioID', Auth::id())->get();
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
            'Descripcion' => 'required|string|max:255',
            'cantidades.*' => 'required|integer|min:1',
            'productos.*' => 'required|exists:producto,ID',
            'valores_unitarios.*' => 'required|numeric|min:0',
        ], [
            'Descripcion.required' => 'La descripción es obligatoria.',
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

        // Verificar stock disponible para cada producto
        $productos = $request->input('productos');
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
    
        // Actualiza los campos del producto con los datos del $request
        $venta->UsuarioID = Auth::id();
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
        $productosDisponibles = Producto::where('UsuarioID', Auth::id())->get();
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
            'Descripcion' => 'required|string|max:255',
            'cantidades.*' => 'required|integer|min:1',
            'productos.*' => 'required|exists:producto,ID',
            'valores_unitarios.*' => 'required|numeric|min:0',
        ], [
            'Descripcion.required' => 'La descripción es obligatoria.',
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

        // Obtener los productos y cantidades actuales
        $productosActuales = ProductoVenta::where('VentaID', $id)->get();
        
        // Restaurar las cantidades originales
        foreach ($productosActuales as $productoActual) {
            $producto = Producto::find($productoActual->ProductoID);
            $producto->Cantidad += $productoActual->Cantidad_Productos;
            $producto->save();
        }
    
        // Actualiza los campos de la venta con los datos del $request
        $venta->Descripcion = $request->Descripcion;
        $venta->save();
    
        // Actualiza los productos y cantidades relacionados
        $productos = $request->input('productos');
        $cantidades = $request->input('cantidades');
    
        // Elimina las entradas anteriores en ProductoVenta para esta venta
        ProductoVenta::where('VentaID', $id)->delete();
    
        // Crea nuevas entradas en ProductoVenta con los datos actualizados
        foreach ($productos as $key => $productoID) {
            $producto = Producto::find($productoID);
            $cantidad = $cantidades[$key];
            $valor_unitario = $request->valores_unitarios[$key];
            $valor_total = $valor_unitario * $cantidad;

            // Verificar stock disponible
            if ($producto->Cantidad < $cantidad) {
                // Restaurar las cantidades originales antes de redirigir
                foreach ($productosActuales as $productoActual) {
                    $producto = Producto::find($productoActual->ProductoID);
                    $producto->Cantidad -= $productoActual->Cantidad_Productos;
                    $producto->save();
                }
                return redirect()->back()
                    ->withErrors(['stock' => "No hay suficiente stock disponible para el producto {$producto->Nombre}. Stock actual: {$producto->Cantidad}"])
                    ->withInput();
            }

            ProductoVenta::create([
                'VentaID' => $id,
                'ProductoID' => $productoID,
                'Cantidad_Productos' => $cantidad,
                'Valor_Unitario' => $valor_unitario,
                'Valor_Total' => $valor_total
            ]);

            // Actualizar el stock del producto
            $producto->Cantidad -= $cantidad;
            $producto->save();
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
