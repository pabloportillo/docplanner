<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Encripta la contraseña
        ]);

        // Genera un token de acceso para el nuevo usuario
        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    /**
     * Login an existing user.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            // Genera un token de acceso para el usuario
            $token = $user->createToken('authToken')->accessToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Credenciales inválidas'], 401);
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        // Revoca el token del usuario actual
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}