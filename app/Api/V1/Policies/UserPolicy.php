<?php

namespace App\Api\V1\Policies;

use App\Api\V1\Models\User;

class UserPolicy
{
    public function update(User $actualUser, User $editedUser)
    {
        return $actualUser->hasPermission('USER_UPDATE');
    }
}