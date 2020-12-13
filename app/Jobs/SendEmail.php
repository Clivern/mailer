<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Jobs;

use App\Libraries\Mailer\Message;
use App\Libraries\Mailer\Sendgrid;
use App\Model\JobStatus;
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

        Sendgrid::send($message);

        $messageSender->updateJobStatus(
            $this->jobStatus->uuid,
            JobStatusRepository::SUCCEEDED_STATUS
        );
    }
}
