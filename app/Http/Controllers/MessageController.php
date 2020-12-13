<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Libraries\Validator;
use App\Service\MessageSender;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Message Controller
 */
class MessageController extends Controller
{
    /** @var Validator $validator */
    private $validator;

    /** @var MessageSender $messageSender */
    private $messageSender;

    /**
     * Class constructor
     *
     * @param Validator $validator
     * @param MessageSender $messageSender
     */
    public function __construct(Validator $validator, MessageSender $messageSender)
    {
        $this->validator = $validator;
        $this->messageSender = $messageSender;
    }

    /**
     * Send Message Action
     *
     * @param  Request $request
     */
    public function sendAction(Request $request)
    {
        # Validate
        $this->validator->validate($request->getContent(), 'v1/message/createAction.schema.json');

        $result = $this->messageSender->dispatchMessage(json_decode($request->getContent(), true));

        return response()->json($result, Response::HTTP_ACCEPTED);
    }
}
