<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

/**
 * Base API controller — Phase 30.
 * All v1 controllers extend this for the consistent JSON shape
 * (Phase 01 §11) via the ApiResponse trait.
 */
class ApiController extends Controller
{
    use ApiResponse;

    protected int $perPage = 15;
}
