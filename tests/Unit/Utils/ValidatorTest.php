<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Utils;

use App\Exceptions\InvalidRequestException;
use App\Utils\Validator;
use Tests\TestCase;

/**
 * Validator Test Cases
 */
class ValidatorTest extends TestCase
{
    private $validator;

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new Validator();
    }

    public function testValidateSuccess()
    {
        $this->assertTrue($this->validator->validate('{"value":"something"}', 'v1/message/createAction.schema.json'));
    }

    public function testValidateFail()
    {
        $this->expectException(InvalidRequestException::class);
        $this->validator->validate('{"value":""}', 'v1/message/createAction.schema.json');
    }

    public function testCheckSuccess()
    {
        $this->assertSame(
            $this->validator->check('{"value":"something"}', 'v1/message/createAction.schema.json'),
            []
        );
    }

    public function testCheckFail()
    {
        $this->assertSame(
            $this->validator->check('{"key":}', 'v1/message/createAction.schema.json'),
            ["value: The property value is required"]
        );
    }
}
