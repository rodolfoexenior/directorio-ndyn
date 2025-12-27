<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'roles'; 

    // Permite la asignación masiva del campo nombre
    protected $fillable = ['nombre', 'descripcion'];
    /**
     * Un Rol puede pertenecer a muchos Usuarios.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

}