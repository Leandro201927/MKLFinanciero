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
        
        $gasto = new Gasto;

        $gasto->UsuarioID = Auth::id();
        $gasto->Fecha_Gasto = now();
        $gasto->Descripcion = $request->Descripcion;

        $productos = $request->input('productos');
        $cantidades = $request->input('cantidades');
        $gasto->save();

        // Guarda los datos en la tabla de relación ProductoGasto
        foreach ($productos as $key => $productoID) {
            // Crea una nueva entrada en ProductoGasto
            ProductoGasto::create([
                'ImpuestoID' => 1,
                'Valor_Total' => 0,
                'MovimientoID' => $gasto->ID, // Reemplaza con el ID del gasto actual
                'ProductoID' => $productoID,
                'Cantidad_Productos' => $cantidades[$key]
            ]);
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

        $gasto->Descripcion = $request->Descripcion;

        $gasto->save(); 

        $productos = $request->input('productos');
        $cantidades = $request->input('cantidades');

        ProductoGasto::where('MovimientoID', $id)->delete();

        foreach ($productos as $key => $productoID) {
            ProductoGasto::create([
                'ImpuestoID' => 1,
                'Valor_Total' => 0,
                'MovimientoID' => $id,
                'ProductoID' => $productoID,
                'Cantidad_Productos' => $cantidades[$key]
            ]);
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
