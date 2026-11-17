<?php

// Recruitment web routes (Phases 08-12). Loaded by routes/web.php via glob.
// Every route is behind auth plus the module.action permission; the
// controller and policy then enforce branch/country/ownership scope.

use App\Http\Controllers\RecruitmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('recruitments', [RecruitmentController::class, 'index'])->middleware('permission:recruitment.view')->name('recruitments.index');
    Route::get('recruitments/create', [RecruitmentController::class, 'create'])->middleware('permission:recruitment.create')->name('recruitments.create');
    Route::post('recruitments', [RecruitmentController::class, 'store'])->middleware('permission:recruitment.create')->name('recruitments.store');
    Route::post('/recruitments/{recruitment}/approve',[RecruitmentController::class, 'approve'])->name('recruitments.approve');
    Route::get('recruitments/tracking', [RecruitmentController::class, 'tracking'])->middleware('permission:recruitment.view')->name('recruitments.tracking');
    Route::get('recruitments/{recruitment}', [RecruitmentController::class, 'show'])->middleware('permission:recruitment.view')->name('recruitments.show');
    Route::get('recruitments/{recruitment}/edit', [RecruitmentController::class, 'edit'])->middleware('permission:recruitment.edit')->name('recruitments.edit');
    Route::put('recruitments/{recruitment}', [RecruitmentController::class, 'update'])->middleware('permission:recruitment.edit')->name('recruitments.update');
    Route::delete('recruitments/{recruitment}', [RecruitmentController::class, 'destroy'])->middleware('permission:recruitment.delete')->name('recruitments.destroy');
    Route::post('recruitments/{recruitment}/cancel', [RecruitmentController::class, 'cancel'])
        ->middleware('permission:recruitment.delete')->name('recruitments.cancel');
    Route::get('recruitments/{recruitment}/documents/{document}/download', [RecruitmentController::class, 'downloadDocument'])
        ->middleware('permission:document.download')->name('recruitments.documents.download');
});