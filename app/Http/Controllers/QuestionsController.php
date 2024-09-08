<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\Result;
use App\Models\Question;
use App\Http\Controllers\FileController;
use App\Models\Control;

class QuestionsController extends Controller
{

    public $fileController;
    public function __construct() {
        $this->fileController = new FileController;
    }
    




}
