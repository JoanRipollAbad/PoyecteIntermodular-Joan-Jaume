<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table = 'incidencias';

    protected $fillable = [
        'user_id',
        'nombre',
        'email',
        'asunto',
        'mensaje',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
