<?php

namespace Amplify\System\Captcha;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mews\Captcha\Captcha
 */
class CaptchaFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'captcha';
    }
}
