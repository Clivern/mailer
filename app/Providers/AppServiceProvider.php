<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Providers;

use App\Jobs\SendEmail;
use App\Service\MessageSender;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \App::bind('sendgrid', function () {
            return new \App\Libraries\Mailer\SendgridClient([
                'api_key' => config('mail.services.sendgrid.api_key'),
                'from' => [
                    'email' => config('mail.from.address'),
                    'name' => config('mail.from.name')
                ]
            ]);
        });

        \App::bind('mailjet', function () {
            return new \App\Libraries\Mailer\MailjetClient([
                'api_public_key' => config('mail.services.mailjet.api_public_key'),
                'api_private_key' => config('mail.services.mailjet.api_private_key'),
                'from' => [
                    'email' => config('mail.from.address'),
                    'name' => config('mail.from.name')
                ]
            ]);
        });

        $this->app->bindMethod(SendEmail::class.'@handle', function ($job, $app) {
            return $job->handle($app->make(MessageSender::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
