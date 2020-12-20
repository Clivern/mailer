<?php

declare(strict_types=1);

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Libraries\Mailer;

use Illuminate\Support\Facades\Facade;

/**
 * Sendgrid facade
 */
class Sendgrid extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sendgrid';
    }
}
