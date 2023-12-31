<?php

namespace Mehrdadakbari\Mongodb\Permissions\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsToMany;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class Role extends Model implements RoleContract
{
    use HasPermissions;

    protected $fillable = ['name', 'guard_name'];

    /**
     * A role may be given various permissions.
     *
     * @return MongoDB\Laravel\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.table_names.role_has_permissions')
        );
    }

    /**
     * A role may be assigned to various users.
     *
     * @return \Illuminate\Support\Collection $users
     */
    public function users()
    {
        return $this->getUsers(
            config('auth.model') ?: config('auth.providers.users.model')
        );
    }

    /**
     * Find a role by its name.
     *
     * @param string $name
     *
     * @throws RoleDoesNotExist
     *
     * @return Role
     */
    public static function findByName(string $name, ?string $guardName): RoleContract
    {
        $role = static::where('name', $name)->first();

        if (! $role) {
            throw new RoleDoesNotExist();
        }

        return $role;
    }

    public static function findById(int|string $id, ?string $guardName): RoleContract
    {
        $role = static::where('id', $id)->first();

        if (! $role) {
            throw new RoleDoesNotExist();
        }

        return $role;
    }

    public static function findOrCreate(string $name, ?string $guardName): RoleContract
    {
        $role = static::where('name', $name)->first();

        if (! $role) {
            Role::create([
                'name' => $name,
                'guard_name' => $guardName
            ]);
        }

        return $role;
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|Permission $permission
     *
     * @return bool
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        if (is_string($permission)) {
            $permission = app(Permission::class)->findByName($permission);
        }

        return $this->permissions->contains('id', $permission->_id);
    }

    protected function getUsers($model)
    {
        return (new $model)->where('roles.id', $this->getAttribute($this->primaryKey));
    }
}
