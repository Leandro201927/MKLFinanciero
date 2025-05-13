<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Display the password reset view for the given token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.exists' => 'Este correo electrónico no está registrado en nuestro sistema.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ]);

        // Verificar token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'El token de restablecimiento no es válido.']);
        }

        // Verificar si el token ha expirado (1 hora)
        $expires = Carbon::parse($resetRecord->created_at)->addHour();
        if (Carbon::now()->isAfter($expires)) {
            return back()->withErrors(['email' => 'El token de restablecimiento ha expirado. Por favor, solicita uno nuevo.']);
        }

        // Actualizar la contraseña
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Eliminar el token de restablecimiento
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Enviar notificación de cambio de contraseña
        try {
            Mail::raw('Tu contraseña ha sido restablecida correctamente.', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Contraseña restablecida - MKL Financiero');
            });
        } catch (\Exception $e) {
            // Si el correo falla, no es crítico
        }

        return redirect()->route('sign-in')->with('status', '¡Tu contraseña ha sido restablecida correctamente!');
    }
} 