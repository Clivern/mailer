<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Service;

use App\Exceptions\InvalidRequestException;
use App\Exceptions\ResourceNotFoundException;
use App\Repository\JobStatusRepository;
use App\Service\MessageSender;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * MessageSender Test Cases
 */
class MessageSenderTest extends TestCase
{
    /** @var MessageSender */
    private $messageSender;

    /** @var JobStatusRepository */
    private $jobStatusRepository;

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->jobStatusRepository = new JobStatusRepository();
        $this->messageSender = new MessageSender($this->jobStatusRepository);

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

    public function testDispatchMessage()
    {
        Http::fake();

        $result = $this->messageSender->dispatchMessage($this->data);

        $logContent = file_get_contents(storage_path('logs/laravel.log'));

        $this->assertTrue(Str::contains(
            $logContent,
            sprintf("Execute job with UUID %s", $result['id'])
        ));

        $job = $this->messageSender->getJobStatusByUUID($result['id']);
        $this->assertSame(JobStatusRepository::PENDING_STATUS, $result["status"]);

        $this->assertTrue($this->messageSender->updateJobStatus($job['id'], JobStatusRepository::FAILED_STATUS));

        $job = $this->messageSender->getJobStatusByUUID($result['id']);
        $this->assertSame($job['status'], JobStatusRepository::FAILED_STATUS);
    }

    public function testGetJobStatusByUUIDFailure()
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->messageSender->getJobStatusByUUID(Uuid::uuid4()->toString());
    }
}
