<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

use App\Http\Controllers\HealthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    // Message Endpoints
    Route::post('/message', [MessageController::class, 'sendAction']);

    // Job Endpoints
    Route::get('/job/{id}', [JobController::class, 'getAction']);
});
