<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Result;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuestionType::create(['name' => 'Quorum']);
        QuestionType::create(['name' => 'Seleccion']);
        QuestionType::create(['name' => 'Aprobacion']);
        QuestionType::create(['name' => 'SI/NO']);
        QuestionType::create(['name' => 'TD']);

        Question::create([
            'title' => 'Aprobacion de estados Financieros',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => 'Aprobado',
            'optionE' => 'No Aprobado',
            'optionF' => '',
            'prefab' => true,
            'type' => 3
        ]);
        Question::create([
            'title' => 'Aprobacion de orden del dia',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => 'Aprobado',
            'optionE' => 'No Aprobado',
            'optionF' => '',
            'prefab' => true,
            'type' => 3
        ]);
        Question::create([
            'title' => 'Aprobacion de Presupuestos',
           'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => 'Aprobado',
            'optionE' => 'No Aprobado',
            'optionF' => '',
            'prefab' => true,
            'type' => 3
        ]);
        Question::create([
            'title' => 'Aprobacion del Acta',
           'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => 'Aprobado',
            'optionE' => 'No Aprobado',
            'optionF' => '',
            'prefab' => true,
            'type' => 3
        ]);
        Question::create([
            'title' => 'Comite de Convivencia',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'prefab' => true,
            'type' => 2
        ]);
        Question::create([
            'title' => 'Consejo de Administracion',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'prefab' => true,
            'type' => 2
        ]);
        Question::create([
            'title' => 'Consentimiento de datos personales',
            'optionA' => 'Voto Publico',
            'optionB' => 'Voto Privado',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'prefab' => true,
            'type' => 5
        ]);
        Question::create([
            'title' => 'Eleccion de Presidente',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'prefab' => true,
            'type' => 2
        ]);
        Question::create([
            'title' => 'Eleccion de revisor fiscal',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'prefab' => true,
            'type' => 2
        ]);
        Question::create([
            'title' => 'Eleccion de secretario',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'prefab' => true,
            'type' => 2
        ]);
        Question::create([
            'title' => 'Quorum',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'prefab' => true,
            'type' => 1
        ]);
        Question::create([
            'title' => 'Proposicion',
            'optionA' => 'SI',
            'optionB' => 'NO',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'prefab' => true,
            'type' => 4
        ]);
        Question::create([
            'title' => 'Prueba',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'prefab' => true,
            'type' => 2
        ]);


    }
}
