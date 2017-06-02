<?php

namespace Test\Helpers\V1;

use App\Api\V1\Models\User;

trait UserTestHelper
{
    protected function userStructure(): array
    {
        return [
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'email',
                    'roles' => [],
                    'added',
                    'modified',
                ]
            ]
        ];
    }

    protected function userListStructure(): array
    {
        return [
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'email',
                        'roles',
                        'added',
                        'modified'
                    ]
                ]
            ]
        ];
    }

    protected function createUserStub($userData): User
    {
        $user = $this->getUserStub();
        $user->id = $userData['id'];
        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->setRoles($userData['roles']);
        $user->setCreatedAt($userData['added']);
        $user->setUpdatedAt($userData['modified']);

        return $user;
    }

    protected function getUserStub()
    {
        $user = new class() extends User
        {
            protected $created_at;
            protected $updated_at;

            public function setCreatedAt($created_at)
            {
                $this->created_at = $created_at;
            }

            public function getCreatedAtAttribute()
            {
                return $this->created_at;
            }

            public function setUpdatedAt($updated_at)
            {
                $this->updated_at = $updated_at;
            }

            public function getUpdatedAtAttribute()
            {
                return $this->updated_at;
            }
        };

        return $user;
    }
}
