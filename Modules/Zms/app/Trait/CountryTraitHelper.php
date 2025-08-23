<?php

namespace Modules\Zms\Trait;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait CountryTraitHelper
{
    public function latFormat() : Attribute
    {
        return Attribute::make(
            get: fn() => number_format($this->lat, 6),
        );
    }

    public function lngFormat() : Attribute
    {
        return Attribute::make(
            get: fn() => number_format($this->lng, 6),
        );
    }
}
