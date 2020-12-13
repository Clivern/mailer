<?php

declare(strict_types=1);

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Libraries;

use App\Exceptions\InvalidRequestException;
use Exception;
use Illuminate\Support\Facades\Log;
use JsonSchema\Validator as JsonValidator;

/**
 * Validator Module.
 */
class Validator
{
    /**
     * Validate JSON against JSON Schema.
     */
    public function validate(string $data, string $schemaName): bool
    {
        $errors = $this->check($data, $schemaName);

        if (!empty($errors)) {
            throw new InvalidRequestException($errors[0]);
        }

        return true;
    }

    /**
     * Check JSON against JSON Schema.
     */
    public function check(string $data, string $schemaName): array
    {
        try {
            $data = empty(trim($data)) ? '{}' : $data;
            $dataObj = json_decode($data);

            if (empty($dataObj)) {
                $dataObj = json_decode('{}');
            }

            $validator = new JsonValidator();

            $validator->validate(
                $dataObj,
                (object) [
                    '$ref' => 'file://'.base_path("schemas/" . $schemaName),
                ]
            );

            $messages = [];

            if ($validator->isValid()) {
                return $messages;
            }

            foreach ($validator->getErrors() as $error) {
                $messages[] = $error['property'].': '.$error['message'];
            }

            return $messages;
        } catch (Exception $e) {
            throw new InvalidRequestException('Invalid request');
        }
    }
}
