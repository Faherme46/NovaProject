<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sesion';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_asamblea'
    ];

    public function setManualData(array $data)
    {
        // Asignar los datos al modelo
        $this->fill($data);

        // Guardar los cambios en la base de datos
        $this->save();

        return $this;
    }
}
