<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Question extends Model
{
    use HasFactory;
    protected $guarded = [];

    
    public function resultCoef(){
        return $this->hasOne(Result::class)->where('isCoef',1);
    }
    public function resultNom(){
        return $this->hasOne(Result::class)->where('isCoef',0);
    }

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

    public function plancha(){
        return $this->hasOne(Plancha::class);
    }
}
