<?php

namespace Mehrdadakbari\Mongodb\Permissions\Traits;

use Spatie\Permission\Contracts\Permission;
use Mehrdadakbari\Mongodb\Permissions\Contracts\EmbedPermission;
use Carbon\Carbon;

trait HasPermissions
{
    /**
     * Grant the given permission(s) to a role.
     *
     * @param string|array|Permission|\Illuminate\Support\Collection $permissions
     *
     * @return HasPermissions
     */
    public function givePermissionTo(...$permissions)
    {
        $permissions = collect($permissions)
            ->flatten()
            ->map(function ($permission) {
                return $this->getStoredPermission($permission, 'web');
            })
            ->all();

        foreach ($permissions as $permission) {
            if (! $this->permissions()->where('id', $permission->_id)->first()) {
                $this->permissions()->save(app(EmbedPermission::class)->forceFill([
                    'id' => $permission->_id,
                    'created_at' => Carbon::now()
                ]));
            }
        }

        $this->save();

        return $this;
    }

    /**
     * Revoke the given permission.
     *
     * @param $permission
     *
     * @return HasPermissions
     */
    public function revokePermissionTo($permission, string $guard = 'web')
    {
        $permission = $this->getStoredPermission($permission, $guard);
        $embedPermission = $this->permissions()->where('id', $permission->_id);

        $this->permissions()->detach($embedPermission);

        return $this;
    }

    /**
     * Remove all current permissions and set the given ones.
     *
     * @param array ...$permissions
     *
     * @return $this
     */
    public function syncPermissions(...$permissions)
    {
        $this->permissions()->delete();

        return $this->givePermissionTo($permissions);
    }

    /**
     * @param string|array|Permission|\Illuminate\Support\Collection $permissions
     *
     * @return Permission
     */
    protected function getStoredPermission($permissions, string $guard)
    {
        if (is_string($permissions)) {
            return app(Permission::class)->findByName($permissions, $guard);
        }

        if (is_array($permissions)) {
            return app(Permission::class)->whereIn('name', $permissions)->get();
        }

        return $permissions;
    }
}
