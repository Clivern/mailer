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
 * Sendgrid Client.
 */
class SendgridClient implements ClientInterface
{
    const API_URL = "https://api.sendgrid.com/v3";

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
        $content = $message->getContent();

        if ($content["type"] === Message::HTML_TYPE) {
            $content["type"] = "text/html";
        } elseif ($content["type"] === Message::TEXT_TYPE) {
            $content["type"] = "text/plain";
        } elseif ($content["type"] === Message::MARKDOWN_TYPE) {
            $content["type"] = "text/html";
            // Convert to HTML
            $content["value"] = Markdown::convertToHtml($content["value"]);
        }

        $response = Http::withHeaders([
            'Authorization' => sprintf('Bearer %s', $this->configs["api_key"]),
            'Content-Type' => 'application/json'
        ])->post(sprintf("%s/mail/send", SendgridClient::API_URL), [
            'personalizations' => [
                'to' => $message->getTo(),
                'from' => $message->getFrom(),
                'subject' => $message->getSubject(),
                'content' => $content
            ],
        ]);

        return (bool) $response->successful();
    }
}
