<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;

class FilePolicy
{
    /**
     * Determine if the given post can be updated by the user.
     */
    public function update(User $user, File $file): bool
    {
        return $user->id === $file->user_id;
    }

    /**
     * Determine if the given post can be updated by the user.
     */
    public function view(User $user, File $file): bool
    {
        return $user->id === $file->user_id;
    }
}
