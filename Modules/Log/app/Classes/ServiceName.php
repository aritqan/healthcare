<?php

namespace Modules\Log\Classes;

final class ServiceName
{
    const FIREBASE  = 'firebase';
    const GOOGLE    = 'google';
    const SENDGRID  = 'sendgrid';
    const AWS       = 'aws';

    public static function all()
    {
        return [
            self::FIREBASE,
            self::GOOGLE,
            self::SENDGRID,
            self::AWS,
        ];
    }

    public static function getServiceNames()
    {
        $services = [];

        foreach (self::all() as $service) {
            $services[$service] = trans('log::strings.services.' . $service);
        }

        return $services;
    }
}
