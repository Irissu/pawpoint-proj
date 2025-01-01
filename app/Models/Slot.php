<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SlotStatus;

class Slot extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date',
        'start_time' => 'time',
        'end_time' => 'time',
        'status' => SlotStatus::class,
    ];

    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id');
    }

    public function appointments()
    {
        return $this->hasOne(Appointment::class);
    }
}
