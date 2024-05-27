<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Gasto;
use App\Models\Producto;
use App\Models\ProductoGasto;

use Illuminate\Http\Request;

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
        $gastos = Gasto::where('UsuarioID', Auth::id())->get(); // -> SELECT * FROM 'Producto';
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

        return redirect()->route('gasto');
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
        $gasto = Gasto::find($id);
        $productosCantidades = $gasto->productos; // Asume que tienes una relación 'productos' en el modelo gasto
        $productosDisponibles = Producto::all();
        return view('gastos.update', compact('gasto', 'productosCantidades', 'productosDisponibles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gasto = Gasto::find($id);

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

        return redirect()->route('gasto');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gasto = Gasto::find($id);
        $gasto->delete();

        // Delete all ProductoGasto entries for the specified $id
        ProductoGasto::where('MovimientoID', $id)->delete();

        return redirect()->route('gasto');
    }
}
