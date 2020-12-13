<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Feature\Controllers;

use App\Repository\JobStatusRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Message Controller Test Cases
 */
class MessageControllerTest extends TestCase
{
    private $data;

    private $jobStatusRepository;

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->jobStatusRepository = new JobStatusRepository();

        $this->data = [
            "to" => [
                [
                    "email" => "to@example.com",
                    "name" => "Joe"
                ]
            ],
            "subject" => "Hello World",
            "content" => [
                "type" => "html",
                "value" => "Something"
            ]
        ];
    }

    /**
     * @return void
     */
    public function testSuccess()
    {
        Http::fake();

        $response = $this->postJson('/api/v1/message', $this->data);
        $logContent = file_get_contents(storage_path('logs/laravel.log'));

        $responseObj = json_decode($response->getContent());

        // Validate if job data store in database
        $job = $this->jobStatusRepository->getOneByUUID($responseObj->id);
        $payloadObj = json_decode($job->payload, true);
        $this->assertSame($payloadObj["message"], $this->data);

        // Validate that job queue works
        $this->assertTrue(Str::contains($logContent, sprintf("Execute job with UUID %s", $responseObj->id)));

        // Validate response
        $response
            ->assertStatus(202)
            ->assertJsonPath(
                'type',
                JobStatusRepository::SEND_MESSAGE_TYPE
            )->assertJsonPath(
                'status',
                JobStatusRepository::PENDING_STATUS
            );
    }

    /**
     * @return void
     */
    public function testInvalidRequest()
    {
        $data = $this->data;
        unset($data["subject"]);

        $response = $this->postJson('/api/v1/message', $data);

        $response
            ->assertStatus(400)
            ->assertJsonPath(
                'errorMessage',
                "subject: The property subject is required"
            );
    }
}
