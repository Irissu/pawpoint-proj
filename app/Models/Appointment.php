<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AppointmentStatus;

/**
 * 
 *
 * @property int $id
 * @property int $vet_id
 * @property int $owner_id
 * @property int $pet_id
 * @property int $slot_id
 * @property AppointmentStatus $status
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $ownerUser
 * @property-read \App\Models\Pet $pet
 * @property-read \App\Models\Slot $slot
 * @property-read \App\Models\User $vetUser
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment wherePetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereSlotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereVetId($value)
 * @mixin \Eloquent
 */
class Appointment extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => AppointmentStatus::class,
    ]; 

    protected $fillable = [
        'vet_id',
        'owner_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'description',
    ];

    // relaciones

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    /* public function slot()
    {
        return $this->belongsTo(Slot::class);
        
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    } */
/* 
    protected static function booted()
{
    static::saving(function ($appointment) {
        if (Appointment::where('slot_id', $appointment->slot_id)->where('status', 'booked')->exists()) {
            throw new \InvalidArgumentException('Slot is already booked.');
        }
    });
} */

}
