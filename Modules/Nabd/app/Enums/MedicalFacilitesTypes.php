<?php

namespace Modules\Nabd\Enums;

use Modules\Base\Enums\EnumHelper;


enum MedicalFacilitesTypes: string
{
    use EnumHelper;

    case CLINIC     = 'clinic';
    case PHARMACY   = 'pharmacy';
}
