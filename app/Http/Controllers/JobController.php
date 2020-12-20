<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Http\Controllers;

use App\Service\MessageSender;
use Illuminate\Http\Request;

/**
 * Job Controller
 */
class JobController extends Controller
{
    /** @var MessageSender $messageSender */
    private $messageSender;

    /**
     * Class constructor
     *
     * @param MessageSender $messageSender
     */
    public function __construct(MessageSender $messageSender)
    {
        $this->messageSender = $messageSender;
    }

    /**
     * Get Job by UUID Action
     *
     * @param  string    $id
     */
    public function getAction($id)
    {
        $result = $this->messageSender->getJobStatusByUUID($id);

        return response()->json($result);
    }
}
