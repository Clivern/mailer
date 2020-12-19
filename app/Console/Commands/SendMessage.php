<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Console\Commands;

use App\Service\MessageSender;
use Illuminate\Console\Command;

/**
 * Send a message command
 */
class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "message:send {--to_email=} {--to_name=} {--subject=} {--type=} {--body=}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a message';

    /**
     * @var MessageSender
     */
    protected $messageSender;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MessageSender $messageSender)
    {
        parent::__construct();
        $this->messageSender = $messageSender;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $result = $this->messageSender->dispatchMessage([
                "to" => [
                    [
                        "email" => $this->option('to_email'),
                        "name" => $this->option('to_name')
                    ]
                ],
                "subject" => $this->option('subject'),
                "content" => [
                    "type" => $this->option('type'),
                    "value" => $this->option('body')
                ]
            ]);

            $this->info(sprintf(
                'A Job with id %s got created to send the message',
                $result["id"]
            ));
        } catch (\Exception $e) {
            $this->error(sprintf('Something gone wrong: %s'. $e->getMessage()));
        }
    }
}
