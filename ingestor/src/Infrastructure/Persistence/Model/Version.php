<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Version extends EloquentModel
{
    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }

    public function detail(): HasOne
    {
        return $this->hasOne(Detail::class);
    }
}
