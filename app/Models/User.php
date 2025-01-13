<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\RoleUsers as Role;

/**
 * 
 *
 * @property Role $role
 * @method bool isAdmin()
 * @method bool isVet()
 * @method bool isOwner()
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string $phone
 * @property string|null $address
 * @property string|null $bio
 * @property string|null $img_path
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $ownerAppointments
 * @property-read int|null $owner_appointments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pet> $pets
 * @property-read int|null $pets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Slot> $slots
 * @property-read int|null $slots_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $vetAppointments
 * @property-read int|null $vet_appointments_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImgPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class User extends Authenticatable implements HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'phone',
        'address',
        'bio',
        'role',
        'img_path',
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
        return $this->hasMany(Pet::class, 'owner_id');
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
        /**
     * Check if the user is an Admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role->value === Role::Admin->value;
    }
    /**
     * Check if the user is a Vet.
     *
     * @return bool
     */
    public function isVet(): bool
    {
        return $this->role->value === Role::Vet->value;
    }
    /**
     * Check if the user is an Owner.
     *
     * @return bool
     */
    
    public function isOwner(): bool
    {
        return $this->role->value === Role::User->value;
    }  

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

/*     protected static function booted()
    {
        static::saving(function ($user) {
            $allowedRoles = ['Admin', 'Vet', 'User'];
            if (!in_array($user->role->value, $allowedRoles, true)) {
                throw new \InvalidArgumentException('Invalid role assigned to user.');
            }
        });
    } */

}
