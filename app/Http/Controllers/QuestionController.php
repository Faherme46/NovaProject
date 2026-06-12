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
use Illuminate\Support\Facades\Http;
use Exception;
use Throwable;

class QuestionController extends Controller
{


    public $question;
    public $isRondas;
    public $options = ['optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF'];
    public function __construct($id = null, $isRondas = false)
    {
        if ($id) {
            $this->question = Question::find($id);
            $this->isRondas = $isRondas;
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
        $controlsAbsent = Control::whereIn('state', [2, 5])->get();
        $controlsAssigned = Control::where('state', 1)->get();
        $valuesCoef = [
            'optionA' => 0,
            'optionB' => 0,
            'optionC' => 0,
            'optionD' => 0,
            'optionE' => 0,
            'optionF' => 0,
            'nule' => 0,
            'absent' => 0,
            'abstainted' => 0,
        ];
        $valuesNom = [
            'optionA' => 0,
            'optionB' => 0,
            'optionC' => 0,
            'optionD' => 0,
            'optionE' => 0,
            'optionF' => 0,
            'nule' => 0,
            'absent' => 0,
            'abstainted' => 0,
        ];
        $availableOptions = $this->question->getAvailableOptions();
        $valuesCoef['absent'] += $controlsAbsent->sum('sum_coef');
        $valuesNom['absent'] += $controlsAbsent->sum('predios_total');
        $valuesCoef['abstainted'] += $controlsAssigned->sum('sum_coef_abs');
        $valuesNom['abstainted'] += $controlsAssigned->sum('predios_abs');
        $valuesCoef['abstainted'] += $controlsAssigned->whereNull('vote')->sum('sum_coef_can');
        $valuesNom['abstainted'] += $controlsAssigned->whereNull('vote')->sum('predios_vote');
        if ($this->question->type == 5) {
            $valuesNom['optionA'] += $controlsAssigned->where('vote', 'A')->sum('predios_vote');
            $valuesCoef['optionA'] += $controlsAssigned->where('vote', 'A')->sum('sum_coef_can');
            $valuesNom['optionB'] += $controlsAssigned->where('vote', 'B')->sum('predios_vote');
            $valuesCoef['optionB'] += $controlsAssigned->where('vote', 'B')->sum('sum_coef_can');
            $valuesNom['nule'] += $controlsAssigned->whereNotIn('vote', ['A', 'B', null])->sum('predios_vote');
            $valuesCoef['nule'] += $controlsAssigned->whereNotIn('vote', ['A', 'B', null])->sum('sum_coef_can');
            Control::where('state', 1)
                ->whereIn('vote', ['A', 'B'])
                ->update(['t_publico' => DB::raw('CASE WHEN vote = "A" THEN 1 ELSE 0 END')]);
        } else if ($this->question->type == 1) {
            $valuesNom['nule'] += $controlsAssigned->whereNotNull('vote')->sum('predios_total');
            $valuesCoef['nule'] += $controlsAssigned->whereNotNull('vote')->sum('sum_coef');
        } else {
            if ($this->isRondas) {
                Control::whereNotNull('voted')->update(['vote' => null]);
            }
            $controlsAssigned = Control::where('state', 1)->whereNotNull('vote')->get();

            foreach ($availableOptions as $option) {
                $valuesNom['option' . $option] += $controlsAssigned->where('vote', $option)->sum('predios_vote');
                $valuesCoef['option' . $option] += $controlsAssigned->where('vote', $option)->sum('sum_coef_can');
            }
            $valuesNom['nule'] += $controlsAssigned->whereNotNull('vote')->whereNotIn('vote', $availableOptions)->sum('predios_vote');
            $valuesCoef['nule'] += $controlsAssigned->whereNotNull('vote')->whereNotIn('vote', $availableOptions)->sum('sum_coef_can');
            json_encode($availableOptions);
            if ($this->isRondas) {
                Control::whereNotNull('vote')->whereNotIn('vote', $availableOptions)->update(['vote' => null]);
                Control::whereNotNull('vote')->update(['voted' => 1]);
            }
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
            // $fileController->exportResult($this->question);
            $fileController->exportVotes($this->question->id, $this->question->title, $this->question->parent_id);
        } catch (Throwable $th) {
            throw $th;
        }
        if (!$this->isRondas) {
            $chartCoef = $this->setImageUrl($this->question->resultCoef, ($this->question->type == 1));
            $chartNom = $this->setImageUrl($this->question->resultNom, ($this->question->type == 1));
        } else {
            $chartCoef = '';
            $chartNom = '';
        }

        return [$chartCoef, $chartNom];
    }


    public function storeRondas($parentId)
    {
        $mainQuestion = Question::find($parentId);
        $rondasExtra = $mainQuestion->rondas;
        //a rondas extra añadir main question como ronda 1
        $valuesCoef = [];
        $valuesNom = [];
        $valuesCoefRonda = [];
        $valuesNomRonda = [];

        $totalCoef = 0;
        $totalNom = 0;
        $resultCoefRonda = $mainQuestion->resultCoef;
        $resultNomRonda = $mainQuestion->resultNom;
        $availableOptions = $mainQuestion->getAvailableOptions();

        $valuesCoefRonda = [];
        $valuesNomRonda = [];
        foreach ($availableOptions as $option) {
            $nameOption = 'option' . $option;
            $valuesCoefRonda[$option]['name'] = $mainQuestion->$nameOption;
            $valuesNomRonda[$option]['name'] = $mainQuestion->$nameOption;
            $valuesCoefRonda[$option]['value'] = round($resultCoefRonda->$nameOption, 3);
            $valuesNomRonda[$option]['value'] = $resultNomRonda->$nameOption;
            $totalCoef += $resultCoefRonda->$nameOption;
            $totalNom += $resultNomRonda->$nameOption;
        }

        $valuesCoef[$mainQuestion->idRonda] = $valuesCoefRonda;
        $valuesNom[$mainQuestion->idRonda] = $valuesNomRonda;


        foreach ($rondasExtra as $index => $ronda) {
            $resultCoefRonda = $ronda->resultCoef;
            $resultNomRonda = $ronda->resultNom;
            $availableOptions = $ronda->getAvailableOptions();

            $valuesCoefRonda = [];
            $valuesNomRonda = [];
            foreach ($availableOptions as $option) {
                $nameOption = 'option' . $option;
                $valuesCoefRonda[$option]['name'] = $ronda->$nameOption;
                $valuesNomRonda[$option]['name'] = $ronda->$nameOption;
                $valuesCoefRonda[$option]['value'] = round($resultCoefRonda->$nameOption, 3);
                $valuesNomRonda[$option]['value'] = $resultNomRonda->$nameOption;
                $totalCoef += $resultCoefRonda->$nameOption;
                $totalNom += $resultNomRonda->$nameOption;
            }

            $valuesCoef[$ronda->idRonda] = $valuesCoefRonda;
            $valuesNom[$ronda->idRonda] = $valuesNomRonda;
        }
        $controlsAssigned = Control::where('state', 1)->get();
        $controlsAbsent = Control::whereIn('state', [2, 5])->get();
        $valuesCoef[$mainQuestion->idRonda]['abstainted']['name'] = 'ABSTENCIÓN';
        $valuesCoef[$mainQuestion->idRonda]['abstainted']['value'] = 0;
        $valuesCoef[$mainQuestion->idRonda]['abstainted']['value'] += $controlsAssigned->sum('sum_coef_abs');
        $valuesCoef[$mainQuestion->idRonda]['abstainted']['value'] += $controlsAssigned->whereNull('voted')->sum('sum_coef_can');
        $valuesCoef[$mainQuestion->idRonda]['absent']['name'] = 'AUSENTES';
        $valuesCoef[$mainQuestion->idRonda]['absent']['value'] = 0;
        $valuesCoef[$mainQuestion->idRonda]['absent']['value'] += $controlsAbsent->sum('sum_coef');

        $valuesNom[$mainQuestion->idRonda]['abstainted']['name'] = 'ABSTENCIÓN';
        $valuesNom[$mainQuestion->idRonda]['abstainted']['value'] = 0;
        $valuesNom[$mainQuestion->idRonda]['abstainted']['value'] += $controlsAssigned->sum('predios_abs');
        $valuesNom[$mainQuestion->idRonda]['abstainted']['value'] += $controlsAssigned->whereNull('voted')->sum('predios_vote');
        $valuesNom[$mainQuestion->idRonda]['absent']['name'] = 'AUSENTES';
        $valuesNom[$mainQuestion->idRonda]['absent']['value'] = 0;
        $valuesNom[$mainQuestion->idRonda]['absent']['value'] += $controlsAbsent->sum('predios_total');



        $chartCoef = $this->createChartRondas($parentId, $mainQuestion->title, $valuesCoef, 'coefChartRondas', $mainQuestion->quorum, $mainQuestion->type == 1);
        $chartNom = $this->createChartRondas($parentId, $mainQuestion->title, $valuesNom, 'nominalChartRondas', $mainQuestion->quorum, $mainQuestion->type == 1);
        return [$chartCoef, $chartNom];
    }

    public function createChartRondas($mainId, $title, $values, $name, $delegados, $blanco)
    {
        // Datos para el gráfico

        $asambleaName = cache('asamblea')['name'];
        $path = $asambleaName . '/Preguntas/' . ($mainId);
        // Ruta donde se guardará la imagen
        $output_path = Storage::disk('externalAsambleas')->path($path);

        if (!file_exists($output_path)) {
            mkdir($output_path, 0755, true);
        }

        //todo numero de preguntas en defecto
        $localPath = $asambleaName . '/' . ($mainId) . '/' . $name . '.png';
        // Crear un array con los datos
        $data = [
            'title' => $title,
            'output' => $output_path . '/' . $name . '.png',
            'values' => $values,
            'nameAsamblea' => $asambleaName,
            'delegados' => $delegados,
            'blanco' => $blanco
        ];
        $fileController = new FileController;
        try {
            $response = Http::post('http://localhost:5000/create-plot-rondas', $data);
            return $fileController->loadImage($output_path . '/' . $name . '.png', $localPath);
        } catch (Throwable $th) {

            throw new Exception('Error al conectar con el servidor python' . $th->getMessage());
        }
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
                $values[] = round($result->$option, 3);
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
            $values[] = round($result->$key, 3);

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
        return back()->with('success', 'Gráficas generadas correctamente correctamente');
    }


    public function fixAllgraficas()
    {
        $questions = Question::all();
        foreach ($questions as $question) {
            if ($question->resultCoef) {
                $this->question = $question;
                $this->setImageUrl($question->resultCoef, false);
                $this->setImageUrl($question->resultNom, false);
            }
        }

        return redirect()->route('home')->with('success', 'Graficas Generadas correctamente');
    }

    public function importVotesAll()
    {
        $questions = Question::all();
        foreach ($questions as $question) {
            if ($question->resultCoef) {
                $this->question = $question;
                $request = new Request();
                $this->importarVotos($request, $question->id);
            }
        }

        return redirect()->route('home')->with('success', 'Graficas Generadas correctamente');
    }


    public function importarVotos(Request $request, $id = null)
    {
        if ($id) {
            $question = Question::find($id);
        } else {
            $this->question = Question::find($request->idQuestion);
        }


        $nameAsamblea = cache('asamblea')['name'];
        $externalFilePathVotes = Storage::disk('externalAsambleas')->path($nameAsamblea . '/Preguntas/' . $this->question->id . '/votos.xlsx');

        if (!file_exists($externalFilePathVotes)) {
            back()->withErrors('error', "El archivo no se encontró en la ruta: {$externalFilePathVotes}");
        }

        // $externalFilePathStates=Storage::disk('externalAsambleas')->path($nameAsamblea.'/Tablas/states.xlsx');
        // $export = new StatesExport();
        // Excel::store($export, $externalFilePathVotes);

        Control::query()->update(['vote' => null]);
        $import = Excel::import(new VotesImport, $externalFilePathVotes);

        $this->createResults();

        Control::query()->update(['vote' => null]);
        $controls = Control::all();
        foreach ($controls as $control) {
            $control->setCoef();
        }
        return back()->with('success', 'Votos importados correctamente');
    }

    public function setCoefAll()
    {
        $controls = Control::all();
        foreach ($controls as $c) {
            $c->setCoef();
        }
    }
}
