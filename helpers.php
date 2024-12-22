<?php

use Intervention\Image\ImageManager;

if (! function_exists('captcha')) {
    /**
     * @return array|ImageManager|mixed
     *
     * @throws Exception
     */
    function captcha(string $config = 'default')
    {
        return app('captcha')->create($config);
    }
}

if (! function_exists('captcha_src')) {

    function captcha_src(string $config = 'default'): string
    {
        return app('captcha')->src($config);
    }
}

if (! function_exists('captcha_img')) {

    function captcha_img(string $config = 'default', array $attrs = []): string
    {
        return app('captcha')->img($config, $attrs);
    }
}

if (! function_exists('captcha_check')) {

    function captcha_check(string $value): bool
    {
        return app('captcha')->check($value);
    }
}

if (! function_exists('captcha_api_check')) {

    function captcha_api_check(string $value, string $key, string $config = 'default'): bool
    {
        return app('captcha')->check_api($value, $key, $config);
    }
}

if (! function_exists('captcha_type')) {

    /**
     * captcha_type
     */
    function captcha_type(): string
    {
        return config('amplify.basic.recaptcha_type', 'math');
    }
}
