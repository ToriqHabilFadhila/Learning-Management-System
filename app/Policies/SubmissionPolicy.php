<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Submission;

class SubmissionPolicy
{
    /**
     * Determine if user can view the submission
     */
    public function view(User $user, Submission $submission): bool
    {
        // Student can view their own submissions
        if ($submission->id_user === $user->id_user) {
            return true;
        }

        // Guru can view submissions for their assignments
        return $submission->assignment->created_by === $user->id_user && $user->role === 'guru';
    }

    /**
     * Determine if user can grade the submission
     */
    public function grade(User $user, Submission $submission): bool
    {
        // Only guru who created the assignment can grade
        return $submission->assignment->created_by === $user->id_user && $user->role === 'guru';
    }

    /**
     * Determine if user can delete the submission
     */
    public function delete(User $user, Submission $submission): bool
    {
        // Only guru who created the assignment can delete submissions
        return $submission->assignment->created_by === $user->id_user && $user->role === 'guru';
    }
}
