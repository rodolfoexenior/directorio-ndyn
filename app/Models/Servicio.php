<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Negocio;
use App\Models\ServicioImagen;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    // Atributos permitidos para asignación masiva
    protected $fillable = [
        'negocio_id', 'nombre_servicio', 'descripcion_servicio', 'precio'
    ];

    /**
     * Un Servicio pertenece a un Negocio (Relación N:1 inversa).
     */
    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class);
    }
    /**
     * Un Servicio tiene muchas Imágenes (Galería N:N).
     */
    public function imagenes(): BelongsToMany
    {
        // El segundo parámetro es el nombre de la tabla pivote que definimos.
        return $this->belongsToMany(ServicioImagen::class, 'servicio_servicio_imagen');
    }
}