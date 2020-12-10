<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Http\Controllers;

use App\Utils\Validator;
use Illuminate\Http\Request;

/**
 * Job Controller
 */
class JobController extends Controller
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
     * Get Job by ID Action
     *
     * @param  int    $id
     */
    public function getAction($id)
    {
        // Get Job By ID

        return response()->json([
            'status' => 'ok'
        ]);
    }
}
