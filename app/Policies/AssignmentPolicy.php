<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Assignment;

class AssignmentPolicy
{
    /**
     * Determine if user can view the assignment
     */
    public function view(User $user, Assignment $assignment): bool
    {
        // Guru can view their own assignments
        if ($assignment->created_by === $user->id_user) {
            return true;
        }

        // Siswa can view published assignments in their enrolled classes
        if ($assignment->is_published) {
            return $assignment->class->enrollments()
                ->where('id_user', $user->id_user)
                ->where('status', 'active')
                ->exists();
        }

        return false;
    }

    /**
     * Determine if user can update the assignment
     */
    public function update(User $user, Assignment $assignment): bool
    {
        // Only guru who created the assignment can update
        return $assignment->created_by === $user->id_user && $user->role === 'guru';
    }

    /**
     * Determine if user can delete the assignment
     */
    public function delete(User $user, Assignment $assignment): bool
    {
        // Only guru who created the assignment can delete
        return $assignment->created_by === $user->id_user && $user->role === 'guru';
    }

    /**
     * Determine if user can publish the assignment
     */
    public function publish(User $user, Assignment $assignment): bool
    {
        return $assignment->created_by === $user->id_user && $user->role === 'guru';
    }
}
