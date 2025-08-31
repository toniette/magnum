<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends EloquentModel
{
    public function models(): HasMany
    {
        return $this->hasMany(Model::class);
    }
}
