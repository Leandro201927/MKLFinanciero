<?php

namespace App\Http\Controllers;

use pdf;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ProductoVenta;
use App\Models\ProductoGasto;

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
        $productos = Producto::where('UsuarioID', Auth::id())->get();
        
        // Para cada producto, verificar si tiene ventas o gastos asociados
        foreach ($productos as $producto) {
            $tieneVentas = ProductoVenta::where('ProductoID', $producto->ID)->exists();
            $tieneGastos = ProductoGasto::where('ProductoID', $producto->ID)->exists();
            $producto->puedeEliminar = !($tieneVentas || $tieneGastos);
        }
        
        return view('productos.read', compact('productos'));
    }
    
    public function imprimirProducto(Request $request)
    {
        // $productos = Producto::orderBy('id', 'ASC')->get();
        $productos = Producto::where('UsuarioID', Auth::id())->orderBy('id', 'ASC')->get();
        $pdf = \PDF::loadView('productos.read', ['productos' => $productos, 'pdf' => true]);
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
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar que el nombre sea único para este usuario
        $validator = Validator::make($request->all(), [
            'Nombre' => [
                'required',
                Rule::unique('producto')->where(function ($query) {
                    return $query->where('UsuarioID', Auth::id());
                }),
            ],
            'Cantidad' => 'required|integer|min:0',
        ], [
            'Nombre.required' => 'El nombre del producto es obligatorio.',
            'Nombre.unique' => 'Ya tienes un producto con este nombre.',
            'Cantidad.required' => 'La cantidad es obligatoria.',
            'Cantidad.integer' => 'La cantidad debe ser un número entero.',
            'Cantidad.min' => 'La cantidad no puede ser negativa.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $producto = new Producto;
    
        // Actualiza los campos del producto con los datos del $request
        $producto->UsuarioID = Auth::id();
        $producto->Nombre = $request->Nombre;
        $producto->Cantidad = $request->Cantidad;
    
        $producto->save();
    
        return redirect()->route('producto')->with('success', 'Producto creado correctamente.');
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
        // Verificar que el producto pertenece al usuario actual
        $producto = Producto::where('ID', $id)
                            ->where('UsuarioID', Auth::id())
                            ->first();
        
        if (!$producto) {
            return redirect()->route('producto')->with('error', 'Producto no encontrado.');
        }
        
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
        // Verificar que el producto pertenece al usuario actual
        $producto = Producto::where('ID', $id)
                            ->where('UsuarioID', Auth::id())
                            ->first();
        
        if (!$producto) {
            return redirect()->route('producto')->with('error', 'Producto no encontrado.');
        }
        
        // Validar que el nombre sea único para este usuario (excepto para este mismo producto)
        $validator = Validator::make($request->all(), [
            'Nombre' => [
                'required',
                Rule::unique('producto')->where(function ($query) use ($id) {
                    return $query->where('UsuarioID', Auth::id())
                                ->where('ID', '!=', $id);
                }),
            ],
        ], [
            'Nombre.required' => 'El nombre del producto es obligatorio.',
            'Nombre.unique' => 'Ya tienes otro producto con este nombre.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Actualiza los campos del producto con los datos del $request
        $producto->Nombre = $request->Nombre;
    
        $producto->save();
    
        return redirect()->route('producto')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        // Verificar que el producto pertenece al usuario actual
        $producto = Producto::where('ID', $id)
                      ->where('UsuarioID', Auth::id())
                      ->first();
                      
        if (!$producto) {
            return redirect()->route('producto')->with('error', 'Producto no encontrado.');
        }

        // Verificar si el producto tiene ventas o gastos asociados
        $tieneVentas = ProductoVenta::where('ProductoID', $id)->exists();
        $tieneGastos = ProductoGasto::where('ProductoID', $id)->exists();

        if ($tieneVentas || $tieneGastos) {
            return redirect()->route('producto')->with('error', 'No se puede eliminar el producto porque tiene ventas o gastos asociados.');
        }
        
        $producto->delete();
        return redirect()->route('producto')->with('success', 'Producto eliminado correctamente.');
    }
}
