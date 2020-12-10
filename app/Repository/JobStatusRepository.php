<?php

declare(strict_types=1);

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Repository;

use App\Model\JobStatus;

class JobStatusRepository
{
    /**
     * Get an Job by UUID.
     */
    public function getOneByUUID(string $uuid): ?JobStatus
    {
        return JobStatus::where('uuid', $uuid)
               ->first();
    }

    /**
     * Get a JobStatus by ID.
     */
    public function getOneByID(int $id): ?JobStatus
    {
        return JobStatus::where('id', $id)
               ->first();
    }

    /**
     * Insert a New JobStatus.
     */
    public function insertOne(array $data): int
    {
        $jobStatus = new JobStatus();

        $jobStatus->uuid = $data["uuid"];
        $jobStatus->status = $data["status"];
        $jobStatus->type = $data["type"];
        $jobStatus->payload = $data["payload"];

        $jobStatus->save();

        return $jobStatus->id;
    }

    /**
     * Update JobStatus by ID.
     */
    public function updateJobStatusById(int $id, string $status): bool
    {
        return (bool) JobStatus::where('id', $id)
            ->update(['status' => $status]);
    }


    /**
     * Update JobStatus by UUID.
     */
    public function updateJobStatusByUUID(string $uuid, string $status): bool
    {
        return (bool) JobStatus::where('uuid', $uuid)
            ->update(['status' => $status]);
    }
}
