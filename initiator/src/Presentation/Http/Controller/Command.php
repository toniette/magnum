<?php

declare(strict_types=1);

namespace Src\Presentation\Http\Controller;

use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class Command
{
    public function details(string $detailId, string $versionId): JsonResponse
    {
        $data = request()->only('type', 'value', 'brand', 'model', 'reference', 'fuel', 'fuel_type');
        data_set($data, 'updated_at', new DateTimeImmutable()->format(DateTimeInterface::ATOM));

        DB::table('details')
            ->where(['id' => $detailId, 'version_id' => $versionId])
            ->update($data);

        return response()->json();
    }
}
