<?php

namespace App\Http\Controllers\ACL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();

        return view('acl.permission-index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        Permission::create([
            'name' => $validated['name']
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permissão registada com sucesso.');
    }

    public function edit(Permission $permission)
    {
        return response()->json([
            'id' => $permission->id,
            'name' => $permission->name,
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id
        ]);

        $permission->update([
            'name' => $validated['name']
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permissão atualizada com sucesso.');
    }

    public function destroy(Permission $permission)
    {
        // Segurança básica (opcional)
        if ($permission->name === 'acesso-total') {
            return response()->json([
                'message' => 'Não é permitido eliminar esta permissão.'
            ], 403);
        }

        $permission->delete();

        return response()->json([
            'message' => 'Permissão eliminada com sucesso.'
        ]);
    }
}
