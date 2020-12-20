<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Jobs;

use App\Libraries\Mailer\Mailjet;
use App\Libraries\Mailer\Message;
use App\Libraries\Mailer\Sendgrid;
use App\Models\JobStatus;
use App\Repository\JobStatusRepository;
use App\Service\MessageSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * SendEmail Async Jobs
 */
class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var JobStatus */
    protected $jobStatus;

    /**
     * Create a new job instance.
     *
     * @param mixed $jobStatus
     * @return void
     */
    public function __construct(JobStatus $jobStatus)
    {
        $this->jobStatus = $jobStatus;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        MessageSender $messageSender
    ) {
        Log::info(sprintf("Execute job with UUID %s", $this->jobStatus->uuid));

        $payload = json_decode($this->jobStatus->payload, true);

        $message = new Message();
        $message->setSubject($payload['message']['subject']);
        $message->setTo($payload['message']['to']);
        $message->setContent(
            $payload['message']['content']['type'],
            $payload['message']['content']['value']
        );

        // Switch between mail services at each attempt
        // First we start with Sendgrid then Mailjet then Sendgrid ... etc
        if ($this->attempts() % 2 === 0) {
            Log::info(sprintf("Attempt to send the message with UUID %s using Mailjet", $this->jobStatus->uuid));
            $status = Mailjet::send($message);
        } else {
            Log::info(sprintf("Attempt to send the message with UUID %s using Sendgrid", $this->jobStatus->uuid));
            $status = Sendgrid::send($message);
        }

        if (!$status) {
            throw new \Exception("Failed to send the email");
        }

        $messageSender->updateJobStatus(
            $this->jobStatus->uuid,
            JobStatusRepository::SUCCEEDED_STATUS
        );

        Log::info(sprintf("Finished job with UUID %s", $this->jobStatus->uuid));
    }

    /**
     * Handle a job failure.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        Log::error(sprintf(
            "Failure while executing job with UUID %s: %s",
            $this->jobStatus->uuid,
            $exception->getMessage()
        ));

        (new JobStatusRepository())->updateJobStatusByUUID(
            $this->jobStatus->uuid,
            JobStatusRepository::FAILED_STATUS
        );
    }
}
