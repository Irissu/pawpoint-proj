<?php
namespace App\Enums;

enum AppointmentStatus: string {
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}