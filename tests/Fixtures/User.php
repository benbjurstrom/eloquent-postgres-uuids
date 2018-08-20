<?php

namespace BenBjurstrom\EloquentPostgresUuids\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use BenBjurstrom\EloquentPostgresUuids\HasUuid;

class User extends Model
{
    use HasUuid;

    public $primaryKey = 'user_id';
}