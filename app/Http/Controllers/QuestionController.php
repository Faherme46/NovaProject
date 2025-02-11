<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\VotesImport;
use App\Models\Control;
use App\Models\Question;
use App\Models\Result;
use App\Models\QuestionsPrefab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class QuestionController extends Controller
{


    public $question;
    public $options = ['optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF'];
    public function __construct($id = null)
    {
        if ($id) {
            $this->question = Question::find($id);
        }
    }
    public function createPrefabQuestion(Request $request)
    {
        $messages = [
            '*.required' => 'El campo :attribute no puede estar vacio'
        ];
        $request->validate([
            'title' => 'required',
            'optionA' => 'required',
            'optionB' => 'required',
            'optionC' => 'required',
            'optionD' => 'required',
            'optionE' => 'required',
            'optionF' => 'required',
            'type' => 'required'
        ], $messages);
        $question = QuestionsPrefab::create($request->all);
    }

    public function updateQuestion(Request $request)
    {
        $messages = [
            '*.required' => 'El campo :attribute no puede estar vacio'
        ];
        $request->validate([
            'title' => 'required',
            'optionA' => 'required',
            'optionB' => 'required',
            'optionC' => 'required',
            'optionD' => 'required',
            'optionE' => 'required',
            'optionF' => 'required',
            'type' => 'required',
            'id'
        ], $messages);
    }



    public function createResults()
    {
        $controlsAbsent = Control::whereNotIn('state', [1, 4])->get();
        $controlsAssigned = Control::where('state', 1)->get();
        $valuesCoef = [
            'optionA'      => 0,
            'optionB'      => 0,
            'optionC'      => 0,
            'optionD'      => 0,
            'optionE'      => 0,
            'optionF'      => 0,
            'nule'     => 0,
            'absent'    => 0,
            'abstainted' => 0,
        ];
        $valuesNom = [
            'optionA'      => 0,
            'optionB'      => 0,
            'optionC'      => 0,
            'optionD'      => 0,
            'optionE'      => 0,
            'optionF'      => 0,
            'nule'     => 0,
            'absent'    => 0,
            'abstainted' => 0,
        ];
        $availableOptions = $this->question->getAvailableOptions();
        $valuesCoef['absent'] += $controlsAbsent->sum('sum_coef');
        $valuesNom['absent']  += $controlsAbsent->sum('predios_total');
        $valuesCoef['abstainted'] += $controlsAssigned->sum('sum_coef_abs');
        $valuesNom['abstainted']  +=  $controlsAssigned->sum('predios_abs');
        $valuesCoef['abstainted'] += $controlsAssigned->whereNull('vote')->sum('sum_coef');
        $valuesNom['abstainted']  +=  $controlsAssigned->whereNull('vote')->sum('predios_total');
        if ($this->question->type == 5) {

            $valuesNom['optionA'] += $controlsAssigned->where('vote', 'A')->sum('predios_total');
            $valuesCoef['optionA'] += $controlsAssigned->where('vote', 'A')->sum('sum_coef');
            $valuesNom['optionB'] += $controlsAssigned->where('vote', 'B')->sum('predios_total');
            $valuesCoef['optionB'] += $controlsAssigned->where('vote', 'B')->sum('sum_coef');
            $valuesNom['nule'] += $controlsAssigned->whereNotIn('vote', ['A', 'B'])->sum('predios_total');
            $valuesCoef['nule'] += $controlsAssigned->whereNotIn('vote', ['A', 'B'])->sum('sum_coef');
            Control::where('state', 1)
                ->whereIn('vote', ['A', 'B'])
                ->update(['t_publico' => DB::raw('CASE WHEN vote = "A" THEN 1 ELSE 0 END')]);
        } else if ($this->question->type == 1) {
            $valuesNom['nule'] += $controlsAssigned->whereNotNull('vote')->sum('predios_total');
            $valuesCoef['nule'] += $controlsAssigned->whereNotNull('vote')->sum('sum_coef');
        } else {
            foreach ($availableOptions as $option) {
                $valuesNom['option' . $option] += $controlsAssigned->where('vote', $option)->sum('predios_vote');
                $valuesCoef['option' . $option] += $controlsAssigned->where('vote', $option)->sum('sum_coef_can');
            }
            $valuesNom['nule'] += $controlsAssigned->whereNotNull('vote')->whereNotIn('vote', $availableOptions)->sum('predios_vote');
            $valuesCoef['nule'] += $controlsAssigned->whereNotNull('vote')->whereNotIn('vote', $availableOptions)->sum('sum_coef_can');
        }

        $totalCoef = 0;
        foreach ($valuesCoef as $value) {
            $totalCoef += $value;
        }
        $totalNom = 0;
        foreach ($valuesNom as $value) {
            $totalNom += $value;
        }
        try {
            $valuesNom['question_id'] = $this->question->id;
            $valuesNom['isCoef'] = false;
            $valuesNom['total'] = $totalNom;

            $valuesCoef['question_id'] = $this->question->id;
            $valuesCoef['isCoef'] = true;
            $valuesCoef['total'] = $totalCoef;
            if ($this->question->resultCoef) {
                $this->question->resultNom->update($valuesNom);
                $this->question->resultCoef->update($valuesCoef);
            } else {
                $this->question->resultNom = Result::create($valuesNom);
                $this->question->resultCoef = Result::create($valuesCoef);
            }

            $this->question->quorum = Control::where('state', 1)->sum('sum_coef');
            $this->question->predios = Control::where('state', 1)->sum('predios_vote');

            $fileController = new FileController;

            $fileController->exportResult($this->question);
            $votes = Control::whereNotNull('vote')->pluck('id')->toArray();
            $fileController->exportVotes($votes, $this->question->id, $this->question->title);
        } catch (Throwable $th) {
            throw $th;
        }

        $chartCoef = $this->setImageUrl($this->question->resultCoef, ($this->question->type == 1));
        $chartNom = $this->setImageUrl($this->question->resultNom, ($this->question->type == 1));

        return [$chartCoef, $chartNom];
    }
    public function setImageUrl($result, $quorum)
    {
        try {
            $path = $this->createChart($result, $quorum);
            $result->chartPath = $path;
            $result->save();
            return $path;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function createChart($result, $quorum)
    {
        // Array para almacenar los datos del gráfico
        $labels = [];
        $values = [];

        // Agregar las opciones A-F si tienen valor

        foreach ($this->options as $option) {
            if ($this->question->$option !== null) {
                $labels[] = $this->question->$option;
                $values[] = $result->$option;
            }
        }
        $six = count($labels) == 6;


        if ($quorum) {
            $additionalOptions = [
                'nule' => 'PRESENTE'
            ];
        } else {
            $additionalOptions = [
                'abstainted' => 'ABSTENCION',
                'absent' => 'AUSENTE',
            ];

            if ($this->question->type != 2 || !$six) {
                $additionalOptions['nule'] = 'NULO';
            }
        }

        // Agregar abstained, absent y nule con sus etiquetas en español


        foreach ($additionalOptions as $key => $label) {
            $labels[] = $label;
            $values[] = $result->$key;
        }

        $fileController = new FileController;

        $imageName = ($result->isCoef) ? 'coefChart' : 'nominalChart';
        $chart = $fileController->createChart($this->question->id, $this->question->title, $labels, $values, $imageName);

        return $chart;
    }



    public function crearGrafica(Request $request)
    {

        $this->question = Question::find($request->idQuestion);
        $this->setImageUrl($this->question->resultCoef, false);
        $this->setImageUrl($this->question->resultNom, false);
        return back()->with('success','Gráficas generadas correctamente correctamente');
    }


    public function importarVotos(Request $request){

        $this->question=Question::find($request->idQuestion);

        $nameAsamblea=cache('asamblea')['name'];
        $externalFilePathVotes = Storage::disk('externalAsambleas')->path($nameAsamblea.'/Preguntas/'.$this->question->id.'/votos.xlsx');

        if (!file_exists($externalFilePathVotes)) {
            back()->withErrors('error',"El archivo no se encontró en la ruta: {$externalFilePathVotes}");
        }

        // $externalFilePathStates=Storage::disk('externalAsambleas')->path($nameAsamblea.'/Tablas/states.xlsx');
        // $export = new StatesExport();
        // Excel::store($export, $externalFilePathVotes);

        Control::query()->update(['vote' => null]);
        $import=Excel::import(new VotesImport, $externalFilePathVotes);

        $this->createResults();

        Control::query()->update(['vote' => null]);
        $controls=Control::all();
        foreach ($controls as  $control) {
            $control->setCoef();
        }
        return back()->with('success','Votos importados correctamente');
    }
}
