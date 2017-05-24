<?php

namespace App\Console\Commands;

use App\Api\V1\Models\User;
use Illuminate\Console\Command;

class TokenGenerateCommand extends Command
{
    protected $signature = 'generate:token {userId=1}';

    protected $description = 'Generate an jwt auth access token';

    protected $jwtAuth;

    public function handle(): void
    {
        $this->jwtAuth = app('tymon.jwt.auth');
        $testUser = User::findOrFail($this->argument('userId'));
        $token = $this->jwtAuth->fromUser($testUser);

        echo sprintf('Generated token: %s', $token);
    }
}
