<?php

namespace App\Api\V1\Policies;

use App\Api\V1\Enums\CassaAccessType;
use App\Api\V1\Models\Cassa;
use App\Api\V1\Models\User;

class CassaPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function list(User $user): bool
    {
        return $user->hasPermission('CASSA_LOAD');
    }

    public function load(User $user, Cassa $cassa): bool
    {
        return ($user->hasPermission('CASSA_LOAD')
            && $this->hasCassaAccess($user, $cassa)
        );
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('CASSA_CREATE');
    }

    public function update(User $user, Cassa $cassa): bool
    {
        return ($user->hasPermission('CASSA_UPDATE')
            && $this->hasCassaAccess($user, $cassa, [CassaAccessType::EDIT])
        );
    }

    public function delete(User $user, Cassa $cassa): bool
    {
        return ($user->hasPermission('CASSA_DELETE')
            && $this->hasCassaAccess($user, $cassa, [CassaAccessType::EDIT])
        );
    }

    protected function hasCassaAccess(User $user, Cassa $cassa, array $accesTypes = []): bool
    {
        if (empty($accesTypes)) {
            $accesTypes = CassaAccessType::listValues();
        }

        $access = $cassa->getCassaUserAccessType($user)->getValue();

        return in_array($access, $accesTypes, true);
    }
}
