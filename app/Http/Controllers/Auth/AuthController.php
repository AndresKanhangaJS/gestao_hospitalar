<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login.
     */
    public function loginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa o login do usuário.
     */
    public function login(Request $request)
    {
        // 1. Validação (Mantida exatamente como a sua)
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // 2. Tentativa de Login
        if (!Auth::attempt($validator->validated(), $request->boolean('remember'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email ou senha incorretos.',
            ], 401);
        }

        // 3. Login OK -> Regenera sessão
        $request->session()->regenerate();

        // 4. DEFINIÇÃO DA VARIÁVEL $user (O que faltava!)
        $user = Auth::user();

        // 5. Verificação de Mudança de Senha
        if ($user->must_change_password) {
            return response()->json([
                'status' => 'force_password_change',
                'message' => 'Primeiro acesso detectado. É necessário alterar a senha.',
                'redirect' => route('password.force_change'),
            ], 200);
        }

        // 6. Sucesso Total
        return response()->json([
            'status' => 'success',
            'redirect' => route('dashboard'),
        ], 200);
    }

    /**
     * Logout do usuário.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate(); // Limpa a sessão
        $request->session()->regenerateToken(); // Gera um novo CSRF token

        return redirect('/login');
    }
}
