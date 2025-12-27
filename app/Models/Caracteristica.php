<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <--- Importación de BelongsToMany
use App\Models\Negocio;

class Caracteristica extends Model
{
    use HasFactory;

    // Atributos permitidos para asignación masiva
    protected $fillable = [
        'nombre', 
        'descripcion',
        'icono'
    ];

    /**
     * Una Característica pertenece a muchos Negocios (Relación N:N).
     */
    public function negocios(): BelongsToMany
    {
        return $this->belongsToMany(Negocio::class, 
                                    'caracteristica_negocio', 
                                    'caracteristica_id', 
                                    'negocio_id');
    }
}