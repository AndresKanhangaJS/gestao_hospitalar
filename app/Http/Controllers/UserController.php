<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        $roles = Role::all();
        return view('usuario.usuario-index', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('LGP25'),
            'status' => 'activo',
        ]);

        // Associar papéis
        $user->assignRole($validated['roles']);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário registado com sucesso.');
    }

    public function edit(User $user)
    {
        $user->load('roles');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'roles' => $user->roles->pluck('name'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'status' => ['required', Rule::in(['activo', 'inactivo'])],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => $validated['status'],
        ]);

        // sincroniza papéis
        $user->syncRoles($validated['roles']);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function show(User $user)
    {
        $user->load('roles');

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'roles' => $user->roles->pluck('name'),
            'created_at' => $user->created_at->format('d/m/Y H:i'),
            'updated_at' => $user->updated_at->format('d/m/Y H:i'),
        ]);
    }

    public function destroy(User $user)
    {
        // 1. Segurança: impedir apagar super admin
        if ($user->hasRole('admin')) {
            return response()->json([
                'message' => 'Não é permitido eliminar um utilizador administrador.'
            ], 403);
        }

        // 2. Segurança: impedir auto-eliminação
        if (auth()->id() === $user->id) {
            return response()->json([
                'message' => 'Não pode eliminar o seu próprio utilizador.'
            ], 403);
        }

        try {
            // 3. Remover associações de roles (Spatie)
            $user->syncRoles([]);

            // 4. Eliminar usuário
            $user->delete();

            return response()->json([
                'message' => 'Usuário eliminado com sucesso.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao eliminar usuário.'
            ], 500);
        }
    }

}
