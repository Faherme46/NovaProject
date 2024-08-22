<?php

namespace App\Jobs;

use App\Events\JobStatus;
use App\Http\Controllers\FileController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;




use Carbon\Carbon;
use App\Models\Control;
use App\Models\Asamblea;
use App\Models\Predio;
use App\Models\Question;

class GeneratePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


}
