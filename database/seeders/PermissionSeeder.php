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
            "papeis.listar",
            "papeis.accoes",
            "papeis.detalhes",
            "papeis.registar",
            "papeis.editar",
            "papeis.eliminar",
            "papeis.associar_permissoes",
            //
            "permissoes.menu",
            "permissoes.listar",
            "permissoes.accoes",
            "permissoes.detalhes",
            "permissoes.registar",
            "permissoes.editar",
            "permissoes.eliminar",
            //
            "usuarios.menu",
            "usuarios.listar",
            "usuarios.accoes",
            "usuarios.detalhes",
            "usuarios.registar",
            "usuarios.editar",
            "usuarios.eliminar",
            "usuarios.atribuir_papeis",
            //
            "gestao_pacientes.menu",
            //
            "pacientes.menu",
            "pacientes.listar",
            "pacientes.accoes",
            "pacientes.detalhes",
            "pacientes.registar",
            "pacientes.editar",
            "pacientes.eliminar",
            "pacientes.fazer_triagem",
            "pacientes.triagem",
            "pacientes.informacoes_medicas",
            //

            "gestao_episodios.menu",
            //
            "episodios.menu",
            "episodios.listar",
            "episodios.accoes",
            "episodios.detalhes",
            "episodios.registar",
            "episodios.editar",
            "episodios.eliminar",
            //

            "gestao_medicos.menu",
            //
            "medicos.menu",
            "medicos.listar",
            "medicos.accoes",
            "medicos.detalhes",
            "medicos.registar",
            "medicos.editar",
            "medicos.eliminar",
            //
            "gestao_convenios.menu",
            "convenios.menu",
            "convenios.listar",
            "convenios.accoes",
            "convenios.detalhes",
            "convenios.registar",
            "convenios.editar",
            "convenios.eliminar",
            //
            "gestao_configuracoes.menu",
            //
            "laboratorio.menu",
            //
            "gestao_empresas.menu"
        ];

        foreach($permissions as $key => $value){
            $permission = Permission::create(['name' => $value]);
        }
    }
}
