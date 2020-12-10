<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Utils\Validator;
use Illuminate\Http\Request;

/**
 * Message Controller
 */
class MessageController extends Controller
{
    /** @var Validator $validator */
    private $validator;

    /**
     * Class constructor
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
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

        $job = (new SendEmail(json_decode($request->getContent())))->onQueue('messages');
        $jobId = dispatch($job);

        var_dump($job);
        var_dump($jobId);

        return response()->json([
            'id' => $jobId,
            'name' => 'message.send',
            'status' => 'PENDING',
            //'createdAt' => $job->created_at
        ]);
    }
}
