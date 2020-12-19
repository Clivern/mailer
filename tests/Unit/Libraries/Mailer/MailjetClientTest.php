<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Libraries\Mailer;

use App\Libraries\Mailer\MailjetClient;
use App\Libraries\Mailer\Message;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * MailjetClient Test Cases
 */
class MailjetClientTest extends TestCase
{
    /** @var MailjetClient */
    private $mailjetClient;

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->mailjetClient = new MailjetClient([
            'api_public_key' => '123',
            'api_private_key' => '456',
            'from' => [
                'name' => 'testName',
                'email' => 'test@example.com'
            ]
        ]);
    }

    /**
     * Test Send Message
     *
     * @return void
     */
    public function testSend()
    {
        $message = new Message();
        $message->setSubject('Hello');
        $message->setTo([
            [
                'email' => 'to@example.com',
                'name' => 'toName'
            ]
        ]);
        $message->setContent(Message::TEXT_TYPE, 'Something');

        Http::fake();

        $this->assertTrue($this->mailjetClient->send($message));

        Http::assertSent(function (Request $request) {
            $this->assertSame($request['Messages'], [
                [
                    'From' => [
                        'Email' => 'test@example.com',
                        'Name' => 'testName'
                    ],
                    'To' => [
                        [
                            'Email' => 'to@example.com',
                            'Name' => 'toName'
                        ]
                    ],
                    'subject' => 'Hello',
                    'TextPart' => 'Something'
                ]
            ]);
            return $request->hasHeader('Authorization', 'Basic MTIzOjQ1Ng==') &&
                   $request->url() === 'https://api.mailjet.com/v3.1/send';
        });
    }
}
