<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Repository;

use App\Repository\JobStatusRepository;
use Tests\TestCase;

/**
 * JobStatusRepository Test Cases
 */
class JobStatusRepositoryTest extends TestCase
{
    private $validator;

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->jobStatusRepository = new JobStatusRepository();
    }

    /**
     * Test getOneByUUID
     */
    public function testGetOneByUUID()
    {
        $jobId = $this->jobStatusRepository->insertOne([
            "uuid" => "x-x-x-x",
            "status" => "PENDING",
            "type" => "messages.action",
            "payload" => "{}"
        ]);

        $job = $this->jobStatusRepository->getOneByUUID("x-x-x-x");

        $this->assertSame($jobId, $job->id);
        $this->assertSame($job->uuid, "x-x-x-x");
        $this->assertSame($this->jobStatusRepository->getOneByUUID("x-x-x-x-x"), null);
    }

    /**
     * Test getOneByID
     */
    public function testGetOneByID()
    {
        $jobId = $this->jobStatusRepository->insertOne([
            "uuid" => "y-y-y-y",
            "status" => "PENDING",
            "type" => "messages.action",
            "payload" => "{}"
        ]);

        $job = $this->jobStatusRepository->getOneByID($jobId);

        $this->assertSame($job->uuid, "y-y-y-y");
        $this->assertSame($job->status, "PENDING");
        $this->assertSame($this->jobStatusRepository->getOneByID(100), null);
    }

    /**
     * Test updateJobStatusById
     */
    public function testUpdateJobStatusById()
    {
        $jobId = $this->jobStatusRepository->insertOne([
            "uuid" => "z-z-z-z",
            "status" => "PENDING",
            "type" => "messages.action",
            "payload" => "{}"
        ]);

        $this->assertTrue($this->jobStatusRepository->updateJobStatusById($jobId, "FAILED"));
        $this->assertFalse($this->jobStatusRepository->updateJobStatusById(1222, "FAILED"));

        $job = $this->jobStatusRepository->getOneByID($jobId);

        $this->assertSame($job->status, "FAILED");
    }

    /**
     * Test updateJobStatusByUUID
     */
    public function testUpdateJobStatusByUUID()
    {
        $jobId = $this->jobStatusRepository->insertOne([
            "uuid" => "j-j-j-j",
            "status" => "PENDING",
            "type" => "messages.action",
            "payload" => "{}"
        ]);

        $this->assertTrue($this->jobStatusRepository->updateJobStatusByUUID("j-j-j-j", "SUCCESS"));
        $this->assertFalse($this->jobStatusRepository->updateJobStatusByUUID("j-j-j-j-j", "FAILED"));

        $job = $this->jobStatusRepository->getOneByID($jobId);

        $this->assertSame($job->status, "SUCCESS");
    }
}
