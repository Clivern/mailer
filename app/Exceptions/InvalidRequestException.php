<?php

declare(strict_types=1);

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Class InvalidRequestException.
 */
class InvalidRequestException extends BaseException
{
    /**
     * Class Constructor.
     *
     * @param string    $errorCode
     * @param int       $code
     * @param Exception $previous
     */
    public function __construct(
        string $message,
        $errorCode = ErrorCodes::ERROR_INVALID_REQUEST,
        $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $errorCode, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'Exception \'%s\' triggered with error code %s:%s%s',
            static::class,
            $this->getErrorCode(),
            PHP_EOL,
            parent::__toString()
        );
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        // Report for development debugging
        Log::debug(sprintf("InvalidRequest Exception raised: %s", $this->getMessage()));
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'errorCode' => $this->getErrorCode(),
            'errorMessage' => $this->getMessage(),
            'correlationId' => $request->headers->get('X-Correlation-ID'),
        ], Response::HTTP_BAD_REQUEST);
    }
}
