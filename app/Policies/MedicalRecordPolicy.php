<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MedicalRecord;
use Auth;

class MedicalRecordPolicy
{
    /**
     * Create a new policy instance.
     */

        public function viewAny(User $user): bool
        {
            return true;
        }

        public function view(User $user, MedicalRecord $medicalRecord): bool
        {
            return true;
        }

        public function create(User $user): bool
        {
            if(Auth::user()->isAdmin() || Auth::user()->isVet()){
                return true;
            } else {
                return false;
            }
        }

        public function update(User $user, MedicalRecord $medicalRecord): bool
        {
            return false;
        }

        public function delete(User $user, MedicalRecord $medicalRecord): bool
        {
            if(Auth::user()->isAdmin()) {
                return true;
            } else {
                return false;
            }
        }

        public function restore(User $user, MedicalRecord $medicalRecord): bool
        {
            return false;
        }

        public function forceDelete(User $user, MedicalRecord $medicalRecord): bool
        {
            return false;
        }
    
}
