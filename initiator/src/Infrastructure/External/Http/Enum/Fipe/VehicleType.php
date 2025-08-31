<?php

declare(strict_types=1);

namespace Src\Infrastructure\External\Http\Enum\Fipe;

enum VehicleType: string
{
    case CAR = 'carros';
    case TRUCK = 'caminhoes';
    case MOTORCYCLE = 'motos';
}
