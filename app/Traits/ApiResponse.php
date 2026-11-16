<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * ApiResponse — Phase 30.
 * The single JSON shape for the whole v1 API (Phase 01 §11):
 *   success: { "data": …, "meta": {…}, "message": … }
 *   error:   { "message": …, "errors": { field: […] } }
 * No HTML in API errors, pagination meta on every list.
 */
trait ApiResponse
{
    protected function success(mixed $data = null, ?string $message = null, int $status = 200, array $meta = []): JsonResponse
    {
        $payload = ['data' => $data];

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        if ($message !== null) {
            $payload['message'] = $message;
        }

        return response()->json($payload, $status);
    }

    protected function paginated(\Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator, mixed $data, ?string $message = null): JsonResponse
    {
        return $this->success($data, $message, 200, [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ]);
    }

    protected function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        $payload = ['message' => $message];

        if ($errors !== []) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
