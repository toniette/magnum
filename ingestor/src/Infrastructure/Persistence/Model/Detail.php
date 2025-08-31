<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detail extends EloquentModel
{
    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }
}
