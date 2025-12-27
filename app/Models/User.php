<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <--- Importación necesaria para M:M
use App\Models\Negocio; // <--- Importación necesaria para la relación 1:N

class User extends Authenticatable implements MustVerifyEmail // <--- Asegurando la implementación de MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================================
    // RELACIONES Y MÉTODOS AUXILIARES
    // ==========================================================

    /**
     * Un usuario puede tener múltiples roles (Relación N:N).
     */
    public function roles(): BelongsToMany
    {
        // Usa la convención (Role::class, 'role_user', 'user_id', 'role_id')
        // Si tu relación es simple y sigue las convenciones, solo necesita esto:
        return $this->belongsToMany(Role::class);
    }
    
    /**
     * Método auxiliar para verificar si el usuario tiene un rol específico (por el nombre).
     */
    public function hasRole(string $roleName): bool
    {
        // Verifica si la colección de roles contiene un rol cuyo 'nombre' coincide con el argumento.
        return $this->roles->contains('nombre', $roleName);
    }

    /**
     * Un usuario es dueño de múltiples negocios (Relación 1:N).
     */
    public function negocios()
    {
        return $this->hasMany(Negocio::class);
    }
}