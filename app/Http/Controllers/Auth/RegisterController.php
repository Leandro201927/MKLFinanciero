<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Verificar si el correo ya ha sido verificado
        if (!Session::has('email_verified') || !Session::has('verification_email')) {
            return redirect()->route('verify.email.form');
        }
        
        $email = Session::get('verification_email');
        return view('auth.signup', compact('email'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Verificar si el correo ya ha sido verificado
        if (!Session::has('email_verified') || !Session::has('verification_email')) {
            return redirect()->route('verify.email.form');
        }
        
        // Verificar que el correo usado sea el mismo que se verificó
        if ($request->email != Session::get('verification_email')) {
            return back()->withErrors(['email' => 'El correo electrónico debe ser el mismo que se verificó.']);
        }

        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:7|max:255',
            'terms' => 'accepted',
        ], [
            'name.required' => 'El nombre es requerido',
            'email.required' => 'El correo electrónico es requerido',
            'password.required' => 'La contraseña es requerida',
            'terms.accepted' => 'Debes aceptar los términos y condiciones'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Limpiar las variables de sesión de verificación
        Session::forget(['email_verified', 'verification_email', 'verification_code', 'verification_expires_at']);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
