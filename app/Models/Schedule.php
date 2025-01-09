<?php

namespace App\Models;

use App\Enums\DaysOfTheWeek;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $cast = [
        'day_of_week' => DaysOfTheWeek::class
    ];

    protected $fillable = [
        'vet_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active'
    ];

    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id'); 
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }
}
