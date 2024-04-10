<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
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
        $productos = Producto::all(); // -> SELECT * FROM 'Producto';
        return view('productos.read', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Producto.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $producto = new Producto;
    
        // Actualiza los campos del producto con los datos del $request
        $producto->UsuarioID = $request->UsuarioID;
        $producto->Precio = $request->Precio;
        $producto->Nombre = $request->Nombre;
    
        $producto->save();
    
        return redirect()->route('producto.index'); // -> route no apunta a una vista, sino al controlador
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
        $producto = Producto::find($id); // -> ::find($id) -> SELECT * FROM 'Producto' WHERE id = $id;
        return view('productos.update', compact('producto'));
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
        $producto = Producto::find($id);
    
        // Actualiza los campos del producto con los datos del $request
        $producto->Nombre = $request->Nombre;
        $producto->Precio = $request->Precio;
    
        $producto->save();
    
        return redirect()->route('producto.index'); // -> route no apunta a una vista, sino al controlador
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::find($id); // -> ::find($id) -> SELECT * FROM 'producto' WHERE id = $id;
        $producto->delete(); // -> DELETE FROM 'producto' WHERE ID = $id;

        return redirect()->route('producto.index');
    }
}
