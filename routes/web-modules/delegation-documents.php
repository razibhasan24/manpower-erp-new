<?php

use App\Http\Controllers\DelegationDocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:delegation.view'])->group(function () {
    Route::get('delegation-documents', [DelegationDocumentController::class, 'index'])->name('delegation-documents.index');
    Route::get('delegation-documents/{delegationDocument}', [DelegationDocumentController::class, 'show'])->whereNumber('delegationDocument')->name('delegation-documents.show');
});

Route::middleware(['auth', 'permission:delegation.create'])->group(function () {
    Route::get('delegation-documents/create', [DelegationDocumentController::class, 'create'])->name('delegation-documents.create');
    Route::post('delegation-documents', [DelegationDocumentController::class, 'store'])->name('delegation-documents.store');
});

Route::middleware(['auth', 'permission:delegation.edit'])->group(function () {
    Route::get('delegation-documents/{delegationDocument}/edit', [DelegationDocumentController::class, 'edit'])->whereNumber('delegationDocument')->name('delegation-documents.edit');
    Route::put('delegation-documents/{delegationDocument}', [DelegationDocumentController::class, 'update'])->whereNumber('delegationDocument')->name('delegation-documents.update');
    Route::delete('delegation-documents/{delegationDocument}', [DelegationDocumentController::class, 'destroy'])->whereNumber('delegationDocument')->name('delegation-documents.destroy');
});

Route::middleware(['auth', 'permission:delegation.download'])->group(function () {
    Route::get('delegation-documents/{delegationDocument}/download', [DelegationDocumentController::class, 'download'])->whereNumber('delegationDocument')->name('delegation-documents.download');
});
