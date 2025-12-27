<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Negocio; // 

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Atributos permitidos para asignación masiva
    protected $fillable = [
        'negocio_id', 'nombre_producto', 'descripcion_producto', 'precio', 'imagen_ruta'
    ];

    /**
     * Un Producto pertenece a un Negocio (Relación N:1 inversa).
     */
    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class);
    }
}