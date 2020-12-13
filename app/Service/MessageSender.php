<?php

declare(strict_types=1);

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Service;

use App\Exceptions\ResourceNotFoundException;
use App\Jobs\SendEmail;
use App\Repository\JobStatusRepository;
use Ramsey\Uuid\Uuid;

/**
 * Message Sender Service.
 */
class MessageSender
{
    /** @var JobStatusRepository $jobStatusRepository */
    protected $jobStatusRepository;

    /**
     * Class Constructor
     *
     * @param JobStatusRepository $jobStatusRepository
     */
    public function __construct(JobStatusRepository $jobStatusRepository)
    {
        $this->jobStatusRepository = $jobStatusRepository;
    }

    /**
     * Get Healthy Mail Service
     *
     * @return array
     */
    public function getHealthyMailService(): array
    {
    }

    /**
     * Dispatch Message
     *
     * @param array $message
     * @return array
     */
    public function dispatchMessage(array $message): array
    {
        $uuid = Uuid::uuid4()->toString();

        $job = $this->jobStatusRepository->insertOne([
            "uuid" => $uuid,
            "status" =>  JobStatusRepository::PENDING_STATUS,
            "type" => JobStatusRepository::SEND_MESSAGE_TYPE,
            "payload" => json_encode([
                "message" => $message
            ])
        ]);

        SendEmail::dispatch($job);

        return [
            'id' => $uuid,
            'type' => $job->type,
            'status' => $job->status,
            'createdAt' => $job->created_at
        ];
    }

    /**
     * Get Job Status by UUID
     *
     * @param  string $uuid
     * @return array
     */
    public function getJobStatusByUUID(string $uuid): array
    {
        $job = $this->jobStatusRepository->getOneByUUID($uuid);

        if (empty($job)) {
            throw new ResourceNotFoundException(sprintf("Job with uuid %s not found", $uuid));
        }

        return [
            'id' => $job->uuid,
            'type' => $job->type,
            'status' => $job->status,
            'createdAt' => $job->created_at
        ];
    }
}
