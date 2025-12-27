<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'activa'];

    /**
     * Una Categoría tiene muchos Negocios.
     */
    public function negocios(): HasMany
    {
        return $this->hasMany(Negocio::class, 'categoria', 'nombre');
        // **IMPORTANTE:** Se usa la clave 'nombre' de Categoría 
        // como clave foránea en la tabla Negocio.
    }
}