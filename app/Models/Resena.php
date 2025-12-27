<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Negocio; // <-- Necesitas el modelo Negocio
use App\Models\User;    // <-- Necesitas el modelo User

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;

    // Atributos permitidos para asignación masiva
    protected $fillable = [
        'negocio_id', 'user_id', 'puntuacion', 'comentario'
    ];

    /**
     * Una Reseña pertenece a un Negocio.
     */
    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class);
    }

    /**
     * Una Reseña pertenece a un Usuario (el que la escribió).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
