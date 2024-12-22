<?php

namespace Amplify\System\Captcha\Controllers;

use packages\Captcha\Captcha;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class CaptchaController
 */
class CaptchaController extends Controller
{
    /**
     * get CAPTCHA
     *
     * @return array|mixed
     *
     * @throws Exception
     */
    public function getCaptcha(Captcha $captcha, string $config = 'default')
    {
        if (ob_get_contents()) {
            ob_clean();
        }

        return $captcha->create($config);
    }

    /**
     * get CAPTCHA api
     *
     * @return array|mixed
     *
     * @throws Exception
     */
    public function getCaptchaApi(Captcha $captcha, string $config = 'default')
    {
        return $captcha->create($config, true);
    }

    public function reloadCaptcha(Request $request): JsonResponse
    {
        $captcha_type = $request->has('recaptcha_type') ? $request->recaptcha_type : captcha_type();

        return response()->json(['captcha' => captcha_img($captcha_type)]);
    }
}
