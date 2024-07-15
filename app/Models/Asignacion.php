<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Asignacion extends Model
{
    use HasFactory;
    protected $guarded = [];

    
    public function control()
    {
        return $this->belongsTo(Control::class, 'id', 'id_control');
    }




}
