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
        'prefab',
        'quorum',
        'predios',
        'seconds',
        'type'
    ];

    public function results(){
        return $this->hasMany(Result::class);
    }

    public function getAvailableOptions(){
        $options=[
            'A'=>'optionA',
            'B'=>'optionB',
            'C'=>'optionC',
            'D'=>'optionD',
            'E'=>'optionE',
            'F'=>'optionF',
        ];

        $availableOption=[];
        foreach ($options as $key => $value) {
            if ($this->$value) {
                $availableOption[]=$key;
            }
        }
        return $availableOption;
    }
}
