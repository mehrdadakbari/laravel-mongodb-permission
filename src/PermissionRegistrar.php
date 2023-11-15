<?php

namespace Mehrdadakbari\Mongodb\Permissions;

use Illuminate\Cache\CacheManager;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\PermissionRegistrar as BasePermissionRegistrar;

class PermissionRegistrar extends BasePermissionRegistrar
{
    /**
     * {@inheritdoc}
     */
    public function __construct(CacheManager $cache)
    {
        parent::__construct($cache);
    }

    /**
     * Get the current permissions.
     *
     * @return \Moloquent\Eloquent\Collection
     */
    protected function getPermissionsWithRoles(): Collection
    {
        return app(Permission::class)->get();
    }
}
