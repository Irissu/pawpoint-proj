<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AppointmentStatus;

class Appointment extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => AppointmentStatus::class,
    ];

    // relaciones

    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function vetUser()
    {
        return $this->belongsTo(User::class, 'vet_id');
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
        
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
