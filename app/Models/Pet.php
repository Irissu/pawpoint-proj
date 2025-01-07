<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property string $type
 * @property string|null $breed
 * @property string $date_of_birth
 * @property float|null $weight
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $appointments
 * @property-read int|null $appointments_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|Pet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pet whereBreed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pet whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pet whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pet whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pet whereWeight($value)
 * @mixin \Eloquent
 */
class Pet extends Model
{
    use HasFactory;

    protected  $fillable = [
        'owner_id',
        'name',
        'type',
        'breed',
        'date_of_birth',
        'weight',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    protected static function booted() {
        // validate the pet type
        static::saving(function ($pet) {
            if (!in_array($pet->type, ['dog', 'cat'], true)) {
                throw new \Exception('Invalid pet type');
            }
        });

    }
}
