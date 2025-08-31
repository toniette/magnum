<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class EloquentModel extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;
}
