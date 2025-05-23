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
        ], [
            'Descripcion.required' => 'La descripción es obligatoria.',
            'cantidades.*.required' => 'La cantidad es obligatoria para todos los productos.',
            'cantidades.*.integer' => 'La cantidad debe ser un número entero.',
            'cantidades.*.min' => 'La cantidad debe ser mayor a 0.',
            'productos.*.required' => 'Debes seleccionar un producto.',
            'productos.*.exists' => 'El producto seleccionado no existe.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $venta = new Venta;
    
        // Actualiza los campos del producto con los datos del $request
        $venta->UsuarioID = Auth::id();
        $venta->Descripcion = $request->Descripcion;
        $venta->Fecha_Venta = now();
        // Obtén los productos y cantidades
        $productos = $request->input('productos');
        $cantidades = $request->input('cantidades');
        $venta->save();

        // Guarda los datos en la tabla de relación ProductoVenta
        foreach ($productos as $key => $productoID) {
            // Crea una nueva entrada en ProductoVenta
            ProductoVenta::create([
                'ImpuestoID' => 1,
                'Valor_Total' => 0,
                'VentaID' => $venta->ID, // Reemplaza con el ID de la venta actual
                'ProductoID' => $productoID,
                'Cantidad_Productos' => $cantidades[$key]
            ]);
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
        ], [
            'Descripcion.required' => 'La descripción es obligatoria.',
            'cantidades.*.required' => 'La cantidad es obligatoria para todos los productos.',
            'cantidades.*.integer' => 'La cantidad debe ser un número entero.',
            'cantidades.*.min' => 'La cantidad debe ser mayor a 0.',
            'productos.*.required' => 'Debes seleccionar un producto.',
            'productos.*.exists' => 'El producto seleccionado no existe.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Actualiza los campos de la venta con los datos del $request
        $venta->Descripcion = $request->Descripcion;
    
        // Guarda los cambios en la venta
        $venta->save();
    
        // Actualiza los productos y cantidades relacionados
        $productos = $request->input('productos');
        $cantidades = $request->input('cantidades');
    
        // Elimina las entradas anteriores en ProductoVenta para esta venta
        ProductoVenta::where('VentaID', $id)->delete();
    
        // Crea nuevas entradas en ProductoVenta con los datos actualizados
        foreach ($productos as $key => $productoID) {
            ProductoVenta::create([
                'ImpuestoID' => 1,
                'Valor_Total' => 0,
                'VentaID' => $id,
                'ProductoID' => $productoID,
                'Cantidad_Productos' => $cantidades[$key],
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
