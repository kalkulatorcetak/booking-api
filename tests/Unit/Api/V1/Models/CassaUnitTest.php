<?php

namespace Test\Unit\Api\V1\Models;

use App\Api\V1\Enums\CassaAccessType;
use App\Api\V1\Enums\Currency;
use App\Api\V1\Models\Cassa;
use App\Api\V1\Models\User;
use Test\UnitTestCase;

class CassaUnitTest extends UnitTestCase
{
    /**
     * @test
     */
    public function createNewCassa(): void
    {
        $this->createCassa('Test Cassa', Currency::HUF);

        $this->seeInDatabase('cassas', ['name' => 'Test Cassa', 'currency' => Currency::HUF]);
    }

    /**
     * @test
     */
    public function addCassaUser(): void
    {
        $cassa = $this->createCassa('Test Cassa', Currency::HUF);

        $user = $this->getAdminUser();
        $cassa->addCassaUser($user, new CassaAccessType(CassaAccessType::EDIT));

        $this->seeInDatabase('cassa_user', ['cassa_id' => $cassa->id, 'user_id' => $user->id, 'access_type' => 'edit']);
    }

    /**
     * @test
     */
    public function removeCassaUser(): void
    {
        $cassa = $this->createCassa('Test Cassa', Currency::HUF);

        $user = $this->getAdminUser();
        $cassa->addCassaUser($user, new CassaAccessType(CassaAccessType::EDIT));

        $this->seeInDatabase('cassa_user', ['cassa_id' => $cassa->id, 'user_id' => $user->id, 'access_type' => 'edit']);

        $cassa->removeCassaUser($user);

        $this->notSeeInDatabase('cassa_user', ['cassa_id' => $cassa->id, 'user_id' => $user->id, 'access_type' => 'edit']);
    }

    /**
     * @test
     */
    public function removeAllCassaUser(): void
    {
        $cassa = $this->createCassa('Test Cassa', Currency::HUF);

        $user1 = User::findOrFail(1);
        $user2 = User::findOrFail(2);
        $cassa->addCassaUser($user1, new CassaAccessType(CassaAccessType::EDIT));
        $cassa->addCassaUser($user2, new CassaAccessType(CassaAccessType::READ));

        $this->seeInDatabase('cassa_user', ['cassa_id' => $cassa->id, 'user_id' => $user1->id, 'access_type' => 'edit']);
        $this->seeInDatabase('cassa_user', ['cassa_id' => $cassa->id, 'user_id' => $user2->id, 'access_type' => 'read']);

        $cassa->removeAllCassaUsers();

        $this->notSeeInDatabase('cassa_user', ['cassa_id' => $cassa->id]);
    }

    /**
     * @test
     */
    public function getCassaUserAccessType(): void
    {
        $cassa = $this->createCassa('Test Cassa', Currency::HUF);

        $user = $this->getAdminUser();
        $cassa->addCassaUser($user, new CassaAccessType(CassaAccessType::EDIT));

        $accessType = $cassa->getCassaUserAccessType($user);

        $this->assertEquals(CassaAccessType::EDIT, $accessType);
    }

    protected function getAdminUser(): User
    {
        return User::findOrFail(1);
    }

    protected function createCassa($cassaName, $currency): Cassa
    {
        $cassa = new Cassa();
        $cassa->name = $cassaName;
        $cassa->currency = $currency;
        $cassa->save();

        return $cassa;
    }
}
