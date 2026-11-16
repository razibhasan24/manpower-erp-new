<?php

namespace App\Http\Middleware;

use App\Models\AuditLog as AuditLogModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Request-level audit middleware.
 *
 * Records state-changing requests by authenticated users.
 * Passwords and file contents are never logged.
 */
class AuditLog
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            ! $request->user() ||
            ! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)
        ) {
            return $response;
        }

        try {
            AuditLogModel::create([
                'user_id' => $request->user()->id,
                'action' => 'request.' . strtolower($request->method()),
                'auditable_type' => null,
                'auditable_id' => null,
                'old_values' => null,
                'new_values' => [
                    'route' => $request->route()?->getName(),
                    'path' => $request->path(),
                    'fields' => array_keys(
                        $request->except([
                            'password',
                            'password_confirmation',
                            'current_password',
                        ])
                    ),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }
}