<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function usuario(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function controles(){
        return $this->hasMany(Control::class);
    }


}
