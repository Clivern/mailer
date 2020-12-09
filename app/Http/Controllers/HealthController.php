<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Health Check Controller
 */
class HealthController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'status' => 'ok'
        ]);
    }
}
