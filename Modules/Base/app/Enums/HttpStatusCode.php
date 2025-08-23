<?php

namespace Modules\Base\Enums;

final class HttpStatusCode
{
    public const OK                     = 200;
    public const CREATED                = 201;
    public const ACCEPTED               = 202;
    public const NO_CONTENT             = 204;
    public const MOVED_PERMANENTLY      = 301;
    public const FOUND                  = 302;
    public const NOT_MODIFIED           = 304;
    public const BAD_REQUEST            = 400;
    public const UNAUTHORIZED           = 401;
    public const FORBIDDEN              = 403;
    public const NOT_FOUND              = 404;
    public const METHOD_NOT_ALLOWED     = 405;
    public const CONFLICT               = 409;
    public const GONE                   = 410;
    public const UNPROCESSABLE_ENTITY   = 422;
    public const INTERNAL_SERVER_ERROR  = 500;
    public const NOT_IMPLEMENTED        = 501;
    public const BAD_GATEWAY            = 502;
    public const SERVICE_UNAVAILABLE    = 503;
    public const GATEWAY_TIMEOUT        = 504;

    public static function all()
    {
        return [
            self::OK,
            self::CREATED,
            self::ACCEPTED,
            self::NO_CONTENT,
            self::MOVED_PERMANENTLY,
            self::FOUND,
            self::NOT_MODIFIED,
            self::BAD_REQUEST,
            self::UNAUTHORIZED,
            self::FORBIDDEN,
            self::NOT_FOUND,
            self::METHOD_NOT_ALLOWED,
            self::CONFLICT,
            self::GONE,
            self::UNPROCESSABLE_ENTITY,
            self::INTERNAL_SERVER_ERROR,
            self::NOT_IMPLEMENTED,
            self::BAD_GATEWAY,
            self::SERVICE_UNAVAILABLE,
            self::GATEWAY_TIMEOUT,
        ];
    }
}
