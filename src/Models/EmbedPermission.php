<?php

namespace Mehrdadakbari\Mongodb\Permissions\Models;

use MongoDB\Laravel\Eloquent\Model;
use Mehrdadakbari\Mongodb\Permissions\Contracts\EmbedPermission as EmbedPermissionContract;

class EmbedPermission extends Model implements EmbedPermissionContract
{
}
