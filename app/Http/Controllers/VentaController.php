<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;

use Illuminate\Support\Facades\Auth;

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
        $ventas = Venta::all(); // -> SELECT * FROM 'Producto';
        return view('ventas.read', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ventas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $venta = new Venta;
    
        // Actualiza los campos del producto con los datos del $request
        $venta->UsuarioID = Auth::id();
        $venta->Descripcion = $request->Descripcion;
    
        $venta->save();
    
        return redirect()->route('venta'); // -> route no apunta a una vista, sino al controlador
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
        // return view('Usuario.update', compact('usuario'));
        $venta = Venta::find($id); // -> ::find($id) -> SELECT * FROM 'Producto' WHERE id = $id;
        return view('ventas.update', compact('venta'));
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
        $venta = Venta::find($id);
    
        // Actualiza los campos del producto con los datos del $request
        $venta->Descripcion = $request->Descripcion;
    
        $venta->save();
    
        return redirect()->route('venta'); // -> route no apunta a una vista, sino al controlador
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $venta = Venta::find($id); // -> ::find($id) -> SELECT * FROM 'producto' WHERE id = $id;
        $venta->delete(); // -> DELETE FROM 'producto' WHERE ID = $id;

        return redirect()->route('venta');
    }
}
