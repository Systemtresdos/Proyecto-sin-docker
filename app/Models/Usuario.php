<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'telefono',
        'direccion',
        'email',
        'password',
        'estado',
        'rol_id',
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
/*     public function getEmailForPasswordReset()
    {
        return $this->correo;
    } */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function initials()
    {
        $words = explode(' ', $this->nombre);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(mb_substr($word, 0, 1));
        }
        return $initials;
    }
    public function cliente(){
        return $this->hasMany(Cliente::class, 'usuario_id');
    }
    public function encargado(){
        return $this->hasMany(Encargado::class);
    }
    public function rol(){
        return $this->belongsTo(rol::class);
    }
}
