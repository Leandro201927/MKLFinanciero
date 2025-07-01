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
    public function index(Request $request)
    {
        $query = Producto::where('UsuarioID', Auth::id());
        
        // Filtro por tipo
        if ($request->filled('tipo') && $request->tipo !== 'todos') {
            $query->where('Tipo', $request->tipo);
        }
        
        // Filtro por búsqueda de nombre
        if ($request->filled('buscar')) {
            $query->where('Nombre', 'like', '%' . $request->buscar . '%');
        }
        
        // Filtro por clasificación
        if ($request->filled('clasificacion')) {
            $query->whereJsonContains('Clasificacion', $request->clasificacion);
        }
        
        $productos = $query->get();
        
        // Para cada producto, verificar si tiene ventas o gastos asociados
        foreach ($productos as $producto) {
            $tieneVentas = ProductoVenta::where('ProductoID', $producto->ID)->exists();
            $tieneGastos = ProductoGasto::where('ProductoID', $producto->ID)->exists();
            $producto->puedeEliminar = !($tieneVentas || $tieneGastos);
        }
        
        // Obtener tipos únicos para el filtro
        $tipos = ['todos' => 'Todos', 'producto' => 'Producto', 'gasto' => 'Gasto', 'servicio' => 'Servicio'];
        
        // Obtener clasificaciones únicas para el filtro
        $clasificaciones = Producto::where('UsuarioID', Auth::id())
                                   ->whereNotNull('Clasificacion')
                                   ->get()
                                   ->pluck('Clasificacion')
                                   ->flatten()
                                   ->unique()
                                   ->sort();
        
        return view('productos.read', compact('productos', 'tipos', 'clasificaciones'));
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

    public function exportarExcel(Request $request)
    {
        $productos = Producto::where('UsuarioID', Auth::id())->orderBy('id', 'ASC')->get();
        
        $data = [];
        $data[] = ['ID', 'Nombre', 'Tipo', 'Cantidad', 'Clasificación']; // Headers
        
        foreach ($productos as $producto) {
            $clasificacion = '';
            if ($producto->Clasificacion && is_array($producto->Clasificacion)) {
                $clasificacion = implode(', ', array_map(function($key, $value) {
                    return "$key: $value";
                }, array_keys($producto->Clasificacion), $producto->Clasificacion));
            }
            
            $data[] = [
                $producto->ID,
                $producto->Nombre,
                ucfirst($producto->Tipo ?? 'N/A'),
                $producto->Tipo === 'producto' ? $producto->Cantidad : 'N/A',
                $clasificacion ?: '-'
            ];
        }
        
        return $this->generateCSV($data, 'productos_' . date('Y-m-d_H-i-s') . '.csv');
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
            'Cantidad' => 'nullable|integer|min:0',
            'Tipo' => 'required|in:producto,gasto,servicio',
            'Clasificacion' => 'nullable|array',
            'Descripcion' => 'nullable|string|max:1000',
        ], [
            'Nombre.required' => 'El nombre del producto es obligatorio.',
            'Nombre.unique' => 'Ya tienes un producto con este nombre.',
            'Cantidad.integer' => 'La cantidad debe ser un número entero.',
            'Cantidad.min' => 'La cantidad no puede ser negativa.',
            'Tipo.required' => 'El tipo es obligatorio.',
            'Tipo.in' => 'El tipo debe ser: producto, gasto o servicio.',
            'Descripcion.string' => 'La descripción debe ser texto.',
            'Descripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
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
        $producto->Tipo = $request->Tipo;
        
        // Solo asignar cantidad si el tipo es 'producto'
        if ($request->Tipo === 'producto') {
            $producto->Cantidad = $request->Cantidad ?? 0;
        } else {
            $producto->Cantidad = 0; // Para servicios y gastos no hay cantidad inicial
        }
        
        // Procesar clasificación solo si el tipo es 'producto'
        if ($request->Tipo === 'producto') {
            $clasificacion = [];
            if ($request->has('clasificacion_keys') && $request->has('clasificacion_values')) {
                $keys = array_filter($request->clasificacion_keys);
                $values = array_filter($request->clasificacion_values);
                
                foreach ($keys as $index => $key) {
                    if (isset($values[$index]) && !empty($key) && !empty($values[$index])) {
                        $clasificacion[$key] = $values[$index];
                    }
                }
            }
            $producto->Clasificacion = !empty($clasificacion) ? $clasificacion : null;
            $producto->Descripcion = null; // Productos no tienen descripción
        } else {
            // Para gastos y servicios, no hay clasificaciones pero sí descripción
            $producto->Clasificacion = null;
            $producto->Descripcion = $request->Descripcion;
        }
    
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
            'Cantidad' => 'nullable|integer|min:0',
            'Tipo' => 'required|in:producto,gasto,servicio',
            'Clasificacion' => 'nullable|array',
            'Descripcion' => 'nullable|string|max:1000',
        ], [
            'Nombre.required' => 'El nombre del producto es obligatorio.',
            'Nombre.unique' => 'Ya tienes otro producto con este nombre.',
            'Cantidad.integer' => 'La cantidad debe ser un número entero.',
            'Cantidad.min' => 'La cantidad no puede ser negativa.',
            'Tipo.required' => 'El tipo es obligatorio.',
            'Tipo.in' => 'El tipo debe ser: producto, gasto o servicio.',
            'Descripcion.string' => 'La descripción debe ser texto.',
            'Descripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Actualiza los campos del producto con los datos del $request
        $producto->Nombre = $request->Nombre;
        $producto->Tipo = $request->Tipo;
        
        // Solo actualizar cantidad si el tipo es 'producto'
        if ($request->Tipo === 'producto') {
            $producto->Cantidad = $request->Cantidad ?? $producto->Cantidad;
        } else {
            $producto->Cantidad = 0; // Para servicios y gastos no hay cantidad
        }
        
        // Procesar clasificación solo si el tipo es 'producto'
        if ($request->Tipo === 'producto') {
            $clasificacion = [];
            if ($request->has('clasificacion_keys') && $request->has('clasificacion_values')) {
                $keys = array_filter($request->clasificacion_keys);
                $values = array_filter($request->clasificacion_values);
                
                foreach ($keys as $index => $key) {
                    if (isset($values[$index]) && !empty($key) && !empty($values[$index])) {
                        $clasificacion[$key] = $values[$index];
                    }
                }
            }
            $producto->Clasificacion = !empty($clasificacion) ? $clasificacion : null;
            $producto->Descripcion = null; // Productos no tienen descripción
        } else {
            // Para gastos y servicios, no hay clasificaciones pero sí descripción
            $producto->Clasificacion = null;
            $producto->Descripcion = $request->Descripcion;
        }
    
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
