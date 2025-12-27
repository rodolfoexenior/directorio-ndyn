<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Servicio;


class ServicioImagen extends Model
{
    use HasFactory;
    
    // Permitir la asignación masiva de la ruta y el texto alternativo
    protected $fillable = [
        'ruta_archivo',
        'alt_texto',
    ];

    /**
     * Una Imagen puede pertenecer a varios Servicios (N:N inversa).
     */
    public function servicios(): BelongsToMany
    {
        // Laravel usará automáticamente 'servicio_servicio_imagen' como pivote
        return $this->belongsToMany(Servicio::class, 'servicio_servicio_imagen');
    }
}