<?php

namespace Test\Helpers\V1;

use App\Api\V1\Models\Cassa;
use App\Api\V1\Models\CassaUser;
use App\Api\V1\Models\User;
use Illuminate\Support\Collection;

trait CassaTestHelper
{
    protected function cassaStructure(): array
    {
        return [
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'currency',
                    'users' => [],
                    'added',
                    'modified',
                ]
            ]
        ];
    }

    protected function cassaListStructure(): array
    {
        return [
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'currency',
                        'users' => [],
                        'added',
                        'modified'
                    ]
                ]
            ]
        ];
    }

    protected function createCassa($cassaName, $currency): Cassa
    {
        $cassa = new Cassa();
        $cassa->name = $cassaName;
        $cassa->currency = $currency;
        $cassa->save();

        return $cassa;
    }


    protected function createCassaStub(array $cassaData): Cassa
    {
        $cassa = $this->getCassaStub();
        $cassa->id = $cassaData['id'];
        $cassa->name = $cassaData['name'];
        $cassa->currency = $cassaData['currency'];
        $cassa->setCassaUsers($cassaData['users']);
        $cassa->setCreatedAt($cassaData['added']);
        $cassa->setUpdatedAt($cassaData['modified']);

        return $cassa;
    }

    protected function getCassaStub(): Cassa
    {
        $cassa = new class() extends Cassa
        {
            protected $users;
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

            public function setCassaUsers(array $users): void
            {
                foreach ($users as $userData) {
                    $user = new User();
                    $user->id = $userData['id'];
                    $user->name = $userData['name'];
                    $cassaUser = $this->getCassaUserStub();
                    $cassaUser->access_type=$userData['access_type'];
                    $cassaUser->setUser($user);
                    $this->users[] = $cassaUser;
                }
            }

            public function getCassaUsers(): Collection
            {
                return collect($this->users);
            }

            protected function getCassaUserStub(): CassaUser
            {
                $cassaUser = new class() extends CassaUser
                {
                    protected $user;

                    public function setUser(User $user): void
                    {
                        $this->user = $user;
                    }

                    public function getUser(): User
                    {
                        return $this->user;
                    }
                };

                return $cassaUser;
            }
        };

        return $cassa;
    }

}
