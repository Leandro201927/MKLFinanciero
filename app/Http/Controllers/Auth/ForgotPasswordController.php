<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Por favor, introduce tu correo electrónico.',
            'email.email' => 'Por favor, introduce una dirección de correo electrónico válida.',
            'email.exists' => 'Este correo electrónico no está registrado en nuestro sistema.'
        ]);

        // Generamos un token único
        $token = Str::random(64);

        // Guardamos los datos en la base de datos
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Construir la URL de restablecimiento
        $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $request->email], false));

        // Enviar correo con el enlace
        try {
            Mail::raw('Haz clic en el siguiente enlace para restablecer tu contraseña: ' . $resetUrl, function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Restablecer contraseña - MKL Financiero');
            });
            
            return back()->with('status', 'Hemos enviado un enlace para restablecer tu contraseña a tu correo electrónico.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'No se pudo enviar el enlace. Por favor, intenta nuevamente. Error: ' . $e->getMessage()]);
        }
    }
} 