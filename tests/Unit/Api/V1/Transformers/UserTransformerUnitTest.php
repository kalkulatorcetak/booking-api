<?php

namespace Test\Unit\Api\V1\Transformers;

use App\Api\V1\Transformers\UserTransformer;
use Carbon\Carbon;
use Test\Helpers\V1\UserTestHelper;
use Test\UnitTestCase;

class UserTransformerUnitTest extends UnitTestCase
{
    use UserTestHelper;

    /**
     * @test
     * @dataProvider userDataProvider
     */
    public function userTransform(array $userData): void
    {
        $user = $this->createUserStub($userData);

        $this->assertEquals($userData, (new UserTransformer())->transform($user));
    }

    public function userDataProvider(): array
    {
        return [
            [
                [
                    'id' => 1,
                    'name' => 'Test User 1',
                    'email' => 'test.user1@test.com',
                    'roles' => ['ADMIN'],
                    'added' => (new Carbon())->toDateTimeString(),
                    'modified' => (new Carbon())->toDateTimeString(),
                ]
            ],
            [
                [
                    'id' => 2,
                    'name' => 'Test User 2',
                    'email' => 'test.user2@test.com',
                    'roles' => ['CASHIER'],
                    'added' => (new Carbon())->toDateTimeString(),
                    'modified' => (new Carbon())->toDateTimeString(),
                ]
            ],
        ];
    }
}
