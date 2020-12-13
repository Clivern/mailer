<?php

declare(strict_types=1);

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Libraries\Mailer;

use Illuminate\Support\Facades\Http;

/**
 * Mailgun Client.
 */
class MailgunClient implements ClientInterface
{
    private $configs;

    /**
     * Class constructor
     *
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->configs = $configs;
    }

    /**
     * {@inheritDoc}
     */
    public function send(Message $message): bool
    {
        //
    }
}
