<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsuarioController extends Controller
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
        $usuarios = User::all(); // -> SELECT * FROM 'Usuario';
        return view('Usuario.read', compact('usuarios'));
        // $usuarios = Usuario::all(); // -> SELECT * FROM 'Usuario';
        // // dd($usuarios);
        // return view('Usuario.read', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Usuario.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Crea un nuevo usuario con los datos del request
        $usuario = new User;
    
        $usuario->Nombre = $request->Nombre;
        $usuario->Correo = $request->Correo;
        $usuario->Contraseña = bcrypt($request->Contraseña); // Considera encriptar la contraseña
        $usuario->Rol = $request->Rol;
        $usuario->Cedula = $request->Cedula;
        $usuario->Estado = $request->Estado;
        $usuario->Fecha_Registro = date('Y-m-d');
    
        // Guarda el nuevo usuario en la base de datos
        $usuario->save();
    
        // Redirige al usuario a la página de lista de usuarios
        return redirect()->route('usuario.index');
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
        $usuario = User::find($id); // -> ::find($id) -> SELECT * FROM 'Usuario' WHERE id = $id;
        return view('Usuario.update', compact('usuario'));
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
        $usuario = User::find($id);
    
        // Actualiza los campos del usuario con los datos del $request
        $usuario->Nombre = $request->Nombre;
        $usuario->Correo = $request->Correo;
        $usuario->Contraseña = $request->Contraseña; // Considera encriptar la contraseña
        $usuario->Rol = $request->Rol;
        $usuario->Cedula = $request->Cedula;
        $usuario->Estado = $request->Estado;
        // No necesitas actualizar 'Fecha_Registro' ya que es la fecha cuando el usuario fue creado
    
        $usuario->save();
    
        return redirect()->route('usuario.index'); // -> route no apunta a una vista, sino al controlador
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::find($id); // -> ::find($id) -> SELECT * FROM 'Usuario' WHERE id = $id;
        $usuario->delete(); // -> DELETE FROM 'Usuario' WHERE ID = $id;

        return redirect()->route('usuario.index');
    }
}
