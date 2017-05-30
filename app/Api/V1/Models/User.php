<?php

namespace App\Api\V1\Models;

use App\Contracts\Cacheable;
use App\Models\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements Cacheable, JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $permissions = [];

    protected $fillable = [
        'name', 'email',
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier(): int
    {
        return (int) $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function setPassword(string $password): void
    {
        $this->password = app('hash')->make($password);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = implode(',', $roles);
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    public function getRoles(): array
    {
        return $this->roles === null ? [] : explode(',', $this->roles);
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions(), true);
    }

    public function getPermissions(): array
    {
        if (empty($this->permissions)) {
            $this->permissions = [];
            foreach ($this->getRoles() as $role) {
                foreach (app('roles')->getRolePermissions($role) as $permission) {
                    if (!in_array($permission, $this->permissions, true)) {
                        $this->permissions[] = $permission;
                    }
                }
            }
        }

        return $this->permissions;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('ADMIN');
    }
}
