<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Logging Middleware Test Cases
 */
class LoggingTest extends TestCase
{
    public function testCorrelationInResponse()
    {
        $response = $this->get('/_health');

        $headers = $response->headers->all();
        $this->assertTrue(!empty($headers["x-correlation-id"][0]));
    }

    public function testLogging()
    {
        $response = $this->get('/_health');

        $logContent = file_get_contents(storage_path('logs/laravel.log'));

        $headers = $response->headers->all();

        // Request log record
        $this->assertTrue(mb_strpos($logContent, sprintf(
            'Incoming GET Request to _health with   {"CorrelationId":"%s"}',
            $headers["x-correlation-id"][0]
        )) !== false);

        // Response log record
        $this->assertTrue(mb_strpos($logContent, sprintf(
            'Outgoing 200 Response to _health with {"status":"ok"}  {"CorrelationId":"%s"}',
            $headers["x-correlation-id"][0]
        )) !== false);
    }
}
