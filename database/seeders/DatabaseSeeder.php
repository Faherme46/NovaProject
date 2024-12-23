<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\General;
use Illuminate\Database\Seeder;

use App\Models\State;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(QuestionSeeder::class);
        State::create(['id'=>1,'value'=>'Activo']);
        State::create(['id'=>2,'value'=>'Ausente']);
        State::create(['id'=>4,'value'=>'Unsigned']);
        State::create(['id'=>5,'value'=>'Entregado']);
        General::create(['key'=>'themeId','value'=>'5']);
        $this->call(RoleSeeder::class);
    }
}
