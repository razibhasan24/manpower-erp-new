<?php

use App\Http\Controllers\SelectionController;
use App\Models\Selection;
use App\Services\SelectionService;
use App\Http\Resources\SelectionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * API v1 for Selection & Grading. Same services and policies as the web UI
 * (Phase 01 section 11). Sanctum auth; permission middleware matches web.
 */

Route::prefix('v1')->group(function () {
Route::middleware(['auth:sanctum', 'permission:selection.view'])->group(function () {
    Route::get('selections', function (Request $request, SelectionService $service) {
        $records = $service->scopedQuery($request->user())->paginate(15);
        return SelectionResource::collection($records);
    })->name('api.selections.index');
    Route::get('selections/{selection}', function (Selection $selection) {
        return new SelectionResource($selection);
    })->name('api.selections.show')->whereNumber('selection');
});
Route::middleware(['auth:sanctum', 'permission:selection.create'])->post('selections', [SelectionController::class, 'store'])->name('api.selections.store');
Route::middleware(['auth:sanctum', 'permission:selection.edit'])->group(function () {
    Route::put('selections/{selection}', [SelectionController::class, 'update'])->name('api.selections.update');
    Route::post('selections/{selection}/status', [SelectionController::class, 'changeStatus'])->name('api.selections.status');
});
});
