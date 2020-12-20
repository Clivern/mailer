<?php

declare(strict_types=1);

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Libraries\Mailer;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Http;

/**
 * Mailjet Client.
 */
class MailjetClient implements ClientInterface
{
    const API_URL = "https://api.mailjet.com/v3.1";

    /** @var array */
    private $configs;

    /**
     * Class constructor
     *
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->configs = $configs;
    }

    /**
     * {@inheritDoc}
     */
    public function send(Message $message): bool
    {
        $to = [];

        foreach ($message->getTo() as $key => $value) {
            $to[$key]["Email"] = $value['email'];

            if (!empty($value['name'])) {
                $to[$key]["Name"] = $value['name'];
            }
        }

        $payload = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->configs['from']['email'],
                        'Name' => $this->configs['from']['name']
                    ],
                    'To' => $to,
                    'subject' => $message->getSubject(),
                ]
            ]
        ];

        $content = $message->getContent();

        if ($content["type"] === Message::HTML_TYPE) {
            $content["type"] = "text/html";
            $payload['Messages'][0]['HTMLPart'] = $content["value"];
        } elseif ($content["type"] === Message::TEXT_TYPE) {
            $content["type"] = "text/plain";
            $payload['Messages'][0]['TextPart'] = $content["value"];
        } elseif ($content["type"] === Message::MARKDOWN_TYPE) {
            $content["type"] = "text/html";
            // Convert to HTML
            $payload['Messages'][0]['HTMLPart'] = Markdown::convertToHtml($content["value"]);
        }

        $response = Http::withHeaders([
            'Authorization' => sprintf(
                'Basic %s',
                base64_encode($this->configs['api_public_key'] . ':' . $this->configs['api_private_key'])
            ),
            'Content-Type' => 'application/json'
        ])->post(sprintf("%s/send", MailjetClient::API_URL), $payload);

        return (bool) $response->successful();
    }
}
