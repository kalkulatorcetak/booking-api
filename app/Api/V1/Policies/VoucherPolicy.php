<?php

namespace App\Api\V1\Policies;

use App\Api\V1\Enums\CassaAccessType;
use App\Api\V1\Models\User;
use app\Api\V1\Models\Voucher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VoucherPolicy
{

    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function list(User $user): bool
    {
        return $user->hasPermission('VOUCHER_LOAD');
    }

    public function load(User $user, Voucher $voucher): bool
    {
        return ($user->hasPermission('VOUCHER_LOAD')
            && $this->hasVoucherCassaAccess($user, $voucher)
        );
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('VOUCHER_CREATE');
    }

    public function update(User $user, Voucher $voucher): bool
    {
        return ($user->hasPermission('VOUCHER_UPDATE')
            && $this->hasVoucherCassaAccess($user, $voucher, [CassaAccessType::EDIT])
        );
    }

    public function delete(User $user, Voucher $voucher): bool
    {
        return ($user->hasPermission('VOUCHER_DELETE')
            && $this->hasVoucherCassaAccess($user, $voucher, [CassaAccessType::EDIT])
        );
    }

    protected function hasVoucherCassaAccess(User $user, Voucher $voucher, array $accesTypes = []): bool
    {
        if (empty($accesTypes)) {
            $accesTypes = CassaAccessType::listValues();
        }

        $cassa = $voucher->getCassa();

        if ($cassa === null) {
            throw new NotFoundHttpException(sprintf('Voucher number %s doesn\'t have cassa!', $voucher->number));
        }

        return in_array($cassa->getCassaUserAccessType($user)->getValue(), $accesTypes, true);
    }
}
