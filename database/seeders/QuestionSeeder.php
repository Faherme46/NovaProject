<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\QuestionsPrefab;
use App\Models\QuestionType;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuestionType::create(['name' => 'Quórum']);
        QuestionType::create(['name' => 'Seleccion']);
        QuestionType::create(['name' => 'Aprobacion']);
        QuestionType::create(['name' => 'SI/NO']);
        QuestionType::create(['name' => 'TD']);
        QuestionType::create(['name' => 'Plancha']);

        QuestionsPrefab::create([
            'title' => 'Aprobacion de estados Financieros',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => 'Aprobado',
            'optionE' => 'No Aprobado',
            'optionF' => '',
            'type' => 3
        ]);
        QuestionsPrefab::create([
            'title' => 'Aprobacion del reglamento de la asamblea',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => 'Aprobado',
            'optionE' => 'No Aprobado',
            'optionF' => '',
            'type' => 3
        ]);
        QuestionsPrefab::create([
            'title' => 'Aprobacion de orden del dia',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => 'Aprobado',
            'optionE' => 'No Aprobado',
            'optionF' => '',
            'type' => 3
        ]);
        QuestionsPrefab::create([
            'title' => 'Aprobacion de Presupuestos',
           'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => 'Aprobado',
            'optionE' => 'No Aprobado',
            'optionF' => '',
            'type' => 3
        ]);
        
        QuestionsPrefab::create([
            'title' => 'Aprobacion del Acta',
           'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => 'Aprobado',
            'optionE' => 'No Aprobado',
            'optionF' => '',
            'type' => 3
        ]);
        QuestionsPrefab::create([
            'title' => 'Aprobacion del Reglamento de la asamblea',
           'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => 'Aprobado',
            'optionE' => 'No Aprobado',
            'optionF' => '',
            'type' => 3
        ]);
        QuestionsPrefab::create([
            'title' => 'Comite de Convivencia',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'type' => 2
        ]);
        
        QuestionsPrefab::create([
            'title' => 'Comite de Verificación del Acta',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'type' => 2
        ]);
        QuestionsPrefab::create([
            'title' => 'Consejo de Administracion',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'type' => 2
        ]);
        QuestionsPrefab::create([
            'title' => 'Consentimiento de datos personales',
            'optionA' => 'Voto Publico',
            'optionB' => 'Voto Privado',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'type' => 5
        ]);
        QuestionsPrefab::create([
            'title' => 'Eleccion de Presidente',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'type' => 2
        ]);
        QuestionsPrefab::create([
            'title' => 'Eleccion de revisor fiscal',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'type' => 2
        ]);
        QuestionsPrefab::create([
            'title' => 'Eleccion de secretario',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'type' => 2
        ]);
        QuestionsPrefab::create([
            'title' => 'Verificación de Quórum',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'type' => 1
        ]);
        QuestionsPrefab::create([
            'title' => 'Proposicion',
            'optionA' => 'SI',
            'optionB' => 'NO',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'type' => 4
        ]);

        QuestionsPrefab::create([
            'title' => 'Prueba',
            'optionA' => '',
            'optionB' => '',
            'optionC' => '',
            'optionD' => '',
            'optionE' => '',
            'optionF' => '',
            'type' => 2
        ]);


    }
}
