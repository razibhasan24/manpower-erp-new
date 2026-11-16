<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Base controller. All controllers stay thin: validate with a Form
 * Request, authorise with a Policy, call a Service, return a response.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
