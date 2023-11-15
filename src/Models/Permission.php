<?php

namespace Mehrdadakbari\Mongodb\Permissions\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class Permission extends Model implements PermissionContract
{
    /**
     * A permission can be applied to roles.
     *
     * @return \Illuminate\Support\Collection $roles
     */
    public function roles(): BelongsToMany
    {
        return $this->getPermissions(
            config('laravel-permission.models.role')
        );
    }

    /**
     * A role may be assigned to various users.
     *
     * @return \Illuminate\Support\Collection $users
     */
    public function users()
    {
        return $this->getPermissions(
            config('auth.model') ?: config('auth.providers.users.model')
        );
    }

    /**
     * Find a permission by its name.
     *
     * @param string $name
     *
     * @throws PermissionDoesNotExist
     */
    public static function findByName(string $name, ?string $guardName): PermissionContract
    {
        $permission = static::where('name', $name)->first();

        if (! $permission) {
            throw new PermissionDoesNotExist();
        }

        return $permission;
    }

    /**
     * Find a permission by its id.
     *
     * @param int|string $id
     *
     * @throws PermissionDoesNotExist
     */
    public static function findById(int|string $id, ?string $guardName): PermissionContract
    {
        $permission = static::where('id', $id)->first();

        if (! $permission) {
            throw new PermissionDoesNotExist();
        }

        return $permission;
    }

    public static function findOrCreate(string $name, ?string $guardName): PermissionContract
    {
        $permission = static::where('name', $name)->first();

        if (! $permission) {
            $permission = static::create([
                'name' => $name,
                'guard_name' => $guardName
            ]);
        }

        return $permission;
    }

    protected function getPermissions($model)
    {
        return (new $model)->where('permissions.id', $this->getAttribute($this->primaryKey));
    }
}
