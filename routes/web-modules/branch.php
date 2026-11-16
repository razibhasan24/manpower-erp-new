<?php

// Branch web routes (Phases 08-12). Loaded by routes/web.php via glob.
// Every route is behind auth plus the module.action permission; the
// controller and policy then enforce branch/country/ownership scope.

use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('branches', [BranchController::class, 'index'])->middleware('permission:branch.view')->name('branches.index');
    Route::get('branches/create', [BranchController::class, 'create'])->middleware('permission:branch.create')->name('branches.create');
    Route::post('branches', [BranchController::class, 'store'])->middleware('permission:branch.create')->name('branches.store');
    Route::get('branches/{branch}', [BranchController::class, 'show'])->middleware('permission:branch.view')->name('branches.show');
    Route::get('branches/{branch}/edit', [BranchController::class, 'edit'])->middleware('permission:branch.edit')->name('branches.edit');
    Route::put('branches/{branch}', [BranchController::class, 'update'])->middleware('permission:branch.edit')->name('branches.update');
    Route::delete('branches/{branch}', [BranchController::class, 'destroy'])->middleware('permission:branch.delete')->name('branches.destroy');
});
