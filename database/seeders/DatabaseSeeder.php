<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RoleSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\State;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        State::create(['id'=>1,'value'=>'Activo']);
        State::create(['id'=>2,'value'=>'Ausente']);
        State::create(['id'=>3,'value'=>'Retirado']);
        State::create(['id'=>5,'value'=>'Entregado']);
        State::create(['id'=>4,'value'=>'Unsigned']);
        $this->call(RoleSeeder::class);
    }
}
