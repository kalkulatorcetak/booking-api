<?php

namespace App\Api\V1\Models;

use App\Api\V1\Enums\CassaAccessType;
use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;

class Cassa extends Model
{
    protected $fillable = [
        'name', 'currency'
    ];

    public function getCassaUsers(): Collection
    {
        return CassaUser::where('cassa_id', $this->id)->get();
    }

    public function getCassaUserAccessType(User $user): CassaAccessType
    {
        $cassaUser = CassaUser::where('cassa_id', $this->id)->where('user_id', $user->id)->get();

        if (count($cassaUser) === 1) {
            $accessType = $cassaUser->first()->getCassaAccessType();
        } else {
            $accessType = new CassaAccessType(CassaAccessType::NONE);
        }

        return $accessType;
    }

    public function addCassaUser(User $user, CassaAccessType $accessType): void
    {
        $cassaUser = new CassaUser();
        $cassaUser->setCassa($this);
        $cassaUser->setUser($user);
        $cassaUser->setCassaAccessType($accessType);

        $cassaUser->save();
    }

    public function removeCassaUser(User $user): void
    {
        CassaUser::where('cassa_id', $this->id)->where('user', $user->id)->delete();
    }

    public function removeAllCassaUsers(): void
    {
        CassaUser::where('cassa_id', $this->id)->delete();
    }
}
