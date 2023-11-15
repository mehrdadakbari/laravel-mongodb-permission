<?php

namespace Mehrdadakbari\Mongodb\Permissions\Models;

use MongoDB\Laravel\Eloquent\Model;
use Mehrdadakbari\Mongodb\Permissions\Contracts\EmbedRole as EmbedRoleContract;

class EmbedRole extends Model implements EmbedRoleContract
{
}
