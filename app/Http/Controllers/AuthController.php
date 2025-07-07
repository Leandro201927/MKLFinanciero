<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Validate user credentials
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function validateCredentials($email, $password)
    {
        return Auth::attempt(['email' => $email, 'password' => $password]);
    }
}
