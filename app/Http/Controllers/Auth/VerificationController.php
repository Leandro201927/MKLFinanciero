<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    /**
     * Muestra el formulario para ingresar el correo electrónico
     *
     * @return \Illuminate\View\View
     */
    public function showEmailForm()
    {
        return view('auth.verify-email');
    }

    /**
     * Envía el código de verificación al correo electrónico
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ], [
            'email.unique' => 'Este correo electrónico ya está registrado.'
        ]);

        // Generar código aleatorio de 6 dígitos
        $code = sprintf('%06d', mt_rand(1, 999999));
        
        // Almacenar en sesión
        Session::put('verification_email', $request->email);
        Session::put('verification_code', $code);
        Session::put('verification_expires_at', now()->addMinutes(15));
        
        // Enviar correo con el código
        try {
            Mail::raw('Tu código de verificación es: ' . $code, function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Código de verificación - MKL Financiero');
            });
            
            return redirect()->route('verify.code.form')->with('status', 'Hemos enviado un código de verificación a tu correo electrónico.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'No se pudo enviar el código de verificación. Por favor, intenta nuevamente.']);
        }
    }

    /**
     * Muestra el formulario para ingresar el código de verificación
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showCodeForm()
    {
        if (!Session::has('verification_email') || !Session::has('verification_code')) {
            return redirect()->route('verify.email.form')->withErrors(['email' => 'Primero debes solicitar un código de verificación.']);
        }

        $email = Session::get('verification_email');
        return view('auth.verify-code', compact('email'));
    }

    /**
     * Verifica el código ingresado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        if (!Session::has('verification_email') || !Session::has('verification_code')) {
            return redirect()->route('verify.email.form')
                ->withErrors(['email' => 'La sesión ha expirado. Por favor, solicita un nuevo código.']);
        }

        $expires_at = Session::get('verification_expires_at');
        if (now()->isAfter($expires_at)) {
            Session::forget(['verification_email', 'verification_code', 'verification_expires_at']);
            return redirect()->route('verify.email.form')
                ->withErrors(['email' => 'El código ha expirado. Por favor, solicita un nuevo código.']);
        }

        if ($request->code != Session::get('verification_code')) {
            return back()->withErrors(['code' => 'El código ingresado es incorrecto.']);
        }

        // Código verificado, redirigir al formulario de registro
        Session::put('email_verified', true);
        return redirect()->route('sign-up')->with('status', 'Correo verificado correctamente. Por favor, completa tu registro.');
    }
} 