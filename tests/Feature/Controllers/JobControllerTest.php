<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Feature\Controllers;

use App\Repository\JobStatusRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * Job Controller Test Cases
 */
class JobControllerTest extends TestCase
{
    private $jobStatusRepository;

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->jobStatusRepository = new JobStatusRepository();
    }

    /**
     * @return void
     */
    public function testJobNotFound()
    {
        $uuid = Uuid::uuid4()->toString();

        $response = $this->get(sprintf('/api/v1/job/%s', $uuid));

        $response
            ->assertStatus(404)
            ->assertJsonPath(
                'errorMessage',
                sprintf("Job with uuid %s not found", $uuid)
            );
    }

    /**
     * @return void
     */
    public function testJobFound()
    {
        $uuid = Uuid::uuid4()->toString();

        $jobObj = $this->jobStatusRepository->insertOne([
            "uuid" => $uuid,
            "status" => JobStatusRepository::PENDING_STATUS,
            "type" => JobStatusRepository::SEND_MESSAGE_TYPE,
            "payload" => "{}"
        ]);

        $response = $this->get(sprintf('/api/v1/job/%s', $uuid));

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'id',
                $uuid
            )->assertJsonPath(
                'type',
                JobStatusRepository::SEND_MESSAGE_TYPE
            )->assertJsonPath(
                'status',
                JobStatusRepository::PENDING_STATUS
            );
    }
}
