<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @throws \Throwable
     * @return void
     *
     */
    public function report(Throwable $exception)
    {
        // Report unclassified exceptions
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @throws \Throwable
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function render($request, Throwable $exception)
    {
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }
        return response()->json([
                'errorCode' => ErrorCodes::ERROR_SERVER_ERROR,
                'errorMessage' => "Internal server error! Please contact system administrators.",
                'correlationId' => $request->headers->get('X-Correlation-ID'),
            ], Response::HTTP_BAD_REQUEST);
    }
}
