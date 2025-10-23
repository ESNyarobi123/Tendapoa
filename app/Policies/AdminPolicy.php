<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    /**
     * Determine if the user can access admin features
     */
    public function accessAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can view any user's data
     */
    public function viewAnyUser(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can view specific user's details
     */
    public function viewUser(User $user, User $targetUser): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can monitor conversations
     */
    public function monitorChats(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can view any job
     */
    public function viewAnyJob(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can access analytics
     */
    public function viewAnalytics(User $user): bool
    {
        return $user->role === 'admin';
    }
}

