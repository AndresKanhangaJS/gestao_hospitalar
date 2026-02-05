<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            "Super Administrador",
            "Administrador",
            "Médico",
            "Enfermeiro",
            "Recepcionista",
            "Laboratorista",
        ];

        foreach($roles as $key => $value){
            $role = Role::create(['name' => $value]);
        }
    }
}
