<?php

namespace App\Policies;

use App\Models\{Job, User};

class JobPolicy
{
    public function update(User $user, Job $job): bool
    {
        return $user->id === $job->user_id; // owner (muhitaji) only
    }
}
