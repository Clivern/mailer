<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Libraries;

use App\Exceptions\InvalidRequestException;
use App\Libraries\Validator;
use Tests\TestCase;

/**
 * Validator Test Cases
 */
class ValidatorTest extends TestCase
{
    private $validator;
    private $data;

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new Validator();

        $this->data = [
            "from" => [
                "email" => "from_address@example.com",
                "name" => "Joe"
            ],
            "to" => [
                [
                    "email" => "to@example.com",
                    "name" => "Joe"
                ]
            ],
            "subject" => "Hello World",
            "content" => [
                "type" => "text/html",
                "value" => "Something"
            ]
        ];
    }

    public function testValidateSuccess()
    {
        $this->assertTrue($this->validator->validate(json_encode($this->data), 'v1/message/createAction.schema.json'));
    }

    public function testValidateFail()
    {
        $data = $this->data;
        unset($data["subject"]);

        $this->expectException(InvalidRequestException::class);
        $this->validator->validate(json_encode($data), 'v1/message/createAction.schema.json');
    }

    public function testCheckSuccess()
    {
        $this->assertSame(
            $this->validator->check(json_encode($this->data), 'v1/message/createAction.schema.json'),
            []
        );
    }

    public function testCheckFail()
    {
        $data = $this->data;
        unset($data["subject"]);

        $this->assertSame(
            $this->validator->check(json_encode($data), 'v1/message/createAction.schema.json'),
            ["subject: The property subject is required"]
        );
    }
}
