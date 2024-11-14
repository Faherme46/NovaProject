<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define los roles
        $admin= Role::create(['name' => 'Admin']);
        $leader= Role::create(['name' => 'Lider']);
        $operator= Role::create(['name' => 'Operario']);

        Permission::create(['name' => 'users.create'])->assignRole([$admin]);
        Permission::create(['name' => 'users.index'])->assignRole([$admin,$leader]);
        Permission::create(['name' => 'users.update'])->assignRole([$admin]);
        Permission::create(['name' => 'users.delete'])->assignRole([$admin]);

        Permission::create(['name' => 'asignaciones.create'])->assignRole([$admin,$leader,$operator]);
        Permission::create(['name' => 'asignaciones.index'])->assignRole([$admin,$leader,$operator]);
        Permission::create(['name' => 'asignaciones.update'])->assignRole([$admin,$leader,$operator]);
        Permission::create(['name' => 'asignaciones.delete'])->assignRole([$admin,$leader,$operator]);

        Permission::create(['name' => 'predios.create'])->assignRole([$admin]);
        Permission::create(['name' => 'predios.index'])->assignRole([$admin,$leader,$operator]);
        Permission::create(['name' => 'predios.update'])->assignRole([$admin,$leader,$operator]);
        Permission::create(['name' => 'predios.delete'])->assignRole([$admin,$leader]);

        Permission::create(['name' => 'personas.create'])->assignRole([$admin,$leader]);
        Permission::create(['name' => 'personas.index'])->assignRole([$admin,$leader,$operator]);
        Permission::create(['name' => 'personas.update'])->assignRole([$admin,$leader,$operator]);
        Permission::create(['name' => 'personas.delete'])->assignRole([$admin,$leader]);

        Permission::create(['name' => 'asambleas.create'])->assignRole([$admin]);
        Permission::create(['name' => 'asambleas.index'])->assignRole([$admin,$leader,$operator]);
        Permission::create(['name' => 'asambleas.update'])->assignRole([$admin,$leader]);
        Permission::create(['name' => 'asambleas.delete'])->assignRole([$admin]);


        User::create([
            'username' => 'ehernandez',
            'password' => 'ehernandez',
            'passwordTxt' => 'ehernandez',
            'lastName' => 'hernandez',
            'name' => 'emilton',
            'roleTxt'=>'Admin'
            ])->assignRole([$admin]);

            User::create([
                'username' => 'german.gualdron',
                'password' => 'Manch1n1',
                'passwordTxt' => 'Manch1n1',
                'lastName' => 'Gualdron',
                'name' => 'German',
                'roleTxt'=>'Admin'
                ])->assignRole([$admin]);


    }
}
