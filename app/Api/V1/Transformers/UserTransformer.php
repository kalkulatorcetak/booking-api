<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'added' => date('Y-m-d', strtotime($user->created_at))
        ];
    }
}
