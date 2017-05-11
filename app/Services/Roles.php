<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Roles
{
    protected $roles = [];

    public function __construct()
    {
        $this->roles = config('roles');
    }

    public function getRoles(): array
    {
        return array_keys($this->roles);
    }

    public function getRolePermissions(string $role): array
    {
        if (!$this->roleExists($role)) {
            throw new NotFoundHttpException("Role {$role} doesn't exists");
        }

        return $this->roles[$role];
    }

    protected function roleExists(string $role): bool
    {
        return array_key_exists($role, $this->roles);
    }
}