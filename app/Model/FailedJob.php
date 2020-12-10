<?php

/*
 * This file is part of Mailer - Transactional Email Microservice.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'failed_jobs';
}
