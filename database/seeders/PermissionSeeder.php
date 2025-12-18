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
            "role.view",
            "role.create",
            "role.edit",
            "role.delete",
            "user.view",
            "user.create",
            "user.edit",
            "user.delete",
        ];

        foreach($permissions as $key => $value){
            $permission = Permission::create(['name' => $value]);
        }
    }
}
