<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            "acl.menu",
            //
            "papeis.menu",
            "papeis.accoes",
            "papeis.detalhes",
            "papeis.registar",
            "papeis.editar",
            "papeis.eliminar",
            "papeis.associar_permissoes",
            //
            "permissoes.menu",
            "permissoes.accoes",
            "permissoes.detalhes",
            "permissoes.registar",
            "permissoes.editar",
            "permissoes.eliminar",
            //
            "usuarios.menu",
            "usuarios.accoes",
            "usuarios.detalhes",
            "usuarios.registar",
            "usuarios.editar",
            "usuarios.eliminar",
            "usuarios.atribuir_papeis",
        ];

        foreach($permissions as $key => $value){
            $permission = Permission::create(['name' => $value]);
        }
    }
}
