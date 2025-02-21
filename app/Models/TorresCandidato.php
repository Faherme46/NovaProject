<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TorresCandidato extends Pivot
{
    use HasFactory;
    protected $guarded;
}
