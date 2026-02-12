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

        /*
        |--------------------------------------------------------------------------
        | 4. Recepcionista -> Permissões
        |--------------------------------------------------------------------------
        */

            $recepcionistaRole = Role::where('name', 'Recepcionista')->first();

            if ($recepcionistaRole) {
                $recepcionistaPermissions = Permission::whereIn('name', [
                    'gestao_pacientes.menu',
                    'pacientes.menu',
                    'pacientes.listar',
                    'pacientes.accoes',
                    'pacientes.detalhes',
                    'pacientes.registar',
                    'pacientes.editar',
                    'episodios.registar',
                    'episodios.detalhes',
                ])->get();

                $recepcionistaRole->syncPermissions($recepcionistaPermissions);
            }

        /*
        |--------------------------------------------------------------------------
        | 5. Enfermeiro -> Permissões
        |--------------------------------------------------------------------------
        */
            $enfermeiroRole = Role::where('name', 'Enfermeiro')->first();

            if ($enfermeiroRole) {
                $enfermeiroPermissions = Permission::whereIn('name', [
                    'gestao_pacientes.menu',
                    'pacientes.menu',
                    'pacientes.listar',
                    'pacientes.accoes',
                    'pacientes.triagem',
                    'pacientes.fazer_triagem',
                    'episodios.listar',
                ])->get();

                $enfermeiroRole->syncPermissions($enfermeiroPermissions);
            }

        /*
        |--------------------------------------------------------------------------
        | 6. Médico -> Permissões
        |--------------------------------------------------------------------------
        */
            $medicoRole = Role::where('name', 'Médico')->first();

            if ($medicoRole) {
                $medicoPermissions = Permission::whereIn('name', [
                    'gestao_pacientes.menu',
                    'pacientes.menu',
                    'pacientes.listar',
                    'pacientes.accoes',
                    'pacientes.detalhes',
                    'pacientes.triagem',
                    'pacientes.informacoes_medicas',
                    'episodios.accoes',
                    'episodios.listar',
                    'episodios.detalhes',
                ])->get();

                $medicoRole->syncPermissions($medicoPermissions);
            }
    }
}
