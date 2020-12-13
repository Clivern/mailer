<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Service;

use App\Exceptions\InvalidRequestException;
use App\Service\MessageSender;
use Tests\TestCase;

/**
 * MessageSender Test Cases
 */
class MessageSenderTest extends TestCase
{
    private $messageSender;

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->messageSender = new MessageSender();
    }
}
