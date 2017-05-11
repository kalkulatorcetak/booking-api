<?php

namespace App\Services;

class Permissions
{
    protected $permissions;

    public function __construct()
    {
        $this->permissions = config('permissions');
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }
}
