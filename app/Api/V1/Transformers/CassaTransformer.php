<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Models\Cassa;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;

class CassaTransformer extends TransformerAbstract
{
    public function transform(Cassa $cassa): array
    {
        return [
            'id' => $cassa->id,
            'name' => $cassa->name,
            'currency' => $cassa->currency,
            'users' => $this->transformUsers($cassa->getCassaUsers()),
            'added' => (new Carbon($cassa->created_at))->toDateTimeString(),
            'modified' => (new Carbon($cassa->updated_at))->toDateTimeString(),
        ];
    }

    protected function transformUsers(Collection $cassaUsers): array
    {
        $transformedCassaUsers = [];

        foreach ($cassaUsers as $cassaUser) {
            $user = $cassaUser->getUser();
            $accessType = $cassaUser->getCassaAccessType();

            $transformedCassaUsers[] = [
                'id' => $user->id,
                'name' => $user->name,
                'access_type' => $accessType->getValue(),
            ];
        }

        return $transformedCassaUsers;
    }
}
