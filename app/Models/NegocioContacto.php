<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Negocio; // <-- Necesitas el modelo Negocio


use Illuminate\Database\Eloquent\Model;

class NegocioContacto extends Model
{
    use HasFactory;

    // **IMPORTANTE:** Definir el nombre de la tabla (usamos guion bajo en la migración)
    protected $table = 'negocio_contacto'; 

    // Atributos permitidos para asignación masiva
    protected $fillable = [
        'negocio_id', 'direccion_manual', 'latitud', 'longitud', 
        'telefono', 'email_contacto', 'web'
    ];

    /**
     * Un registro de Contacto pertenece a un Negocio (Relación 1:1 inversa).
     */
    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class);
    }
}
