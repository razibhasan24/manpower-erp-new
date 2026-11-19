<?php

// Passport Holder web routes (Phases 08-12). Loaded by routes/web.php via glob.
// Every route is behind auth plus the module.action permission; the
// controller and policy then enforce branch/country/ownership scope.

use App\Http\Controllers\PassportHolderController;
use App\Http\Controllers\PartyDocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('passport-holder-documents', [PartyDocumentController::class, 'passportHolderDocuments'])->middleware('permission:passport-holder.view')->name('passport-holder-documents.index');
    Route::get('passport-holders', [PassportHolderController::class, 'index'])->middleware('permission:passport-holder.view')->name('passport-holders.index');
    Route::get('passport-holders/create', [PassportHolderController::class, 'create'])->middleware('permission:passport-holder.create')->name('passport-holders.create');
    Route::post('passport-holders', [PassportHolderController::class, 'store'])->middleware('permission:passport-holder.create')->name('passport-holders.store');
    Route::get('passport-holders/{passport_holder}', [PassportHolderController::class, 'show'])->middleware('permission:passport-holder.view')->name('passport-holders.show');
    Route::get('passport-holders/{passport_holder}/edit', [PassportHolderController::class, 'edit'])->middleware('permission:passport-holder.edit')->name('passport-holders.edit');
    Route::put('passport-holders/{passport_holder}', [PassportHolderController::class, 'update'])->middleware('permission:passport-holder.edit')->name('passport-holders.update');
    Route::delete('passport-holders/{passport_holder}', [PassportHolderController::class, 'destroy'])->middleware('permission:passport-holder.delete')->name('passport-holders.destroy');
    Route::get('passport-holders/{passport_holder}/documents/{document}/download', [PassportHolderController::class, 'downloadDocument'])
        ->middleware('permission:passport-holder.download')->name('passport-holders.documents.download');
});
