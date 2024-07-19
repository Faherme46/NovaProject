<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Question extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'title',
        'optionA',
        'optionB',
        'optionC',
        'optionD',
        'optionE',
        'optionF',
        'nominalPriotiry',
        'prefab',
        'type'
    ];
}
