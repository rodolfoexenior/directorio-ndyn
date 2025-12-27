<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User; // <-- Necesitas el modelo User
use App\Models\NegocioContacto; // <-- Necesitas los modelos de negocio
use App\Models\Horario;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\Resena;
use App\Models\Caracteristica;
use App\Models\NegocioImagen; // <-- ¡Aseguramos que el modelo NegocioImagen esté importado!


use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    use HasFactory;

    // Atributos permitidos para asignación masiva
    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'categoria',
        'es_emprendimiento',
        'estado_id',
        'es_destacado',
        'palabras_clave',
        'limite_fotos',
    ];

    /**
     * Un Negocio pertenece a un Usuario (el dueño).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un Negocio tiene un registro de Contacto (uno a uno).
     */
    public function contacto(): HasOne
    {
        return $this->hasOne(NegocioContacto::class);
    }

    /**
     * Un Negocio tiene varios Horarios (uno a muchos).
     */
    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class);
    }

    /**
     * Un Negocio tiene varios Servicios.
     */
    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class);
    }

    /**
     * Un Negocio tiene varios Productos.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    /**
     * Un Negocio tiene múltiples Reseñas.
     */
    public function resenas(): HasMany
    {
        return $this->hasMany(Resena::class);
    }

    /**
     * Un Negocio tiene varias Características (N:N).
     */
    public function caracteristicas(): BelongsToMany
    {
        // La tabla pivote es 'caracteristica_negocio'
        return $this->belongsToMany(Caracteristica::class, 'caracteristica_negocio');
    }
    
    /**
     * Un negocio tiene muchas imágenes.
     */
    public function imagenes()
    {
        // CLAVE: Corregimos la convención fallida de Eloquent. 
        // Estamos forzando el nombre de la tabla a 'negocio_imagenes'.
        return $this->hasMany(NegocioImagen::class, 'negocio_id');
    }
    /**
     * Un Negocio pertenece a un EstadoNegocio.
     */
    public function estado() {
        return $this->belongsTo(EstadoNegocio::class, 'estado_id');
    }


    
}