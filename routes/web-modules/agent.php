<?php

// Agent web routes (Phases 08-12). Loaded by routes/web.php via glob.
// Every route is behind auth plus the module.action permission; the
// controller and policy then enforce branch/country/ownership scope.

use App\Http\Controllers\AgentController;
use App\Http\Controllers\PartyDocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('agent-documents', [PartyDocumentController::class, 'agentDocuments'])->middleware('permission:agent.view')->name('agent-documents.index');
    Route::get('agents/{agent}/documents/{document}/download', [PartyDocumentController::class, 'downloadAgentDocument'])
        ->middleware('permission:agent.view')->name('agents.documents.download');
    Route::get('agents', [AgentController::class, 'index'])->middleware('permission:agent.view')->name('agents.index');
    Route::get('agents/create', [AgentController::class, 'create'])->middleware('permission:agent.create')->name('agents.create');
    Route::post('agents', [AgentController::class, 'store'])->middleware('permission:agent.create')->name('agents.store');
    Route::get('agents/{agent}', [AgentController::class, 'show'])->middleware('permission:agent.view')->name('agents.show');
    Route::get('agents/{agent}/edit', [AgentController::class, 'edit'])->middleware('permission:agent.edit')->name('agents.edit');
    Route::put('agents/{agent}', [AgentController::class, 'update'])->middleware('permission:agent.edit')->name('agents.update');
    Route::delete('agents/{agent}', [AgentController::class, 'destroy'])->middleware('permission:agent.delete')->name('agents.destroy');
    Route::put('agents/{agent}/countries', [AgentController::class, 'syncCountries'])
        ->middleware('permission:agent.edit')->name('agents.countries');
});
