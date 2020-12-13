<?php

declare(strict_types=1);

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Libraries\Mailer;

/**
 * Message Class.
 */
class Message
{
    const HTML_TYPE = "html";

    const MARKDOWN_TYPE = "markdown";

    const TEXT_TYPE = "text";

    /** @var string */
    private $subject = "";

    /** @var array */
    private $from = [];

    /** @var array */
    private $to = [];

    /** @var string */
    private $content = "";

    /**
     * Set Message Subject
     *
     * @param string $subject
     */
    public function setSubject(string $subject): Message
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set Message From email & name
     *
     * @param string $email
     * @param string $name
     */
    public function setFrom(string $email, string $name): Message
    {
        $this->from = [
            "email" => $email,
            "name" => $name
        ];

        return $this;
    }

    /**
     * Set Message to emails and names
     *
     * @param array $to
     */
    public function setTo(array $to): Message
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Set message content & type
     *
     * @param string $type
     * @param string $value
     */
    public function setContent(string $type, string $value): Message
    {
        $this->content = [
            "type" => $type,
            "value" => $value
        ];

        return $this;
    }

    /**
     * Get Subject
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Get message from name and email
     *
     * @return array
     */
    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * Get message to email and name
     *
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * Get message content & type
     *
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }
}
