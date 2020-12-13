<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Libraries\Mailer;

use App\Libraries\Mailer\Message;
use App\Libraries\Mailer\SendgridClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * SendgridClient Test Cases
 */
class SendgridClientTest extends TestCase
{
    /** @var SendgridClient */
    private $sendgridClient;

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->sendgridClient = new SendgridClient(["api_key" => "123"]);
    }

    /**
     * Test Send Message
     *
     * @return void
     */
    public function testSend()
    {
        $message = new Message();
        $message->setSubject("Hello");
        $message->setFrom("from@example.com", "Joe");
        $message->setTo([
            "email" => "to@example.com",
            "name" => "Doe"
        ]);
        $message->setContent(Message::TEXT_TYPE, "Something");

        Http::fake();

        $this->assertTrue($this->sendgridClient->send($message));

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('Authorization', 'Bearer 123') &&
                   $request->url() === 'https://api.sendgrid.com/v3/mail/send' &&
                   $request['personalizations']['subject'] === 'Hello' &&
                   $request['personalizations']['from']['email'] === 'from@example.com' &&
                   $request['personalizations']['to']['email'] === 'to@example.com' &&
                   $request['personalizations']['content']['value'] === 'Something' &&
                   $request['personalizations']['content']['type'] === 'text/plain';
        });
    }
}
