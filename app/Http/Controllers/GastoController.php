<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Gasto;
use App\Models\Producto;
use App\Models\ProductoGasto;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GastoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gastos = Gasto::where('UsuarioID', Auth::id())->orderBy('Fecha_Gasto', 'desc')->get();
        return view('gastos.read', compact('gastos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::where('UsuarioID', Auth::id())->get();
        return view('gastos.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
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
        
        $gasto = new Gasto;
    
        // Actualiza los campos del gasto con los datos del $request
        $gasto->UsuarioID = Auth::id();
        $gasto->Descripcion = $request->Descripcion;
        $gasto->Fecha_Gasto = now();
        $gasto->save();

        // Obtén los productos, cantidades y valores unitarios
        $productos = $request->input('productos');
        $cantidades = $request->input('cantidades');
        $valores_unitarios = $request->input('valores_unitarios');

        // Guarda los datos en la tabla de relación ProductoGasto y actualiza el stock
        foreach ($productos as $key => $productoID) {
            $producto = Producto::find($productoID);
            $cantidad = $cantidades[$key];
            $valor_unitario = $valores_unitarios[$key];
            $valor_total = $cantidad * $valor_unitario;

            // Crear la entrada en ProductoGasto
            ProductoGasto::create([
                'Valor_Unitario' => $valor_unitario,
                'Valor_Total' => $valor_total,
                'MovimientoID' => $gasto->ID,
                'ProductoID' => $productoID,
                'Cantidad_Productos' => $cantidad
            ]);

            // Actualizar el stock del producto
            $producto->Cantidad += $cantidad;
            $producto->save();
        }
        
        return redirect()->route('gasto')->with('success', 'Gasto registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Verificar que el gasto pertenece al usuario actual
        $gasto = Gasto::where('ID', $id)
                      ->where('UsuarioID', Auth::id())
                      ->first();
                      
        if (!$gasto) {
            return redirect()->route('gasto')->with('error', 'Gasto no encontrado.');
        }
        
        $productosCantidades = $gasto->productos; // Asume que tienes una relación 'productos' en el modelo gasto
        $productosDisponibles = Producto::where('UsuarioID', Auth::id())->get();
        return view('gastos.update', compact('gasto', 'productosCantidades', 'productosDisponibles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Verificar que el gasto pertenece al usuario actual
        $gasto = Gasto::where('ID', $id)
                      ->where('UsuarioID', Auth::id())
                      ->first();
                      
        if (!$gasto) {
            return redirect()->route('gasto')->with('error', 'Gasto no encontrado.');
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
        $productosActuales = ProductoGasto::where('MovimientoID', $id)->get();
        
        // Restaurar las cantidades originales
        foreach ($productosActuales as $productoActual) {
            $producto = Producto::find($productoActual->ProductoID);
            $producto->Cantidad -= $productoActual->Cantidad_Productos;
            $producto->save();
        }

        $gasto->Descripcion = $request->Descripcion;
        $gasto->save(); 

        $productos = $request->input('productos');
        $cantidades = $request->input('cantidades');

        ProductoGasto::where('MovimientoID', $id)->delete();

        foreach ($productos as $key => $productoID) {
            $producto = Producto::find($productoID);
            $cantidad = $cantidades[$key];
            $valor_unitario = $request->valores_unitarios[$key];
            $valor_total = $valor_unitario * $cantidad;

            ProductoGasto::create([
                'MovimientoID' => $id,
                'ProductoID' => $productoID,
                'Cantidad_Productos' => $cantidad,
                'Valor_Unitario' => $valor_unitario,
                'Valor_Total' => $valor_total
            ]);

            // Actualizar el stock del producto
            $producto->Cantidad += $cantidad;
            $producto->save();
        }

        return redirect()->route('gasto')->with('success', 'Gasto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Verificar que el gasto pertenece al usuario actual
        $gasto = Gasto::where('ID', $id)
                      ->where('UsuarioID', Auth::id())
                      ->first();
                      
        if (!$gasto) {
            return redirect()->route('gasto')->with('error', 'Gasto no encontrado.');
        }
        
        $gasto->delete();

        // Delete all ProductoGasto entries for the specified $id
        ProductoGasto::where('MovimientoID', $id)->delete();

        return redirect()->route('gasto')->with('success', 'Gasto eliminado correctamente.');
    }
}
