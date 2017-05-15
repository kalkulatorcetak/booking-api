<?php

namespace App\Api\V1\Models;

use App\Models\AbstractModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Lumen\Auth\Authorizable;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends AbstractModel implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $permissions;

    protected $fillable = [
        'name', 'email',
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPassword(string $password): void
    {
        $this->password = app('hash')->make($password);
    }

    public function setRoles(array $roles): void
    {
        $user = app('Dingo\Api\Auth\Auth')->user();

        if (in_array('ADMIN', $roles) && !$user->isAdmin()) {
            throw new UnauthorizedHttpException(null, 'Only ADMIN user can add ADMIN role to a user');
        }

        $this->roles = implode(',', $roles);
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    public function getRoles(): array
    {
        return explode(',', $this->roles);
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    public function getPermissions(): array
    {
        if (!isset($this->permissions)) {
            $this->permissions = [];
            foreach ($this->getRoles() as $role) {
                foreach (app('roles')->getRolePermissions($role) as $permission) {
                    if (!in_array($permission, $this->permissions)) {
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
