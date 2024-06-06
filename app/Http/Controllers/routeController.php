<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class routeController extends Controller
{
    public function creaReunion(){
        return view('admin.creaReunion');
    }
}
