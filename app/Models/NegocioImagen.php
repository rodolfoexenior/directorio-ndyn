<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioImagen extends Model
{
    use HasFactory;

    // CLAVE: Corregimos la convención de nombres forzando el nombre de la tabla.
    // Esto resuelve el error "Base table or view not found: 1146 Table 'ndyn.negocio_imagens' doesn't exist"
    protected $table = 'negocio_imagenes'; 

    protected $fillable = [
        'negocio_id',
        'ruta_archivo',
        'es_principal',
        'orden',
    ];

    // Relación Inversa (Una imagen pertenece a un negocio)
    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}