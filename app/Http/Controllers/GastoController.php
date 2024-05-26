<?php

namespace App\Http\Controllers;
use App\Models\Gasto;
use Illuminate\Support\Facades\Auth;

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
        $gastos = Gasto::all(); // -> SELECT * FROM 'Producto';
        return view('gastos.read', compact('gastos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gastos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $gasto = new Gasto;

        $gasto->UsuarioID = Auth::id();
        $gasto->Descripcion = $request->Descripcion;

        $gasto->save();

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
        return view('gastos.update', compact('gasto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gasto = Gasto::find($id);

        $gasto->Descripcion = $request->Descripcion;

        $gasto->save();

        return redirect()->route('gasto');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gasto = Gasto::find($id);
        $gasto->delete();

        return redirect()->route('gasto');
    }
}
