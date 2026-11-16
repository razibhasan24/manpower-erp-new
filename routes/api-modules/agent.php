<?php

// Agent API routes (Phases 08-12, /api/v1). Loaded by routes/api.php
// via glob. Sanctum auth plus the same module.action permissions and
// policies as the web routes.

use App\Http\Controllers\Api\V1\AgentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('agents', [AgentController::class, 'index'])->middleware('permission:agent.view')->name('api.agents.index');
    Route::post('agents', [AgentController::class, 'store'])->middleware('permission:agent.create')->name('api.agents.store');
    Route::get('agents/{agent}', [AgentController::class, 'show'])->middleware('permission:agent.view')->name('api.agents.show');
    Route::put('agents/{agent}', [AgentController::class, 'update'])->middleware('permission:agent.edit')->name('api.agents.update');
    Route::patch('agents/{agent}', [AgentController::class, 'update'])->middleware('permission:agent.edit')->name('api.agents.patch');
    Route::delete('agents/{agent}', [AgentController::class, 'destroy'])->middleware('permission:agent.delete')->name('api.agents.destroy');
});
