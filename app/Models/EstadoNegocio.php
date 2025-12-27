<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Negocio; // <-- Necesitas el modelo Negocio

use Illuminate\Database\Eloquent\Model;

class EstadoNegocio extends Model
{
    //
    public function negocios() {
    return $this->hasMany(Negocio::class);
}
}