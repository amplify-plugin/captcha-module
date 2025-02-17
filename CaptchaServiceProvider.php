<?php

namespace Amplify\System\Captcha;

use Amplify\System\Captcha\Controllers\CaptchaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;

/**
 * Class CaptchaServiceProvider
 */
class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Merge configs
        $this->mergeConfigFrom(
            __DIR__ . '/config/captcha.php',
            'captcha'
        );

        // Bind captcha
        $this->app->bind('captcha', function ($app) {
            return new Captcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Contracts\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
    }

    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        // Publish configuration files
        $this->publishes([
            __DIR__ . '/config/captcha.php' => config_path('captcha.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/resources/backgrounds' => public_path('vendor/captcha/backgrounds'),
        ], 'captcha-background');
        
        // HTTP routing
        if (! config('captcha.disable')) {
            /**
             * @var Route $router
             */
            $router = $this->app['router'];
            $router->controller(CaptchaController::class)->group(function () use (&$router) {
                $router->get('captcha/api/{config?}', 'getCaptchaApi')->middleware('web');
                $router->get('captcha/{config?}', 'getCaptcha')->middleware('web');
                $router->get('admin/reload-captcha', 'reloadCaptcha')->middleware('web');
                $router->get('reload-captcha', 'reloadCaptcha')->middleware('web');
            });
        }

        /* @var Factory $validator */
        $validator = $this->app['validator'];

        // Validator extensions
        $validator->extend('captcha', function ($attribute, $value, $parameters) {
            return config('captcha.disable') || ($value && captcha_check($value));
        });

        // Validator extensions
        $validator->extend('captcha_api', function ($attribute, $value, $parameters) {
            return config('captcha.disable') || ($value && captcha_api_check($value, $parameters[0], $parameters[1] ?? 'default'));
        });
    }
}
