<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Health Controller Test Cases
 */
class HealthControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/_health');

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'ok'
            ]);
    }
}
