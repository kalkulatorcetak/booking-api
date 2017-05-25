<?php

namespace App\Console\Commands;

use App\Api\V1\Models\User;
use Illuminate\Console\Command;

class TokenGenerateCommand extends Command
{
    protected $signature = 'generate:token {userId=1}';

    protected $description = 'Generate an jwt auth access token';

    public function handle(): void
    {
        $testUser = User::findOrFail($this->argument('userId'));
        $token = app('tymon.jwt.auth')->fromUser($testUser);

        echo sprintf('Generated token: %s', $token);
    }
}
