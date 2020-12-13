<?php

declare(strict_types=1);

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Libraries\Mailer;

/**
 * Client Interface.
 */
interface ClientInterface
{
    /**
     * Set Message
     *
     * @param  Message $message
     * @return bool
     */
    public function send(Message $message): bool;
}
