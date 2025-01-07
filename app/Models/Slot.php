<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SlotStatus;

/**
 * 
 *
 * @property int $id
 * @property int $vet_id
 * @property \Illuminate\Support\Carbon $date
 * @property mixed $start_time
 * @property mixed $end_time
 * @property SlotStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Appointment|null $appointments
 * @property-read \App\Models\User $vet
 * @method static \Illuminate\Database\Eloquent\Builder|Slot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Slot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slot whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slot whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slot whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slot whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slot whereVetId($value)
 * @mixin \Eloquent
 */
class Slot extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date',
        'start_time' => 'time',
        'end_time' => 'time',
        'status' => SlotStatus::class,
    ];
    protected $fillable = [
        'vet_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'schedule_id',
    ];

    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id');
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    protected static function booted()
{
    static::saving(function ($slot) {
        if ($slot->start_time >= $slot->end_time) {
            throw new \InvalidArgumentException('Start time must be before end time.');
        }
    });
}
}
