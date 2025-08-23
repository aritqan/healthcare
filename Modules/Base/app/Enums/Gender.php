<?php

namespace Modules\Base\Enums;

final class Gender
{
    const MALE   = 'male';
    const FEMALE = 'female';
    const OTHER  = 'other';

    public static function all()
    {
        return [
            self::MALE   ,
            self::FEMALE ,
            self::OTHER  ,
        ];
    }

    public static function getGenders()
    {
        return [
            self::MALE      => trans('base::base.gender.' . self::MALE),
            self::FEMALE    => trans('base::base.gender.' . self::FEMALE),
            self::OTHER     => trans('base::base.gender.' . self::OTHER),
        ];
    }

}
