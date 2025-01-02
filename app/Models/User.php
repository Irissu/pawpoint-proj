<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\RoleUsers as Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
        'role' => Role::class,
    ];
    // relaciones

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function ownerAppointments()
    {
        return $this->hasMany(Appointment::class, 'owner_id');
    }

    public function vetAppointments()
    {
        return $this->hasMany(Appointment::class, 'vet_id');
    }

    public function slots() 
    {
        return $this->hasMany(Slot::class);
    }

    // helpers para verificar roles
    public function isAdmin(): bool
    {
        return $this->role->value === Role::Admin;
    }
    
    public function isVet(): bool
    {
        return $this->role->value === Role::Vet;
    }
    
    public function isOwner(): bool
    {
        return $this->role->value === Role::User;
    }  
}
