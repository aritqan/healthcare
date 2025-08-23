<?php

namespace Modules\Base\Http\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public $data = [];

    public $supportedLang ;

    public function __construct()
    {
        $this->supportedLang = view()->shared('_ALL_LOCALE_');
    }
}
