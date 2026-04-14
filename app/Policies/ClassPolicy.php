<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Classes;

class ClassPolicy
{
    /**
     * Determine if user can view the class
     */
    public function view(User $user, Classes $class): bool
    {
        // Guru can view their own classes
        if ($class->created_by === $user->id_user) {
            return true;
        }

        // Siswa can view classes they're enrolled in
        return $class->enrollments()
            ->where('id_user', $user->id_user)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Determine if user can update the class
     */
    public function update(User $user, Classes $class): bool
    {
        // Only guru who created the class can update
        return $class->created_by === $user->id_user && $user->role === 'guru';
    }

    /**
     * Determine if user can delete the class
     */
    public function delete(User $user, Classes $class): bool
    {
        // Only guru who created the class can delete
        return $class->created_by === $user->id_user && $user->role === 'guru';
    }

    /**
     * Determine if user can manage class (add materials, assignments, etc)
     */
    public function manage(User $user, Classes $class): bool
    {
        return $class->created_by === $user->id_user && $user->role === 'guru';
    }
}
