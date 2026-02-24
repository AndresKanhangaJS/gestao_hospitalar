<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckMustChangePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se o usuário está logado
        if (Auth::check()) {
            $user = Auth::user();

            // Se ele PRECISA mudar a senha e NÃO está na página de mudança ou fazendo logout
            if ($user->must_change_password &&
                !$request->is('alterar-senha-obrigatoria*') &&
                !$request->is('logout')) {

                return redirect()->route('password.force_change');
            }
        }

        return $next($request);
    }
}
