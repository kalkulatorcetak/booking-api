<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Models\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoles(),
            'added' => (new Carbon($user->created_at))->toDateTimeString(),
            'modified' => (new Carbon($user->updated_at))->toDateTimeString(),
        ];
    }
}
