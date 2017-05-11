<?php

namespace App\Api\V1\Policies;

use App\Api\V1\Models\User;

class UserPolicy
{
    public function before(User $user, $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function list(User $actualUser): bool
    {
        return $actualUser->hasPermission('USER_LOAD');
    }

    public function load(User $actualUser, User $editedUser): bool
    {
        return ($actualUser->hasPermission('USER_LOAD')
            || $actualUser->id == $editedUser->id
        );
    }

    public function create(User $actualUser): bool
    {
        return $actualUser->hasPermission('USER_CREATE');
    }

    public function update(User $actualUser, User $editedUser): bool
    {
        return ($actualUser->hasPermission('USER_UPDATE')
            || $actualUser->id == $editedUser->id
        );
    }

    public function delete(User $actualUser, User $editedUser)
    {
        return ($actualUser->hasPermission('USER_DELETE')
            || $actualUser->id == $editedUser->id
        );
    }
}
