<?php

namespace Mehrdadakbari\Mongodb\Permissions;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Mehrdadakbari\Mongodb\Permissions\Contracts\EmbedPermission as EmbedPermissionContract;
use Mehrdadakbari\Mongodb\Permissions\Contracts\EmbedRole as EmbedRoleContract;
use Mehrdadakbari\Mongodb\Permissions\Models\Permission;
use Mehrdadakbari\Mongodb\Permissions\Models\Role;
use Mehrdadakbari\Mongodb\Permissions\Traits\HasPermissions;
use Mehrdadakbari\Mongodb\Permissions\Traits\HasRoles;
use Illuminate\Contracts\Auth\Access\Gate;


class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     */
    public function boot(PermissionRegistrar $permissionLoader, Gate $gate)
    {
        $permissionLoader->registerPermissions($gate);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerAliasLoaders();
        $this->registerEmbedModelBindings();
    }

    /**
     * Extends Traits class
     */
    protected function registerAliasLoaders()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Spatie\Permission\Models\Permission', Permission::class);
        $loader->alias('Spatie\Permission\Models\Role', Role::class);
        $loader->alias('Spatie\Permission\Traits\HasPermissions', HasPermissions::class);
        $loader->alias('Spatie\Permission\Traits\HasRoles', HasRoles::class);
    }

    /**
     * Bind the Permission and Role model into the IoC.
     */
    protected function registerEmbedModelBindings()
    {
        $configTables = $this->app->config['laravel-permission.table_names'];

        $this->app->bind(EmbedPermissionContract::class, $configTables['role_has_permissions']);
        $this->app->bind(EmbedPermissionContract::class, $configTables['user_has_permissions']);
        $this->app->bind(EmbedRoleContract::class, $configTables['user_has_roles']);
    }
}
