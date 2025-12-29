<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Negocio; // <-- Necesitas el modelo Negocio
use Illuminate\Database\Eloquent\Factories\HasFactory;  


use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    // Atributos permitidos para asignación masiva
    protected $fillable = [
        'negocio_id', 'dia_semana', 'hora_apertura', 'hora_cierre', 'esta_cerrado'
    ];

    /**
     * Un Horario pertenece a un Negocio (Relación N:1 inversa).
     */
    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class);
    }
}