<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Super Administrador -> TODAS as permissões
        |--------------------------------------------------------------------------
        */
        $superAdminRole = Role::where('name', 'Super Administrador')->first();

        if ($superAdminRole) {
            $allPermissions = Permission::all();
            $superAdminRole->syncPermissions($allPermissions);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Administrador -> Permissões do grupo "usuários.*"
        |--------------------------------------------------------------------------
        */
        $adminRole = Role::where('name', 'Administrador')->first();

        if ($adminRole) {
            $userPermissions = Permission::where('name', 'like', 'usuarios.%')->get();
            $adminRole->syncPermissions($userPermissions);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Associar Super Administrador ao usuário Master
        |--------------------------------------------------------------------------
        */
        $masterUser = User::where('email', 'master@gmail.com')->first();

        if ($masterUser && $superAdminRole) {
            $masterUser->assignRole($superAdminRole);
        }
    }
}
