<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        if (config('app.env') === 'production' || $request->server->has('HTTP_X_FORWARDED_PROTO')) {
            URL::forceScheme('https');
        }

        Mail::extend('brevo', function (array $config = []) {
            $key = $config['key'] ?? config('services.brevo.key');

            return (new BrevoTransportFactory)->create(
                new Dsn(
                    'brevo+api',
                    'default',
                    $key
                )
            );
        });
    }
}